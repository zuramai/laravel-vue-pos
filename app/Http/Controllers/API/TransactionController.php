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
    /** Registered by Route::resource. Not used by the UI: answers 200 with an empty body. */
    public function index()
    {
    }

    public function newTransaction(Request $request, TransactionService $transactions) {
        list($order, $totalPrice) = $transactions->checkout($request->all(), Auth::user()->id);

        return response()->json([
            'status' => true,
            'message' => $transactions->successMessage($order, $totalPrice, $request->bayar, $request->kembalian),
        ]);
    }

    public function history(Request $request) {
        $search = $request->get('search');
        return new TransactionCollection(Transaction::with(['details.product','customer'])->where('user_id',Auth::user()->id)->where('id','LIKE',"%$search%")->orderBy('id','desc')->paginate(self::PER_PAGE));
    }

    public function allHistory(Request $request) {
        $search = $request->get('search');
        return new TransactionCollection(Transaction::with(['details.product','cashier','customer'])->where('id','LIKE',"%$search%")->orderBy('id','desc')->paginate(self::PER_PAGE));
    }

    public function invoice($invoice_id) {
        return Transaction::with(['details.product','customer','payment_method'])->where('invoice',$invoice_id)->orderBy('id','desc')->first();
    }

    /** Route transaction/chart. Not used by the UI: answers 200 with an empty body. */
    public function chart() {
    }

    /** Registered by Route::resource. Not used by the UI: answers 200 with an empty body. */
    public function create()
    {
    }

    /** Registered by Route::resource. Not used by the UI: answers 200 with an empty body. */
    public function store(Request $request)
    {
    }

    /** Registered by Route::resource. Not used by the UI: answers 200 with an empty body. */
    public function show($id)
    {
    }

    /** Registered by Route::resource. Not used by the UI: answers 200 with an empty body. */
    public function edit($id)
    {
    }

    /** Registered by Route::resource. Not used by the UI: answers 200 with an empty body. */
    public function update(Request $request, $id)
    {
    }

    /** Registered by Route::resource. Not used by the UI: answers 200 with an empty body. */
    public function destroy($id)
    {
    }
}
