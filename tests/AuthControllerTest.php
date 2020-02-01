<?php

namespace Tests;

use App\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var User
     */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'email'    => 'test@test.com',
            'password' => app()->make('hash')->make('secret'),
        ]);
    }

    /**
     * @test
     */
    public function returnsUnauthorizedErrorOnInvalidLogin()
    {
        $this->call('POST', '/auth/login', [
            'email'    => $this->user->email,
            'password' => 'invalid-password',
        ]);

        $this->assertResponseStatus(401);
        $this->seeJson([
            'error' => 'Unauthorized',
        ]);
    }

    /**
     * @test
     */
    public function meReturnsUnauthorizedOnNonLoggedUser()
    {
        $this->call('POST', '/auth/me');

        $this->assertResponseStatus(401);
        $this->assertEquals($this->response->getContent(), 'Unauthorized.');
    }

    /**
     * @test
     */
    public function me()
    {
        $this->call('POST', '/auth/login', [
            'email'    => $this->user->email,
            'password' => 'secret',
        ]);

        $this->call('POST', '/auth/me');
        $this->assertResponseOk();
    }

    /**
     * @test
     */
    public function login()
    {
        $this->call('POST', '/auth/login', [
            'email'    => $this->user->email,
            'password' => 'secret',
        ]);

        $this->assertResponseOk();

        $this->seeJson([
            'token_type' => 'bearer',
        ]);
    }

    /**
     * @test
     */
    public function logout()
    {
        $this->call('POST', '/auth/login', [
            'email'    => $this->user->email,
            'password' => 'secret',
        ]);

        $this->assertResponseOk();

        $content = json_decode($this->response->getContent(), true);

        $this->call('POST', '/auth/logout', [], [
            [
                'Authorization' => 'Bearer '.$content['access_token'],
            ],
        ]);

        $this->assertResponseOk();
    }

    /**
     * @test
     */
    public function refresh()
    {
        $this->call('POST', '/auth/login', [
            'email'    => $this->user->email,
            'password' => 'secret',
        ]);

        $this->assertResponseOk();

        $content = json_decode($this->response->getContent(), true);
        $token = $content['access_token'];

        $this->call('POST', '/auth/refresh', [], [
            [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        $this->assertResponseOk();

        $content = json_decode($this->response->getContent(), true);
        $newToken = $content['access_token'];

        $this->assertFalse($token === $newToken);
    }
}
