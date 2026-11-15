<?php

namespace App\Model\Concerns;

/**
 * Search by name with LIKE '%term%', as the admin list pages do.
 */
trait Searchable
{
    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'LIKE', '%'.$term.'%');
    }
}
