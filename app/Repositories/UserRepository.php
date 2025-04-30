<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function list(array $filters = [])
    {
        $user = User::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $user->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
            });
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';
        $perPage = $filters['per_page'] ?? 15;
        $currentPage = $filters['page'] ?? null;

        return $user->orderBy($sortBy, $sortDir)->paginate($perPage, ['*'], 'page', $currentPage);
    }

    public function create(array $data)
    {
        return User::firstOrCreate($data);
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $user = User::findOrFail($id)->update($data);

        return $user;
    }

    public function delete($id)
    {
        return User::destroy($id);
    }
}
