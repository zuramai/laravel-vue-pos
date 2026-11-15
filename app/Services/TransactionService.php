<?php

namespace App\Services;

use App\Model\Product;
use App\Model\Transaction;
use App\Model\TransactionDetail;
use Ramsey\Uuid\Uuid;

/**
 * Checkout of the kasir cart.
 *
 * The known defects of the checkout are kept as they are (see the defect log):
 * D-01 only the last cart line reduces the stock, D-02 an order without customer
 * fails, D-16 the discount is not applied, D-23 there is no database transaction.
 */
class TransactionService
{
    /**
     * Keys of one cart line. A line without one of these keys ends the request
     * with a PHP notice (500), also for 'price', which the checkout does not use.
     */
    const CART_LINE_KEYS = ['quantity', 'ppn', 'price', 'realPrice', 'id'];

    /**
     * Saves the order, its details, the totals and the stock.
     *
     * @param  array  $input  request input: cart, bayar, kembalian, payment_method, customer
     * @param  int  $cashierId
     * @return array  [Transaction $order, float|int $totalPrice]
     */
    public function checkout(array $input, $cashierId)
    {
        $order = $this->createOrder($input, $cashierId);

        $totalPrice = 0;
        $totalPpn = 0;
        foreach ($input['cart'] ?? null as $line) {
            $amounts = $this->saveDetail($order, $line);
            $totalPrice += $amounts['subtotal'];
            $totalPpn += $amounts['ppn'];
        }
        $order->update(['total' => $totalPrice, 'total_ppn' => $totalPpn]);

        // D-01: $line is the last cart line. An empty cart leaves $line undefined (500).
        $this->reduceStock($line);

        return [$order, $totalPrice];
    }

    public function successMessage(Transaction $order, $totalPrice, $bayar, $kembalian)
    {
        return '<b>Transaksi Sukses!</b> <br>Total Harga: <b>Rp'.number_format($totalPrice)
            ."</b><br>Jumlah Bayar: <b>Rp ".number_format($bayar)
            ."</b><br>Kembalian: <b>Rp ".number_format($kembalian)
            ."</b><br>Invoice: <a href='".url("transaksi/invoice/".$order->invoice)."'>Klik Disini</a>";
    }

    private function createOrder(array $input, $cashierId)
    {
        return Transaction::create([
            'invoice' => Uuid::uuid4(),
            'user_id' => $cashierId,
            'total' => 0,
            'total_ppn' => 0,
            'bayar' => $input['bayar'] ?? null,
            'kembalian' => $input['kembalian'] ?? null,
            'payment_method_id' => $input['payment_method'] ?? null,
            'customer_id' => $input['customer'] ?? null,
        ]);
    }

    private function saveDetail(Transaction $order, $line)
    {
        $this->requireCartLineKeys($line);
        $amounts = $this->lineAmounts($line);

        TransactionDetail::create([
            'order_id' => $order->id,
            'product_id' => $line['id'],
            'quantity' => $line['quantity'],
            'price' => $amounts['price'],
            'subtotal' => $amounts['subtotal'],
            'ppn' => $amounts['ppn'],
        ]);

        return $amounts;
    }

    private function requireCartLineKeys($line)
    {
        foreach (self::CART_LINE_KEYS as $key) {
            $line[$key];
        }
    }

    /**
     * PPN is a percentage of the unit price. The saved price includes PPN.
     */
    private function lineAmounts($line)
    {
        $ppnAmount = $line['realPrice'] * $line['ppn'] / 100;
        $price = $line['realPrice'] + $ppnAmount;

        return [
            'price' => $price,
            'subtotal' => $price * $line['quantity'],
            'ppn' => $ppnAmount,
        ];
    }

    private function reduceStock($line)
    {
        $product = Product::find($line['id']);
        $product->stock = $product->stock - $line['quantity'];
        $product->save();
    }
}
