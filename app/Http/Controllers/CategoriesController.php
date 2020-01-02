<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class CategoriesController
 * @package App\Http\Controllers
 */
class CategoriesController extends Controller
{
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

        return response()->json([
            'data' => $categories->toArray(),
            'status' => 'success',
        ]);
    }

    /**
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $slug)
    {
        $category = Category::findOrFail($slug);

        return response()->json([
            'data' => $category,
            'status' => 'success',
        ]);
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

        return response()->json([
            'data' => $category,
            'status' => 'success',
        ], 201);
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

        return response()->json([
            'data' => $category,
            'status' => 'success',
        ], 201);
    }

    /**
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(string $slug)
    {
        $category = Category::findOrFail($slug);

        $category->delete();

        return response()->json([
            'data' => [],
            'status' => 'success',
        ], 201);
    }
}
