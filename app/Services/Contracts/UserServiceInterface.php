<?php

namespace App\Services\Contracts;

interface UserServiceInterface
{
    public function list($filters = []);

    public function create($data);

    public function find($id);

    public function update($id, $data);

    public function delete($id);
}
