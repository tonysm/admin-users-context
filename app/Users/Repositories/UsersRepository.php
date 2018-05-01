<?php

namespace App\Users\Repositories;

use App\Users\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UsersRepository
{
    /**
     * @param string $name
     *
     * @return User
     */
    public function create(string $name) : User
    {
        return User::create(['name' => $name]);
    }

    /**
     * @param User $user
     *
     * @throws \Exception
     */
    public function delete(User $user)
    {
        $user->delete();
    }

    /**
     * @param int $userId
     *
     * @throws ModelNotFoundException
     * @return User
     */
    public function findOrFail(int $userId) : User
    {
        return User::findOrFail($userId);
    }

    public function listWithGroupsPaginated() : Paginator
    {
        return User::with('groups')->paginate();
    }
}