<?php

namespace App\Repositories\Contracts;

interface AuthRepositoryInterface
{
    public function register(array $data);

    public function login(array $credentials): array;

    public function logout($user);
}
