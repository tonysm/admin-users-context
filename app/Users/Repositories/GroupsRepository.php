<?php

namespace App\Users\Repositories;

use App\Users\User;
use App\Users\Group;

class GroupsRepository
{
    public function create(string $name): Group
    {
        return Group::create(['name' => $name]);
    }

    public function hasUsers(Group $group)
    {
        return $group->users()->count() > 0;
    }

    public function delete(Group $group)
    {
        $group->delete();
    }

    public function addUser(Group $group, User $user)
    {
        $group->users()->save($user);
    }

    public function removeUser(Group $group, User $user)
    {
        $group->users()->detach($user);
    }
}