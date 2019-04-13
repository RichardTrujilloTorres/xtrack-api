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
}
