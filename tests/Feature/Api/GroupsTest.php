<?php

namespace Tests\Feature\Api;

use App\Users\User;
use App\Users\Group;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GroupsTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminsCanCreateGroups()
    {
        $admin = factory(User::class)->states(['admin'])->create();

        $response = $this->actingAs($admin, 'api')
            ->postJson('/api/groups', [
                'name' => 'Test Group',
            ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertCount(1, Group::all());
    }

    public function testValidateGroupNameIsRequired()
    {
        $admin = factory(User::class)->states(['admin'])->create();

        $response = $this->actingAs($admin, 'api')
            ->postJson('/api/groups', []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ],
        ]);
    }

    public function testValidateGroupNameIsString()
    {
        $admin = factory(User::class)->states(['admin'])->create();

        $response = $this->actingAs($admin, 'api')
            ->postJson('/api/groups', [
                'name' => 123,
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ],
        ]);
    }

    public function testValidateGroupNameLength()
    {
        $admin = factory(User::class)->states(['admin'])->create();

        $response = $this->actingAs($admin, 'api')
            ->postJson('/api/groups', [
                'name' => str_random(256),
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ],
        ]);
    }

    public function testOnlyAdminsCanCreateGroups()
    {
        $nonAdmin = factory(User::class)->create();

        $response = $this->actingAs($nonAdmin, 'api')
            ->postJson('/api/groups', [
                'name' => 'Test Group',
            ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertCount(0, Group::all());
    }

    public function testAdminsCannotDeleteGroupsWhenTheyHaveUsersAssociated()
    {
        $admin = factory(User::class)->states(['admin'])->create();
        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();

        $group->users()->save($user);

        $response = $this->actingAs($admin, 'api')
            ->deleteJson('/api/groups/' . $group->id);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
        ]);
    }

    public function testAdminsCanRemoveGroupsWhenNoUsersAreAssociated()
    {
        $admin = factory(User::class)->states(['admin'])->create();
        $group = factory(Group::class)->create();

        $response = $this->actingAs($admin, 'api')
            ->deleteJson('/api/groups/' . $group->id);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertNull($group->fresh());
    }

    public function testOnlyAdminsCanDeleteGroups()
    {
        $nonAdmin = factory(User::class)->create();
        $group = factory(Group::class)->create();

        $response = $this->actingAs($nonAdmin, 'api')
            ->deleteJson('/api/groups/' . $group->id);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $this->assertNotNull($group->fresh());
    }
}
