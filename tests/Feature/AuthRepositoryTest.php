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
        $user = $authRepo->register([
            'name' => 'Adib',
            'email' => 'adib@example.com',
            'password' => '123',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Adib', $user->name);
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

        $this->assertIsArray($response);
        $this->assertArrayHasKey('token', $response);
        $this->assertArrayHasKey('user', $response);
        $this->assertInstanceOf(User::class, $response['user']);
        $this->assertEquals('Adib', $response['user']->name);
    }

    public function test_logout()
    {
        $authRepo = new AuthRepository();

        $user = $authRepo->register([
            'name' => 'Adib',
            'email' => 'adib@example.com',
            'password' => '123',
        ]);

        $token = $user->createToken('test-token');
        $user->withAccessToken($token->accessToken);
        $this->assertTrue($authRepo->logout($user));
    }
}
