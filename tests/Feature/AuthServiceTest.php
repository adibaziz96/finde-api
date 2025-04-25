<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Services\AuthService;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthServiceTest extends TestCase
{
    public function test_register_creates_user()
    {
        // Mock AuthRepositoryInterface
        $authRepo = Mockery::mock(AuthRepositoryInterface::class);
        
        // Mock the method calls on the repository
        $authRepo->shouldReceive('createUser')
            ->once()
            ->andReturn(new User([
                'name' => 'Adib',
                'email' => 'adib@example.com',
                'password' => Hash::make('123'),
            ]));

        // Bind the mock to the service container
        $this->app->instance(AuthRepositoryInterface::class, $authRepo);

        // Test the AuthService's register method
        $authService = new AuthService($authRepo);
        $user = $authService->register([
            'name' => 'Adib',
            'email' => 'adib@example.com',
            'password' => '123',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Adib', $user->name);
    }

    public function test_login_returns_token_for_valid_user()
    {
        // Prepare user data
        $user = new User([
            'email' => 'adib@example.com',
            'password' => Hash::make('123')
        ]);

        // Mock AuthRepositoryInterface
        $authRepo = Mockery::mock(AuthRepositoryInterface::class);
        
        $authRepo->shouldReceive('findByEmail')
            ->once()
            ->with('adib@example.com')
            ->andReturn($user);

        $authRepo->shouldReceive('validatePassword')
            ->once()
            ->with($user, '123')
            ->andReturn(true);

        $authRepo->shouldReceive('issueToken')
            ->once()
            ->with($user)
            ->andReturn('valid_token');

        // Bind the mock to the service container
        $this->app->instance(AuthRepositoryInterface::class, $authRepo);

        // Test the AuthService's login method
        $authService = new AuthService($authRepo);
        $result = $authService->login([
            'email' => 'adib@example.com',
            'password' => '123',
        ]);

        $this->assertArrayHasKey('token', $result);
        $this->assertEquals('valid_token', $result['token']);
    }
}
