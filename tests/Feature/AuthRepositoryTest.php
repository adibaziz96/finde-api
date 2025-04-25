<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Repositories\AuthRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user()
    {
        $authRepo = new AuthRepository();

        $user = $authRepo->createUser([
            'name' => 'Adib',
            'email' => 'adib@example.com',
            'password' => '123',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Adib', $user->name);
    }

    public function test_validate_password()
    {
        $user = new User([
            'email' => 'adib@example.com',
            'password' => Hash::make('123')
        ]);

        $authRepo = new AuthRepository();

        $this->assertTrue($authRepo->validatePassword($user, '123'));
        $this->assertFalse($authRepo->validatePassword($user, 'wrongpassword'));
    }
}

