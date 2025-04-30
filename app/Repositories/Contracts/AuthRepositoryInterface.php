<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function register(array $data);

    public function login(array $data);

    public function logout(User $user);
}
