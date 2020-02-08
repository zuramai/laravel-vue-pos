<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $table = "order_details";
    protected $fillable = ['order_id','product_id','quantity','price','subtotal','ppn'];

    public function product() {
        return $this->belongsTo('App\Model\Product','product_id');
    }

    public function order() {
        return $this->belongsTo('App\Model\Transaction','order_id');
    }
}
