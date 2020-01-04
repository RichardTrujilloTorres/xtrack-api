<?php

namespace App\Http\Controllers;

use App\Expense;

/**
 * Class StatsController
 * @package App\Http\Controllers
 */
class StatsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function byCategory()
    {
        $expenses = Expense::byCategory()->get();

        return response()->json([
            'data' => $expenses,
            'status' => 'success',
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function byMonth()
    {
        $expenses = Expense::byMonth()->get();

        return response()->json([
            'data' => $expenses,
            'status' => 'success',
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function byDay()
    {
        $expenses = Expense::byDay()->get();

        return response()->json([
            'data' => $expenses,
            'status' => 'success',
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function monthlySummary()
    {
        $highestExpense = Expense::highest()->get()[0];

        $highestCategory = Expense::highestCategory()->get()[0];

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
