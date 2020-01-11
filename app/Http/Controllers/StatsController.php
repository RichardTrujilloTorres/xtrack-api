<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Http\Traits\ResponsesTrait;

/**
 * Class StatsController
 * @package App\Http\Controllers
 */
class StatsController extends Controller
{
    use ResponsesTrait;

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function byCategory()
    {
        $expenses = Expense::byCategory()->get();

        return $this->success($expenses);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function byMonth()
    {
        $expenses = Expense::byMonth()->get();

        return $this->success($expenses);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function byDay()
    {
        $expenses = Expense::byDay()->get();

        return $this->success($expenses);
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

        return $this->success($data);
    }
}
