<?php

namespace App\Users\Repositories;

use App\Users\User;
use App\Users\Group;

class GroupsRepository
{
    /**
     * @param string $name
     *
     * @return Group
     */
    public function create(string $name): Group
    {
        return Group::create(['name' => $name]);
    }

    /**
     * @param Group $group
     *
     * @return bool
     */
    public function hasUsers(Group $group)
    {
        return $group->users()->count() > 0;
    }

    /**
     * @param Group $group
     *
     * @throws \Exception
     */
    public function delete(Group $group)
    {
        $group->delete();
    }

    /**
     * @param Group $group
     * @param User $user
     */
    public function addUser(Group $group, User $user)
    {
        $group->users()->save($user);
    }

    /**
     * @param Group $group
     * @param User $user
     */
    public function removeUser(Group $group, User $user)
    {
        $group->users()->detach($user);
    }
}