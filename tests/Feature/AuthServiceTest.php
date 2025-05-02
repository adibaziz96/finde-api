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
    protected AuthRepositoryInterface $authRepo;
    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authRepo = Mockery::mock(AuthRepositoryInterface::class);
        $this->authService = new AuthService($this->authRepo);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_register()
    {
        $this->authRepo->shouldReceive('register')
            ->once()
            ->with([
                'name' => 'Adib',
                'email' => 'adib@example.com',
                'password' => '12345678',
            ])
            ->andReturn(new User([
                'name' => 'Adib',
                'email' => 'adib@example.com',
                'password' => Hash::make('12345678'),
            ]));

        $response = $this->authService->register([
            'name' => 'Adib',
            'email' => 'adib@example.com',
            'password' => '12345678',
        ]);

        $this->assertInstanceOf(User::class, $response);
    }

    public function test_login()
    {
        $mockUser = new User([
            'name' => 'Adib',
            'email' => 'adib@example.com',
            'password' => Hash::make('12345678'),
        ]);

        $this->authRepo->shouldReceive('login')
            ->once()
            ->with([
                'email' => 'adib@example.com',
                'password' => '12345678',
            ])
            ->andReturn([
                'token' => 'mock-token-123',
                'user' => $mockUser,
            ]);

        $response = $this->authService->login([
            'email' => 'adib@example.com',
            'password' => '12345678',
        ]);

        $this->assertArrayHasKey('user', $response);
        $this->assertArrayHasKey('token', $response);
    }

    public function test_logout()
    {
        $mockUser = new User([
            'name' => 'Adib',
            'email' => 'adib@example.com',
        ]);

        $this->authRepo->shouldReceive('logout')
            ->once()
            ->with($mockUser)
            ->andReturn(true);

        $this->assertTrue($this->authService->logout($mockUser));
    }
}
