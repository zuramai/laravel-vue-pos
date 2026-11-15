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

    /**
     * Admin product list search. Name AND code must match (defect D-07, kept).
     */
    public function scopeSearchNameAndCode($query, $term)
    {
        return $query->where('name', 'LIKE', '%'.$term.'%')->where('code', 'LIKE', '%'.$term.'%');
    }

    public function discount() {
        return $this->hasOne('App\Model\Discount');
    }

    public function category() {
        return $this->belongsTo('App\Model\Category');
    }
}
