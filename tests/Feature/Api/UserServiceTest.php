<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\UserService;
use Mockery;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    protected UserRepositoryInterface $userRepo;
    protected UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepo = Mockery::mock(UserRepositoryInterface::class);
        $this->userService = new UserService($this->userRepo);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_user()
    {
        $users = User::factory()->count(100)->make();

        $this->userRepo
            ->shouldReceive('list')
            ->once()
            ->with([])
            ->andReturn(collect($users));

        $response = $this->userService->list([]);

        $this->assertEquals(100, $response->count());
        $this->assertInstanceOf(User::class, $response->first());
    }

    public function test_create_user()
    {
        $data = [
            'name' => 'Adib',
            'email' => 'adib@example.com',
            'password' => '12345678',
        ];

        $this->userRepo
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($input) use ($data) {
                return $input['name'] === $data['name']
                    && $input['email'] === $data['email'];
            }))
            ->andReturn(new User([
                'name' => $data['name'],
                'email' => $data['email'],
            ]));

        $user = $this->userService->create($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('adib@example.com', $user->email);
    }

    public function test_get_user_by_id()
    {
        $user = new User([
            'name' => 'Adib',
            'email' => 'adib@example.com',
        ]);

        $this->userRepo
            ->shouldReceive('find')
            ->once()
            ->with($user->users_ulid)
            ->andReturn($user);

        $response = $this->userService->find($user->users_ulid);

        $this->assertInstanceOf(User::class, $response);
        $this->assertEquals('adib@example.com', $response->email);
    }

    public function test_delete_user()
    {
        $user = new User([
            'name' => 'Adib',
            'email' => 'adib@example.com',
            'users_ulid' => '01JT7EVG1E9584KSX12007MXQP',
        ]);

        $this->userRepo
            ->shouldReceive('delete')
            ->once()
            ->with($user->users_ulid)
            ->andReturn($user);

        $response = $this->userService->delete($user->users_ulid);

        $this->assertInstanceOf(User::class, $response);
        $this->assertEquals('adib@example.com', $response->email);
    }
}
