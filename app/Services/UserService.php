<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;

class UserService implements UserServiceInterface
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function list($filters = [])
    {
        return $this->userRepo->list($filters);
    }

    public function create($data)
    {
        return $this->userRepo->create($data);
    }

    public function find($id)
    {
        return $this->userRepo->find($id);
    }

    public function update($id, $data)
    {
        return $this->userRepo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->userRepo->delete($id);
    }
}
