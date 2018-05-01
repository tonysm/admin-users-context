<?php

namespace App\Users;

use App\Users\Repositories\UsersRepository;
use App\Users\Repositories\GroupsRepository;
use App\Exceptions\CannotDeleteGroupException;

class UsersContext
{
    /**
     * @var UsersRepository
     */
    private $usersRepository;

    /**
     * @var GroupsRepository
     */
    private $groupsRepository;

    public function __construct(
        UsersRepository $usersRepository,
        GroupsRepository $groupsRepository
    ) {
        $this->usersRepository = $usersRepository;
        $this->groupsRepository = $groupsRepository;
    }

    public function createUser(string $name) : User
    {
        return $this->usersRepository->create($name);
    }

    public function deleteUser(User $user)
    {
        $this->usersRepository->delete($user);
    }

    public function createGroup(string $name) : Group
    {
        return $this->groupsRepository->create($name);
    }

    public function deleteGroup(Group $group)
    {
        if ($this->groupsRepository->hasUsers($group)) {
            throw new CannotDeleteGroupException($group);
        }

        $this->groupsRepository->delete($group);
    }
}