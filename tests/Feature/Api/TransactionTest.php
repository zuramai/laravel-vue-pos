<?php

namespace Tests\Feature\Api;

use App\Model\Product;
use App\Model\Transaction;
use Tests\ApiTestCase;

class TransactionTest extends ApiTestCase
{
    /** One cart line as the kasir page sends it (product row + quantity, realPrice). */
    public static function cartLine($id, $quantity)
    {
        $products = [
            1 => ['id' => 1, 'name' => 'TestPPN', 'code' => 'TestPPN', 'price' => 100000, 'ppn' => 5, 'stock' => 2],
            2 => ['id' => 2, 'name' => 'Chocolatos', 'code' => 'CHO1', 'price' => 500, 'ppn' => 20, 'stock' => 155],
        ];
        $line = $products[$id];
        $line['realPrice'] = $line['price'];
        $line['quantity'] = $quantity;
        $line['price'] = $line['price'] * $quantity;

        return $line;
    }

    public function test_new_transaction_with_one_item_saves_order_details_and_stock()
    {
        $response = $this->apiJson('POST', 'transaction/new', [
            'kembalian' => 3200,
            'bayar' => 5000,
            'cart' => [static::cartLine(2, 3)],
            'customer' => 1,
            'payment_method' => 1,
        ], 'kasir');

        $response->assertStatus(200);
        $this->assertTrue($response->json('status'));
        $this->assertMatchesSnapshot('new_single', $response);

        $order = \App\Model\Transaction::with('details')->orderBy('id', 'desc')->first();
        $this->assertSame(2, (int) $order->user_id);
        $this->assertEquals(1800, $order->total);
        $this->assertEquals(100, $order->total_ppn);
        $this->assertEquals(5000, $order->bayar);
        $this->assertEquals(3200, $order->kembalian);
        $this->assertEquals(1, $order->customer_id);
        $this->assertEquals(1, $order->payment_method_id);
        $this->assertCount(1, $order->details);
        $this->assertEquals(600, $order->details[0]->price);
        $this->assertEquals(1800, $order->details[0]->subtotal);
        $this->assertEquals(100, $order->details[0]->ppn);
        $this->assertEquals(3, $order->details[0]->quantity);
        $this->assertSame(152, (int) Product::find(2)->stock);
    }

    public function test_new_transaction_with_many_items_sums_subtotals_and_ppn_per_unit()
    {
        $response = $this->apiJson('POST', 'transaction/new', [
            'kembalian' => 92800,
            'bayar' => 200000,
            'cart' => [static::cartLine(1, 1), static::cartLine(2, 2)],
            'customer' => 3,
            'payment_method' => 1,
        ], 'kasir');

        $response->assertStatus(200);
        $this->assertMatchesSnapshot('new_multi', $response);

        $order = \App\Model\Transaction::with('details')->orderBy('id', 'desc')->first();
        $this->assertEquals(105000 + 1200, $order->total);
        $this->assertEquals(5000 + 100, $order->total_ppn);
        $this->assertCount(2, $order->details);
        $this->assertSame([1, 2], $order->details->pluck('product_id')->map('intval')->all());
    }

    public function test_new_transaction_message_links_to_the_invoice_page()
    {
        $response = $this->apiJson('POST', 'transaction/new', [
            'kembalian' => 400, 'bayar' => 1000, 'cart' => [static::cartLine(2, 1)],
            'customer' => 1, 'payment_method' => 1,
        ], 'kasir');

        $invoice = Transaction::orderBy('id', 'desc')->first()->invoice;
        $this->assertContains("transaksi/invoice/{$invoice}'>Klik Disini</a>", $response->json('message'));
        $this->assertStringStartsWith('<b>Transaksi Sukses!</b> <br>Total Harga: <b>Rp600</b>', $response->json('message'));
    }

    public function test_history_lists_only_orders_of_the_logged_in_cashier()
    {
        $response = $this->api('GET', 'transaction/history', [], [], 'kasir');

        $response->assertStatus(200);
        $this->assertSame([15, 2], array_column($response->json('data'), 'id'));
        $this->assertMatchesSnapshot('history_kasir', $response);
    }

    public function test_history_search_matches_order_id_with_like()
    {
        $response = $this->api('GET', 'transaction/history', ['search' => '1'], [], 'kasir');

        $this->assertSame([15], array_column($response->json('data'), 'id'));
    }

    public function test_history_all_lists_every_order_with_cashier_paginated_by_ten()
    {
        $page1 = $this->api('GET', 'transaction/history/all');
        $page2 = $this->api('GET', 'transaction/history/all', ['page' => 2]);

        $page1->assertStatus(200);
        $this->assertSame(15, $page1->json('meta.total'));
        $this->assertSame([18, 17, 16, 15, 14, 13, 12, 11, 10, 9], array_column($page1->json('data'), 'id'));
        $this->assertSame([8, 7, 6, 2, 1], array_column($page2->json('data'), 'id'));
        $this->assertMatchesSnapshot('history_all_page_1', $page1);
        $this->assertMatchesSnapshot('history_all_page_2', $page2);
    }

    public function test_invoice_returns_the_order_with_details_customer_and_payment_method()
    {
        $response = $this->api('GET', 'transaction/invoice/1c079ebb-0185-4b80-ba72-30a8337d8382', [], [], 'kasir');

        $response->assertStatus(200);
        $this->assertSame(9, $response->json('id'));
        $this->assertMatchesSnapshot('invoice_order_9', $response);
    }

    public function test_invoice_unknown_id_returns_an_empty_200_response()
    {
        $response = $this->api('GET', 'transaction/invoice/tidak-ada', [], [], 'kasir');

        $response->assertStatus(200);
        $this->assertSame('', $response->getContent());
    }

    public function test_unused_endpoints_return_an_empty_200_response()
    {
        foreach (['transaction', 'transaction/chart', 'transaction/create', 'transaction/1', 'transaction/1/edit'] as $uri) {
            $response = $this->api('GET', $uri);
            $response->assertStatus(200);
            $this->assertSame('', $response->getContent(), $uri);
        }
    }
}
