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
        // TODO clean up
        $sort = $this->request->sort;
        $parts = explode('|', $sort);
        $field = @$parts[0] ? @$parts[0] : 'id';
        $direction = @$parts[1] ? @$parts[1] : 'asc';

        // TODO repository
        return Expense::orderBy($field, $direction)->paginate($this->request->per_page);
    }

    public function show($id)
    {
        $expense = Expense::findOrFail($id);

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

    public function update($id)
    {
        $expense = Expense::findOrFail($id);

        $expense->update($this->request->all());

        return response()->json([
            'data' => $expense->toArray(),
            'status' => 'success',
        ], 201);
    }

    public function delete($id)
    {
        $expense = Expense::findOrFail($id);

        $expense->delete();

        return response()->json([
            'data' => [],
            'status' => 'success',
        ], 201);
    }
}
