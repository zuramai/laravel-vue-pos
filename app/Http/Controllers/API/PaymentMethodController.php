<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\PaymentMethod;
use App\Http\Resources\PaymentMethodCollection;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new PaymentMethodCollection(PaymentMethod::orderBy('id','asc')->get());
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
