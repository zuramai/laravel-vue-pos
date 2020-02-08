<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name','description','code','price','category_id','stock'
    ];

    public function discount() {
        return $this->hasOne('App\Model\Discount');
    }

    public function category() {
        return $this->belongsTo('App\Model\Category');
    }
}
