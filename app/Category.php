<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Category.
 */
class Category extends Model
{
    protected $primaryKey = 'slug';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'needed',
        'slug',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
