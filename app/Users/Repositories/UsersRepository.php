<?php

namespace App\Users\Repositories;

use App\Users\User;

class UsersRepository
{
    public function create(string $name) : User
    {
        return User::create(['name' => $name]);
    }

    public function delete(User $user)
    {
        $user->delete();
    }
}