<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Customer;
use App\Services\ImageUploader;
use App\Http\Resources\CustomerCollection;
use Validator;

class CustomerController extends Controller
{
    const IMAGE_FOLDER = 'images/customers';

    private $images;

    public function __construct(ImageUploader $images)
    {
        $this->images = $images;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return new CustomerCollection(Customer::search($request->search)->orderBy('id','DESC')->paginate(self::PER_PAGE));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'alamat' => 'required',
            'phone' => 'required',
            'provinsi' => 'required',
            'kota' => 'required',
            'email' => 'required|email',
            'photo' => 'nullable'
        ]);

        $customer = new Customer;
        $customer->name = $request->name;
        $customer->alamat = $request->alamat;
        $customer->role = $request->role;
        $customer->phone = $request->phone;
        $customer->kota = $request->kota;
        $customer->provinsi = $request->provinsi;
        $customer->email = $request->email;
        if($request->hasFile('photo')) {
            $customer->image_name = $this->images->store($request->file('photo'), self::IMAGE_FOLDER);
        }

        $customer->save();

        return response()->json(['status' => true]);
    }

    public function all() {
        return new CustomerCollection(Customer::orderBy('name','asc')->get());
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
