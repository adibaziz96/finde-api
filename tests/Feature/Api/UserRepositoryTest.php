<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected UserRepository $userRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepo = new UserRepository();
    }

    public function test_get_user()
    {
        $users = User::factory(100)->create();
        $response = $this->userRepo->list([]);

        $this->assertEquals(100, $response->total());
        $this->assertInstanceOf(User::class, $response->first());
    }

    public function test_create_user()
    {
        $data = [
            'name' => 'Adib',
            'email' => 'adib@example.com',
            'password' => bcrypt('12345678'),
        ];

        $user = $this->userRepo->create($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Adib', $user->name);
        $this->assertEquals('adib@example.com', $user->email);
        $this->assertDatabaseHas('users', ['email' => 'adib@example.com']);
    }

    public function test_get_user_by_id()
    {
        $user = User::factory()->create();
        $response = $this->userRepo->find($user->id);

        $this->assertNotNull($response);
        $this->assertEquals($user->id, $response->id);
    }

    public function test_delete_user()
    {
        $user = User::factory()->create([
            'email' => 'adib@example.com',
        ]);
        $this->userRepo->delete($user->id);

        $this->assertDatabaseMissing('users', ['email' => 'adib@example.com']);
    }
}
