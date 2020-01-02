<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->json('GET', '/');

        $response->assertResponseOk();
        $response->seeJsonContains([
            'data' => 'All good',
        ]);
    }
}
