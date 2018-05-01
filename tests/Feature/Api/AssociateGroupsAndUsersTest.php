<?php

namespace Tests\Feature\Api;

use App\User;
use App\Group;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssociateGroupsAndUsersTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminsCanAssociateUsersToGroups()
    {
        $admin = factory(User::class)->states(['admin'])->create();
        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();

        $response = $this->actingAs($admin, 'api')
            ->postJson('/api/groups/' . $group->id . '/users', [
                'user_id' => $user->id,
            ]);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertCount(1, $group->refresh()->users);
        $this->assertTrue($group->users->first()->is($user));
    }

    public function testFailsWhenUserAlreadyBelongsToGroup()
    {
        $admin = factory(User::class)->states(['admin'])->create();
        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();

        $group->users()->save($user);

        $response = $this->actingAs($admin, 'api')
            ->postJson('/api/groups/' . $group->id . '/users', [
                'user_id' => $user->id,
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'user_id',
            ],
        ]);

        $this->assertCount(1, $group->refresh()->users);
        $this->assertTrue($group->users->first()->is($user));
    }

    public function testOnlyAdminsCanAssociateUsersFromGroups()
    {
        $nonAdmin = factory(User::class)->create();
        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();

        $response = $this->actingAs($nonAdmin, 'api')
            ->postJson('/api/groups/' . $group->id . '/users', [
                'user_id' => $user->id,
            ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertCount(0, $group->refresh()->users);
    }

    public function testValidatesUserIdField()
    {
        $admin = factory(User::class)->states(['admin'])->create();
        $group = factory(Group::class)->create();

        $response = $this->actingAs($admin, 'api')
            ->postJson('/api/groups/' . $group->id . '/users', [
                'user_id' => 'something',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'user_id',
            ],
        ]);

        $this->assertCount(0, $group->refresh()->users);
    }

    public function testValidateUserIdIsRequired()
    {
        $admin = factory(User::class)->states(['admin'])->create();
        $group = factory(Group::class)->create();

        $response = $this->actingAs($admin, 'api')
            ->postJson('/api/groups/' . $group->id . '/users', []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'user_id',
            ],
        ]);

        $this->assertCount(0, $group->refresh()->users);
    }

    public function testValidateUserDoesNotExist()
    {
        $admin = factory(User::class)->states(['admin'])->create();
        $group = factory(Group::class)->create();

        $response = $this->actingAs($admin, 'api')
            ->postJson('/api/groups/' . $group->id . '/users', [
                'user_id' => 42,
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'user_id',
            ],
        ]);

        $this->assertCount(0, $group->refresh()->users);
    }
}
