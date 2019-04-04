<?php

namespace App\Http\Controllers;

use App\Expense;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        // TODO
        // $this->middleware('auth:api');

        $this->request = $request;
    }

    public function index()
    {
        // TODO repository
        $data = Expense::all();

        return response()->json([
            'data' => $data->toArray(),
            'status' => 'success',
        ]);
    }

    public function show(Expense $expense)
    {
        return response()->json([
            'data' => $expense->toArray(),
            'status' => 'success',
        ]);
    }

    public function store()
    {
        $expense = Expense::create($this->request->all());

        return response()->json([
            'data' => $expense->toArray(),
            'status' => 'success',
        ], 201);
    }

    public function update(Expense $expense)
    {
        $expense->update($this->request->all());

        return response()->json([
            'data' => $expense->toArray(),
            'status' => 'success',
        ], 201);
    }

    public function delete(Expense $expense)
    {
        $expense->delete();

        return response()->json([
            'data' => [],
            'status' => 'success',
        ], 201);
    }
}
