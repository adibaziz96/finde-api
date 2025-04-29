<?php

namespace App\Repositories\Contracts;

interface AuthRepositoryInterface
{
    public function register($data);

    public function login($credentials);

    public function logout($user);
}
