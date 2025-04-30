<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function list(array $filters = []);

    public function create(array $data);

    public function find($id);

    public function update($id, array $data);

    public function delete($id);
}
