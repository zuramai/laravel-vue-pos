<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Discount;
use App\Model\Product;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discounts = Discount::with('product')->orderBy('id','desc')->paginate(self::PER_PAGE);
        return response()->json($discounts);
    }

    /** Registered by Route::resource. Not used by the UI: answers 200 with an empty body. */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'product' => 'required|exists:products,code',
            'amount' => 'required|gte:1'
        ]);

        $product_id = Product::where('code',$request->product)->first()->id;

        $discount = new Discount;
        $discount->product_id = $product_id;
        $discount->amount = $request->amount;
        $discount->status = true;
        $discount->save();

        return response()->json(['status' => true, 'message' =>'Sukses Menambah Diskon!']);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $discount = Discount::find($id);
        $discount->delete();

        return response()->json(['status' => true, 'message' => 'Sukses Hapus Data Diskon']);
    }
}
