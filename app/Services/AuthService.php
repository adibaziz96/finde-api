<?php

namespace App\Services;

use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Validation\ValidationException;

class AuthService implements AuthServiceInterface
{
    protected $authRepo;

    public function __construct(AuthRepositoryInterface $authRepo)
    {
        $this->authRepo = $authRepo;
    }

    public function register(array $data)
    {
        return $this->authRepo->register($data);
    }

    public function login(array $credentials)
    {
        return $this->authRepo->login($credentials);
    }

    public function logout($user)
    {
        return $this->authRepo->logout($user);
    }
}
