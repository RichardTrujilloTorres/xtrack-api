<?php

namespace Tests;

use App\Category;
use App\Expense;
use Laravel\Lumen\Testing\DatabaseMigrations;

/**
 * Class ExpensesControllerTest.
 */
class ExpensesControllerTest extends TestCase
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
        factory(Category::class, 3)->create();
        /**
         * @var Expense[]
         */
        $expenses = factory(Expense::class, 10)->create();
        $response = $this->json(
            'GET',
            '/api/expenses',
            [],
            [
                'Authorization' => 'Bearer '.$this->token,
            ]
        );

        $response->assertResponseOk();

        $this->seeJsonContains([
            'description' => $expenses[0]->description,
        ]);
    }

    /**
     * @test
     */
    public function show()
    {
        /**
         * @var Expense[]
         */
        $expenses = factory(Expense::class, 10)->create();
        $response = $this->json(
            'GET',
            '/api/expenses/'.$expenses[0]->id,
            [],
            [
                'Authorization' => 'Bearer '.$this->token,
            ]
        );

        $response->assertResponseOk();

        $this->seeJsonContains([
            'denomination' => (string) $expenses[0]->denomination,
        ]);
    }

    /**
     * @test
     */
    public function returns404OnExpenseNotFound()
    {
        $response = $this->json(
            'GET',
            '/api/expenses/999999',
            [],
            [
                'Authorization' => 'Bearer '.$this->token,
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
        factory(Category::class, 3)->create();

        $response = $this->json(
            'POST',
            '/api/expenses',
            [],
            [
                'Authorization' => 'Bearer '.$this->token,
            ]
        );

        $response
            ->assertResponseStatus(422);
    }

    /**
     * @test
     */
    public function store()
    {
        /**
         * @var Category[]
         */
        $categories = factory(Category::class, 3)->create();

        $this->json('POST', '/api/expenses', [
            'denomination' => 13.33,
            'description'  => 'test expense',
            'category'     => $categories[0]->toArray(),
        ]);

        $this
            ->assertResponseStatus(201);

        $this->seeJsonContains([
            'denomination' => 13.33,
            'description'  => 'test expense',
        ]);

        $this->seeJsonContains([
            'slug' => $categories[0]->slug,
        ]);
    }

    /**
     * @test
     */
    public function updateReturns404OnExpenseNotFound()
    {
        $response = $this->json(
            'PUT',
            '/api/expenses/999999',
            [
                'description' => 'test expense --updated',
            ],
            [
                'Authorization' => 'Bearer '.$this->token,
            ]
        );

        $response
            ->assertResponseStatus(404);
    }

    /**
     * @test
     */
    public function update()
    {
        /**
         * @var Expense[]
         */
        $expenses = factory(Expense::class, 10)->create();

        $this->json(
            'PUT',
            '/api/expenses/'.$expenses[0]->id,
            [
                'description' => 'test expense --updated',
            ],
            [
                'Authorization' => 'Bearer '.$this->token,
            ]
        );

        $this->assertResponseStatus(201);

        $this->seeJsonContains([
            'description' => 'test expense --updated',
        ]);
    }

    /**
     * @test
     */
    public function updateWithCategory()
    {
        /**
         * @var Category[]
         */
        $categories = factory(Category::class, 3)->create();

        /**
         * @var Expense[]
         */
        $expenses = factory(Expense::class, 10)->create();

        $this->json(
            'PUT',
            '/api/expenses/'.$expenses[0]->id,
            [
                'description' => 'test expense --updated',
                'category'    => [
                    'slug' => $categories[0]->slug,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->token,
            ]
        );

        $this->assertResponseStatus(201);

        $this->seeJsonContains([
            'description' => 'test expense --updated',
        ]);

        $this->seeJsonContains([
            'slug' => $categories[0]->slug,
        ]);

        $this->assertFalse($expenses[0]->category == $categories[0]->slug);
    }

    /**
     * @test
     */
    public function deleteReturn404OnNotFoundExpense()
    {
        $response = $this->json(
            'GET',
            '/api/expenses/999999',
            [],
            [
                'Authorization' => 'Bearer '.$this->token,
            ]
        );

        $response
            ->assertResponseStatus(404);
    }

    /**
     * @test
     */
    public function deleteExpense()
    {
        /**
         * @var Expense[]
         */
        $expenses = factory(Expense::class, 10)->create();

        $this->json(
            'DELETE',
            '/api/expenses/'.$expenses[0]->id,
            [],
            [
                'Authorization' => 'Bearer '.$this->token,
            ]
        );

        $this->assertResponseStatus(201);
        $this->seeJsonContains([
            'data'   => [],
            'status' => 'success',
        ]);

        $this->notSeeInDatabase('expenses', [
            'id' => $expenses[0]->id,
        ]);
    }
}
