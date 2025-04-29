<?php

namespace Tests\Feature;

use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Services\AuthService;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    public function test_register()
    {
        $authRepo = Mockery::mock(AuthRepositoryInterface::class);
        $this->app->instance(AuthRepositoryInterface::class, $authRepo);

        $authRepo->shouldReceive('register')
            ->once()
            ->with([
                'name' => 'Adib',
                'email' => 'adib@example.com',
                'password' => '123',
            ])
            ->andReturn(new User([
                'name' => 'Adib',
                'email' => 'adib@example.com',
                'password' => Hash::make('123'),
            ]));

        $authService = new AuthService($authRepo);
        $user = $authService->register([
            'name' => 'Adib',
            'email' => 'adib@example.com',
            'password' => '123',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Adib', $user->name);
    }

    public function test_login()
    {
        $authRepo = Mockery::mock(AuthRepositoryInterface::class);
        $this->app->instance(AuthRepositoryInterface::class, $authRepo);

        $mockUser = new User([
            'name' => 'Adib',
            'email' => 'adib@example.com',
            'password' => Hash::make('123'),
        ]);

        $authRepo->shouldReceive('login')
            ->once()
            ->with([
                'email' => 'adib@example.com',
                'password' => '123',
            ])
            ->andReturn([
                'token' => 'mock-token-123',
                'user' => $mockUser,
            ]);

        $authService = new AuthService($authRepo);
        $result = $authService->login([
            'email' => 'adib@example.com',
            'password' => '123',
        ]);

        $this->assertArrayHasKey('token', $result);
        $this->assertEquals('Adib', $result['user']->name);
    }

    public function test_logout()
    {
        $authRepo = Mockery::mock(AuthRepositoryInterface::class);
        $this->app->instance(AuthRepositoryInterface::class, $authRepo);

        $mockUser = new User([
            'name' => 'Adib',
            'email' => 'adib@example.com',
        ]);

        $authRepo->shouldReceive('logout')
            ->once()
            ->with($mockUser)
            ->andReturn(true);

        $authService = new AuthService($authRepo);
        $this->assertTrue($authService->logout($mockUser));
    }
}
