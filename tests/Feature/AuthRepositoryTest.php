<?php

namespace Tests\Feature;

use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_register()
    {
        $authRepo = new AuthRepository();
        $response = $authRepo->register([
            'name' => 'Adib',
            'email' => 'adib@example.com',
            'password' => '123',
        ]);

        $this->assertInstanceOf(User::class, $response);
    }

    public function test_login()
    {
        $authRepo = new AuthRepository();

        $authRepo->register([
            'name' => 'Adib',
            'email' => 'adib@example.com',
            'password' => '123',
        ]);

        $response = $authRepo->login([
            'email' => 'adib@example.com',
            'password' => '123',
        ]);

        $this->assertArrayHasKey('user', $response);
        $this->assertArrayHasKey('token', $response);
    }

    public function test_logout()
    {
        $authRepo = new AuthRepository();

        $response = $authRepo->register([
            'name' => 'Adib',
            'email' => 'adib@example.com',
            'password' => '123',
        ]);

        $token = $response->createToken('test-token');
        $response->withAccessToken($token->accessToken);

        $this->assertTrue($authRepo->logout($response));
    }
}
