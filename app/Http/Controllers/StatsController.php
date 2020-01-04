<?php

namespace App\Http\Controllers;

use App\Expense;

class StatsController extends Controller
{
    public function byCategory()
    {
        $expenses = Expense::byCategory()->get();

        return response()->json([
            'data' => $expenses,
            'status' => 'success',
        ]);
    }

    public function byMonth()
    {
        $expenses = Expense::byMonth()->get();

        return response()->json([
            'data' => $expenses,
            'status' => 'success',
        ]);
    }

    public function byDay()
    {
        $expenses = Expense::byDay()->get();

        return response()->json([
            'data' => $expenses,
            'status' => 'success',
        ]);
    }

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
