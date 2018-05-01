<?php

namespace App\Users;

use App\Users\Repositories\UsersRepository;
use App\Users\Repositories\GroupsRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Exceptions\CannotDeleteGroupException;
use Illuminate\Contracts\Pagination\Paginator;

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

    /**
     * @param User $user
     *
     * @throws \Exception
     */
    public function deleteUser(User $user)
    {
        $this->usersRepository->delete($user);
    }

    public function createGroup(string $name) : Group
    {
        return $this->groupsRepository->create($name);
    }

    /**
     * @param Group $group
     *
     * @throws \Exception
     */
    public function deleteGroup(Group $group)
    {
        if ($this->groupsRepository->hasUsers($group)) {
            throw new CannotDeleteGroupException($group);
        }

        $this->groupsRepository->delete($group);
    }

    public function addUserToGroup(int $userId, Group $group)
    {
        $user = $this->usersRepository->findOrFail($userId);

        $this->groupsRepository->addUser($group, $user);
    }

    public function removeUserFromGroup(User $user, Group $group)
    {
        $this->groupsRepository->removeUser($group, $user);
    }

    public function listUsersWithGroupsPaginated() : Paginator
    {
        return $this->usersRepository->listWithGroupsPaginated();
    }

    public function listAllGroups() : Collection
    {
        return $this->groupsRepository->listAll();
    }
}