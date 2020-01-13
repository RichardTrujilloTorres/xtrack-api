<?php

namespace Tests;

use App\Category;
use App\Expense;
use App\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CategoriesControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->login();
    }

    /**
     * @test
     */
    public function index()
    {
        /**
         * @var Category[] $categories
         */
        $categories = factory(Category::class, 10)->create();
        $response = $this->json(
            'GET',
            '/api/categories',
            [],
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $response->assertResponseOk();

        $this->seeJsonContains([
            'name' => $categories[0]->name,
        ]);
    }

    /**
     * @test
     */
    public function show()
    {
        /**
         * @var Category
         */
        $category = factory(Category::class)->create();
        $response = $this->json(
            'GET',
            '/api/categories/' . $category->slug,
            [],
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $response->assertResponseOk();

        $this->seeJsonContains([
            'name' => $category->name,
        ]);
    }

    /**
     * @test
     */
    public function returns404OnCategoryNotFound()
    {
        $response = $this->json(
            'GET',
            '/api/categories/not-found',
            [],
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $response
            ->assertResponseStatus(404);
    }

    /**
     * @test
     */
    public function returns422OnInvalidRequest()
    {
        $response = $this->json(
            'POST',
            '/api/categories',
            [],
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $response
            ->assertResponseStatus(422);

        $this->json('POST', '/api/categories', [
            'name' => 'dummy category',
            'slug' => '',
        ]);

        $response
            ->assertResponseStatus(201);

        $this->json(
            'POST',
            '/api/categories',
            [
            'name' => 'dummy category',
            ],
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $response
            ->assertResponseStatus(422);
    }

    /**
     * @test
     */
    public function itCreatesTheSlugWhenNotSpecified()
    {
        $this->json(
            'POST',
            '/api/categories',
            [
            'name' => 'dummy category',
            'slug' => '',
            ],
            [
            'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $this->assertResponseStatus(201);
        $this->seeJsonContains([
            'slug' => \Illuminate\Support\Str::slug('dummy category')
        ]);

        $this->json(
            'POST',
            '/api/categories',
            [
            'name' => 'dummy category again',
            ],
            [
            'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $this->assertResponseStatus(201);
        $this->seeJsonContains([
            'slug' => \Illuminate\Support\Str::slug('dummy category again')
        ]);
    }

    /**
     * @test
     */
    public function store()
    {
        $this->json(
            'POST',
            '/api/categories',
            [
            'name' => 'dummy category',
            'description' => 'Dummy description.',
            'slug' => 'some slug',
            ],
            [
            'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $this->assertResponseStatus(201);
        $this->seeJsonContains([
            'name' => 'dummy category',
            'description' => 'Dummy description.',
            'slug' => 'some slug',
        ]);
    }

    /**
     * @test
     */
    public function updateReturns404OnCategoryNotFound()
    {
        $response = $this->json(
            'PUT',
            '/api/categories/not-found',
            [
            'name' => 'dummy name',
            ],
            [
            'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $response
            ->assertResponseStatus(404);
    }

    /**
     * @test
     */
    public function updateReturns422OnAlreadyExistingCategoryName()
    {
        /**
         * @var Category $category
         */
        $category = factory(Category::class)->create();
        $this->json(
            'PUT',
            '/api/categories/' . $category->slug,
            [
            'name' => $category->name,
            ],
            [
            'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $this->assertResponseStatus(422);
    }

    /**
     * @test
     */
    public function update()
    {
        /**
         * @var Category $category
         */
        $category = factory(Category::class)->create();
        $this->json(
            'PUT',
            '/api/categories/' . $category->slug,
            [
            'name' => 'updated',
            ],
            [
            'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $this->assertResponseStatus(201);
        $this->seeJsonContains([
            'name' => 'updated',
            'description' => $category->description,
            'slug' => $category->slug,
        ]);
    }

    /**
     * @test
     */
    public function deleteReturn404OnNotFoundCategory()
    {
        $response = $this->json(
            'GET',
            '/api/categories/not-found',
            [],
            [
            'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $response
            ->assertResponseStatus(404);
    }

    /**
     * @test
     */
    public function deleteCategory()
    {
        /**
         * @var Category $category
         */
        $category = factory(Category::class)->create();
        $this->json(
            'DELETE',
            '/api/categories/' . $category->slug,
            [],
            [
            'Authorization' => 'Bearer ' . $this->token,
            ]
        );


        $this->assertResponseStatus(201);
        $this->seeJsonContains([
            'data' => [],
            'status' => 'success',
        ]);

        $this->notSeeInDatabase('categories', [
            'id' => $category->id,
        ]);
    }

    /**
     * @test
     */
    public function expenses()
    {
        /**
         * @var Category $category
         */
        $category = factory(Category::class, 1)->create()->first();

        /**
         * @var Expense[] $expenses
         */
        $expenses = factory(Expense::class, 10)->create();

        $response = $this->json(
            'PUT',
            '/api/expenses/' . $expenses[0]->id,
            [
                'category' => [
                    'slug' => $category->slug,
                ]
            ],
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $response->assertResponseStatus(201);
        $this->seeInDatabase('expenses', [
            'id' => $expenses[0]->id,
            'category_slug' => $category->slug,
        ]);

        $response = $this->json(
            'GET',
            '/api/categories/' . $category->slug . '/expenses',
            [],
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $response->assertResponseOk();
        $response->seeJsonContains([
            'description' => $expenses[0]->description,
            'denomination' => (string)$expenses[0]->denomination,
            'slug' => $category->slug,
        ]);
    }
}
