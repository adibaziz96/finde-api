<?php

namespace Tests\Feature;

use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected AuthRepository $authRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authRepo = new AuthRepository();
    }

    public function test_register()
    {
        $response = $this->authRepo->register([
            'name' => 'Adib',
            'email' => 'adib@example.com',
            'password' => '12345678',
        ]);

        $this->assertInstanceOf(User::class, $response);
        $this->assertDatabaseHas('users', ['email' => 'adib@example.com']);
    }

    public function test_login()
    {
        $this->authRepo->register([
            'name' => 'Adib',
            'email' => 'adib@example.com',
            'password' => '12345678',
        ]);

        $response = $this->authRepo->login([
            'email' => 'adib@example.com',
            'password' => '12345678',
        ]);

        $this->assertArrayHasKey('user', $response);
        $this->assertArrayHasKey('token', $response);
        $this->assertEquals($response['user']->email, 'adib@example.com');
    }

    public function test_logout()
    {
        $response = $this->authRepo->register([
            'name' => 'Adib',
            'email' => 'adib@example.com',
            'password' => '12345678',
        ]);

        $token = $response->createToken('test-token');
        $response->withAccessToken($token->accessToken);

        $this->assertTrue($this->authRepo->logout($response));
    }
}
