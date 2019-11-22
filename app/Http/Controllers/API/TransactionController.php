<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Transaction;
use App\Model\TransactionDetail;
use App\Http\Resources\TransactionCollection;
use Ramsey\Uuid\Uuid;
use Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        // $search = $r->get('search');
        // return new TransactionCollection(Transaction::whereHas('details')->with(['details' => function($query) use ($id){
        //     $query->with('product' => function($query) use ($id){
        //             $query->where('product.id',$id);
        //     });
        // }])->where('id','LIKE',"%$search%")->orderBy('id','desc')->paginate(10));
    }

    
    public function newTransaction(Request $r) {
        $customer = $r->customer || 1;
        $payment_method = $r->payment_method || 1;
        $order = Transaction::create([
            'invoice' => Uuid::uuid4(),
            'user_id' => Auth::user()->id,
            'total' => 0,
            'bayar' => $r->bayar,
            'kembalian' => $r->kembalian,
            'payment_method_id' => $r->payment_method,
            'customer_id' => $r->customer,
        ]);
        $totalPrice = 0;
        foreach($r->cart as $data_cart) {
            $quantity = $data_cart['quantity'];
            $realPrice = $data_cart['realPrice'];

            $subtotal = $realPrice * $quantity;

            $create = TransactionDetail::create([
                'order_id' => $order->id,
                'product_id' => $data_cart['id'],
                'quantity' => $data_cart['quantity'],
                'price' => $realPrice,
                'subtotal' => $subtotal
            ]);
        $totalPrice += $subtotal;
        }
        Transaction::find($order->id)->update(['total' => $totalPrice]);
        return response()->json(['status' => true, 'message' => 'Transaksi Sukses']);
    }

    public function history(Request $request) {
        $search = $request->get('search');
        return new TransactionCollection(Transaction::with(['details.product','customer'])->where('user_id',Auth::user()->id)->where('id','LIKE',"%$search%")->orderBy('id','desc')->paginate(10));
    }

    public function invoice(Request $r, $invoice_id) {
        return Transaction::with(['details.product','customer'])->where('invoice',$invoice_id)->orderBy('id','desc')->first();
    }

    public function chart() {
        $data = [];
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
