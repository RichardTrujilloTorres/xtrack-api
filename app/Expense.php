<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
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

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeByCategory($query)
    {
        return $query
            ->select('category', DB::raw('SUM(denomination) as total'))
            ->where(DB::raw('YEAR(created_at)'), DB::raw('YEAR(CURRENT_DATE())'))
            ->groupBy('category')
            ;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeByMonth($query)
    {
        return $query
            ->select('category', DB::raw('SUM(denomination) as total'), DB::raw('MONTH(created_at) as month'))
            ->where(DB::raw('YEAR(created_at)'), DB::raw('YEAR(CURRENT_DATE())'))
            ->groupBy('category', 'month')
            ;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeByDay($query)
    {
        return $query
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('SUM(denomination) as total'))
            ->where(DB::raw('YEAR(created_at)'), DB::raw('YEAR(CURRENT_DATE())'))
            ->groupBy('day')
            ;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeHighest($query)
    {
            return $query
                ->select('description', DB::raw('CAST(denomination AS DECIMAL(5,2)) as denomination'), 'category')
                ->where(DB::raw('MONTH(created_at)'), DB::raw('MONTH(CURRENT_DATE())'))
                ->where(DB::raw('YEAR(created_at)'), DB::raw('YEAR(CURRENT_DATE())'))
                ->orderBy('denomination', 'desc')
                ->limit(1)
                ;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeHighestCategory($query)
    {
        return $query
            ->select('category', DB::raw('SUM(denomination) as total'))
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->limit(1)
            ;
    }
}
