<?php

namespace Tests\Feature\Api;

use Tests\ApiTestCase;

class DiscountPaymentTest extends ApiTestCase
{
    public function test_discount_index_returns_laravel_paginator_json_with_product()
    {
        $response = $this->api('GET', 'discount');

        $response->assertStatus(200);
        $this->assertSame([4, 2], array_column($response->json('data'), 'id'));
        $this->assertMatchesSnapshot('discount_index', $response);
    }

    public function test_discount_store_by_product_code()
    {
        $response = $this->api('POST', 'discount', ['product' => 'CHO1', 'amount' => '15']);

        $response->assertStatus(200);
        $this->assertSame(['status' => true, 'message' => 'Sukses Menambah Diskon!'], $response->json());
        $this->assertDatabaseHas('discounts', ['product_id' => 2, 'amount' => 15, 'status' => 1]);
    }

    public function test_discount_store_with_unknown_product_or_low_amount_returns_422()
    {
        $response = $this->api('POST', 'discount', ['product' => 'XXX', 'amount' => '0']);

        $response->assertStatus(422);
        $this->assertMatchesSnapshot('discount_store_invalid', $response);
    }

    public function test_discount_destroy()
    {
        $response = $this->api('DELETE', 'discount/2');

        $response->assertStatus(200);
        $this->assertSame(['status' => true, 'message' => 'Sukses Hapus Data Diskon'], $response->json());
        $this->assertDatabaseMissing('discounts', ['id' => 2]);
    }

    public function test_discount_destroy_unknown_id_returns_500()
    {
        $this->api('DELETE', 'discount/999')->assertStatus(500);
    }

    public function test_payment_method_index()
    {
        $response = $this->api('GET', 'payment-method', [], [], 'kasir');

        $response->assertStatus(200);
        $this->assertMatchesSnapshot('payment_method_index', $response);
    }

    public function test_unused_resource_endpoints_return_an_empty_200_response()
    {
        foreach (['discount/create', 'discount/2', 'discount/2/edit', 'payment-method/create', 'payment-method/1'] as $uri) {
            $response = $this->api('GET', $uri);
            $response->assertStatus(200);
            $this->assertSame('', $response->getContent(), $uri);
        }
    }
}
