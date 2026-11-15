<?php

namespace App\Model;

use App\Model\Concerns\Searchable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Searchable;

    protected $fillable = [
        'name', 'image_name', 'description', 'slug'
    ];
}
