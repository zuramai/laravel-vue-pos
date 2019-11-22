<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    
    public function orders() {
        return $this->hasMany('App\Model\Transaction');
    }
}
