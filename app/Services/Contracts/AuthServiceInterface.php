<?php

namespace App\Services\Contracts;

interface AuthServiceInterface
{
    public function register($data);

    public function login($credentials);

    public function logout($user);
}
