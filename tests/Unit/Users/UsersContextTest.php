<?php

namespace Tests\Unit\Users;

use Mockery;
use App\Users\User;
use Tests\TestCase;
use App\Users\Group;
use App\Users\UsersContext;
use App\Users\Repositories\UsersRepository;
use App\Users\Repositories\GroupsRepository;

class UsersContextTest extends TestCase
{
    /**
     * @var UsersRepository
     */
    private $usersRepo;

    /**
     * @var GroupsRepository
     */
    private $groupsRepo;

    /**
     * @var UsersContext
     */
    private $context;

    public function setUp()
    {
        parent::setUp();
        $this->usersRepo = Mockery::mock(UsersRepository::class);
        $this->groupsRepo = Mockery::mock(GroupsRepository::class);

        $this->context = new UsersContext($this->usersRepo, $this->groupsRepo);
    }

    public function testCreateUser()
    {
        $this->usersRepo->shouldReceive('create')
            ->with('John Doe')
            ->andReturn(new User());

        $user = $this->context->createUser('John Doe');

        $this->assertInstanceOf(User::class, $user);
    }

    public function testDeletesUsers()
    {
        $model = new User();

        $this->usersRepo->shouldReceive('delete')
            ->with($model)
            ->once();

        $this->context->deleteUser($model);
    }

    public function testCreatesGroups()
    {
        $this->groupsRepo->shouldReceive('create')
            ->with('Fake Group')
            ->andReturn(new Group());

        $group = $this->context->createGroup('Fake Group');

        $this->assertInstanceOf(Group::class, $group);
    }

    /**
     * @expectedException \App\Exceptions\CannotDeleteGroupException
     */
    public function testFailsToDeleteGroupsWhenThereAreUsersAssociated()
    {
        $group = new Group();

        $this->groupsRepo->shouldReceive('hasUsers')
            ->with($group)
            ->andReturn(true);

        $this->context->deleteGroup($group);
    }

    public function testCanDeleteGroup()
    {
        $group = new Group();

        $this->groupsRepo->shouldReceive('hasUsers')
            ->with($group)
            ->andReturn(false);

        $this->groupsRepo->shouldReceive('delete')
            ->with($group)
            ->once();

        $this->context->deleteGroup($group);
    }
}
