<?php

namespace Tests\Unit\Users;

use Mockery;
use App\Users\User;
use Tests\TestCase;
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
}
