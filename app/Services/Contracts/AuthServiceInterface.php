<?php

namespace App\Services\Contracts;

interface AuthServiceInterface
{
    public function register(array $data);

    public function login(array $credentials): array;

    public function logout($user);
}
