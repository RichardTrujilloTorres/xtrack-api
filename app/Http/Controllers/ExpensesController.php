<?php

namespace App\Http\Controllers;

use App\Category;
use App\Expense;
use App\Http\Traits\ResponsesTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExpensesController extends Controller
{
    use ResponsesTrait;

    /**
     * @var Request
     */
    private $request;

    /**
     * ExpensesController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
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

        return $this->success($expense);
    }

    public function store()
    {
        $this->validate($this->request, [
            'denomination' => 'required',
        ]);

        /**
         * @var Category $category
         */
        $category = Category::findOrFail(@$this->request->category['slug']);

        /**
         * @var Expense $expense
         */
        $expense = Expense::create(array_merge(
            $this->request->all(),
            ['category' => $category]
        ));

        return $this->success($expense, Response::HTTP_CREATED);
    }

    public function update($id)
    {
        $expense = Expense::findOrFail($id);

        $expense->update($this->request->all());

        return $this->success($expense, Response::HTTP_CREATED);
    }

    public function delete($id)
    {
        $expense = Expense::findOrFail($id);

        $expense->delete();

        return $this->success([], Response::HTTP_OK);
    }
}
