<?php

namespace Tests;

use App\Category;
use App\Expense;
use Carbon\Carbon;
use Laravel\Lumen\Testing\DatabaseMigrations;

/**
 * Class StatsControllerTest
 * @package Tests
 */
class StatsControllerTest extends TestCase
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
    public function byCategory()
    {
        /**
         * @var Category[] $categories
         */
        $categories = factory(Category::class, 3)->create();

        /**
         * @var Expense[] $expenses
         */
        $expenses = factory(Expense::class, 10)->create();

        $expenses[0]->category()->associate($categories[0]);
        $expenses[1]->category()->associate($categories[0]);
        $expenses[0]->save();
        $expenses[1]->save();
        $totalExpensesSample = $expenses[0]->denomination + $expenses[1]->denomination;

        $response = $this->json(
            'GET',
            '/api/stats/by-category',
            [],
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $response->assertResponseOk();
        $content = json_decode($response->response->getContent());
        foreach ($content->data as $datum) {
            if (isset($datum->category) &&
                $datum->category->slug === $categories[0]->slug) {
                $this->assertEquals($datum->total, $totalExpensesSample);
            }
        }
    }

    /**
     * @test
     */
    public function byMonth()
    {
        /**
         * @var Expense[] $expenses
         */
        $expenses = factory(Expense::class, 5)->create();

        $expenses[0]->created_at = $expenses[1]->created_at = Carbon::now()->setMonth(1);
        $expenses[0]->save();
        $expenses[1]->save();
        /**
         * @var int $totalJanuaryExpenses
         */
        $totalJanuaryExpenses = $expenses[0]->denomination + $expenses[1]->denomination;

        $expenses[2]->created_at = $expenses[3]->created_at = Carbon::now()->setMonth(2);
        $expenses[2]->save();
        $expenses[3]->save();

        /**
         * @var int $totalFebruaryExpenses
         */
        $totalFebruaryExpenses = $expenses[2]->denomination + $expenses[3]->denomination;

        $expenses[4]->created_at = Carbon::now()->setMonth(3);
        $expenses[4]->save();


        $response = $this->json(
            'GET',
            '/api/stats/by-month',
            [],
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $response->assertResponseOk();

        $content = json_decode($response->response->getContent());
        foreach ($content->data as $item) {
            switch ($item->month) {
                case 1:
                    $this->assertEquals($item->total, $totalJanuaryExpenses);
                    break;

                case 2:
                    $this->assertEquals($item->total, $totalFebruaryExpenses);
                    break;

                default:
                    $this->assertEquals($item->total, $expenses[4]->denomination);
                    break;
            }
        }
    }

    /**
     * @test
     */
    public function byDay()
    {
        /**
         * @var Expense[] $expenses
         */
        $expenses = factory(Expense::class, 3)->create();
        $total = $expenses[0]->denomination + $expenses[1]->denomination + $expenses[2]->denomination;

        /**
         * @var Expense[] $tomorrowExpenses
         */
        $tomorrowExpenses = factory(Expense::class, 3)->create();
        $tomorrowExpenses[0]->created_at =
            $tomorrowExpenses[1]->created_at =
                $tomorrowExpenses[2]->created_at = Carbon::now()->addDays(1);
        $tomorrowExpenses[0]->save();
        $tomorrowExpenses[1]->save();
        $tomorrowExpenses[2]->save();
        $tomorrowsTotal = $tomorrowExpenses[0]->denomination +
            $tomorrowExpenses[1]->denomination +
            $tomorrowExpenses[2]->denomination;

        $response = $this->json(
            'GET',
            '/api/stats/by-day',
            [],
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $response->assertResponseOk();

        $content = json_decode($response->response->getContent());

        $this->assertEquals($content->data[0]->total, $total);
        $this->assertEquals($content->data[0]->day, Carbon::now()->format('Y-m-d'));

        $this->assertEquals($content->data[1]->total, $tomorrowsTotal);
        $this->assertEquals($content->data[1]->day, Carbon::now()->addDay()->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function monthlySummary()
    {
        /**
         * @var Expense[] $expenses
         */
        $expenses = factory(Expense::class, 3)->create();

        /**
         * @var Category[] $categories
         */
        $categories = factory(Category::class, 2)->create();

        $expenses[0]->category()->associate($categories[0]);
        $expenses[1]->category()->associate($categories[0]);
        $expenses[2]->category()->associate($categories[1]);
        $expenses[0]->denomination = 299.99;
        $expenses[1]->denomination = 99.99;
        $expenses[2]->denomination = 11.11;
        $expenses[0]->save();
        $expenses[1]->save();
        $expenses[2]->save();

        $response = $this->json(
            'GET',
            '/api/stats/monthly-summary',
            [],
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $response->assertResponseOk();

        $content = json_decode($response->response->getContent());

        $this->assertEquals($content->data->highestExpense->category->slug, $categories[0]->slug);
        $this->assertEquals($content->data->highestCategory->category->slug, $categories[0]->slug);
        $this->assertEquals($content->data->highestExpense->denomination, 299.99);
        $this->assertEquals($content->data->highestCategory->total, 299.99 + 99.99);
    }
}
