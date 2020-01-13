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
        return Expense::datatable($this->request);
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
        $expense = Expense::create($this->request->only(['denomination', 'description']));
        $expense->category()->associate($category);
        $expense->save();

        return $this->success($expense, Response::HTTP_CREATED);
    }

    public function update($id)
    {
        /**
         * @var Expense $expense
         */
        $expense = Expense::findOrFail($id);

        if ($this->request->has('category')) {
            $this->updateExpenseCategory($expense);
        }

        $expense->update($this->request->only(['denomination', 'description']));

        return $this->success($expense, Response::HTTP_CREATED);
    }

    /**
     * @param Expense $expense
     * @return bool
     */
    protected function updateExpenseCategory(Expense $expense)
    {
        /**
         * @var Category $category
         */
        $category = Category::findOrFail(@$this->request->category['slug']);

        $expense->category()->associate($category);
        return $expense->save();
    }

    public function delete($id)
    {
        $expense = Expense::findOrFail($id);

        $expense->delete();

        return $this->success([], Response::HTTP_CREATED);
    }
}
