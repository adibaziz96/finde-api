<?php

namespace App\Services;

use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Services\Contracts\AuthServiceInterface;

class AuthService implements AuthServiceInterface
{
    protected $authRepo;

    public function __construct(AuthRepositoryInterface $authRepo)
    {
        $this->authRepo = $authRepo;
    }

    public function register($data)
    {
        return $this->authRepo->register($data);
    }

    public function login($credentials)
    {
        return $this->authRepo->login($credentials);
    }

    public function logout($user)
    {
        return $this->authRepo->logout($user);
    }
}
