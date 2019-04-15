<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function byCategory()
    {
        $expenses = DB::table('expenses')
            ->select('category', DB::raw('SUM(denomination) as total'))
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
            ->groupBy('day')
            ->get();

        return response()->json([
            'data' => $expenses,
            'status' => 'success',
        ]);
    }
}
