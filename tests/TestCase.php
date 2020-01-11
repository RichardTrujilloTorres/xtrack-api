<?php

namespace Tests;

use App\User;

abstract class TestCase extends \Laravel\Lumen\Testing\TestCase
{
    /**
     * @var string
     */
    protected $token;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }


    public function login()
    {
        User::create([
            'email' => 'test@test.com',
            'password' => app()->make('hash')->make('secret'),
        ]);

        $response = $this->call('POST', '/auth/login', [
            'email' => 'test@test.com',
            'password' => 'secret',
        ]);

        $this->token = json_decode($response->getContent())->access_token;
    }
}
