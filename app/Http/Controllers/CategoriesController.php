<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Traits\ResponsesTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CategoriesController
 * @package App\Http\Controllers
 */
class CategoriesController extends Controller
{
    use ResponsesTrait;

    /**
     * @var Request
     */
    private $request;

    /**
     * CategoriesController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = Category::all();


        return $this->success($categories->toArray());
    }

    /**
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $slug)
    {
        $category = Category::findOrFail($slug);

        return $this->success($category);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store()
    {
        $this->validate($this->request, [
            'name' => 'required|unique:categories',
        ]);

        $slug = $this->request->has('slug') && ! empty($this->request->get('slug'))
                ? $this->request->get('slug')
                : Str::slug($this->request->get('name'));

        $category = Category::create(array_merge(
            $this->request->all(),
            ['slug' => $slug]
        ));

        return $this->success($category, Response::HTTP_CREATED);
    }

    /**
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(string $slug)
    {
        $this->validate($this->request, [
            'name' => 'required|unique:categories',
        ]);

        $category = Category::findOrFail($slug);

        $category->update($this->request->all());

        return $this->success($category, Response::HTTP_CREATED);
    }

    /**
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(string $slug)
    {
        $category = Category::findOrFail($slug);

        $category->delete();

        return $this->success([], Response::HTTP_CREATED);
    }

    /**
     * @param string $slug
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function expenses(string $slug, Request $request)
    {
        $category = Category::findOrFail($slug);

        $perPage = $request->has('per_page')
            ? $request->per_page
            : 20;

        $expenses = $category->expenses()->paginate($perPage);

        return $this->success($expenses, Response::HTTP_OK);
    }
}
