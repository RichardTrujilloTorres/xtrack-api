<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function byCategory()
    {
        $expenses = DB::table('expenses')
            ->select('category', DB::raw('SUM(denomination) as total'))
            ->where(DB::raw('YEAR(created_at)'), DB::raw('YEAR(CURRENT_DATE())'))
            ->groupBy('category')
            ->get();


        return response()->json([
            'data' => $expenses,
            'status' => 'success',
        ]);
    }

    public function byMonth()
    {
        $expenses = DB::table('expenses')
            ->select('category', DB::raw('SUM(denomination) as total'),DB::raw('MONTH(created_at) as month'))
            ->where(DB::raw('YEAR(created_at)'), DB::raw('YEAR(CURRENT_DATE())'))
            ->groupBy('category', 'month')
            ->get();

        return response()->json([
            'data' => $expenses,
            'status' => 'success',
        ]);
    }

    public function byDay()
    {
        $expenses = DB::table('expenses')
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('SUM(denomination) as total'))
            ->where(DB::raw('YEAR(created_at)'), DB::raw('YEAR(CURRENT_DATE())'))
            ->groupBy('day')
            ->get();

        return response()->json([
            'data' => $expenses,
            'status' => 'success',
        ]);
    }

    public function monthlySummary()
    {
        $highestExpense = DB::table('expenses')
            ->select('description', DB::raw('CAST(denomination AS DECIMAL(5,2)) as denomination'), 'category')
            ->where(DB::raw('MONTH(created_at)'), DB::raw('MONTH(CURRENT_DATE())'))
            ->where(DB::raw('YEAR(created_at)'), DB::raw('YEAR(CURRENT_DATE())'))
            ->orderBy('denomination', 'desc')
            ->first();

        $highestCategory = DB::table('expenses')
            ->select('category', DB::raw('SUM(denomination) as total'))
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->first();


        $data = [
            'highestExpense' => $highestExpense,
            'highestCategory' => $highestCategory,
        ];

        return response()->json([
            'data' => $data,
            'status' => 'success',
        ]);
    }
}
