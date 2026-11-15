<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Transaction;
use App\Http\Resources\TransactionCollection;
use App\Services\TransactionService;
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
    }

    public function newTransaction(Request $r, TransactionService $transactions) {
        list($order, $totalPrice) = $transactions->checkout($r->all(), Auth::user()->id);

        return response()->json([
            'status' => true,
            'message' => $transactions->successMessage($order, $totalPrice, $r->bayar, $r->kembalian),
        ]);
    }

    public function history(Request $request) {
        $search = $request->get('search');
        return new TransactionCollection(Transaction::with(['details.product','customer'])->where('user_id',Auth::user()->id)->where('id','LIKE',"%$search%")->orderBy('id','desc')->paginate(10));
    }

    public function allHistory(Request $request) {
        $search = $request->get('search');
        return new TransactionCollection(Transaction::with(['details.product','cashier','customer'])->where('id','LIKE',"%$search%")->orderBy('id','desc')->paginate(10));
    }

    public function invoice(Request $r, $invoice_id) {
        return Transaction::with(['details.product','customer','payment_method'])->where('invoice',$invoice_id)->orderBy('id','desc')->first();
    }

    public function chart() {
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
