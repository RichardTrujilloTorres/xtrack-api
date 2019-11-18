<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Expense extends Model
{
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'denomination', 'description', 'category',
    ];

    public function searchableAs()
    {
        return 'expenses_index';
    }

    public function toSearchableArray()
    {
        return $this->toArray();
    }
}
