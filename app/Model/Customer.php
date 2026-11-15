<?php

namespace App\Model;

use App\Model\Concerns\Searchable;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use Searchable;

    protected $table = 'customers';
    
    public function orders() {
        return $this->hasMany('App\Model\Transaction');
    }
}
