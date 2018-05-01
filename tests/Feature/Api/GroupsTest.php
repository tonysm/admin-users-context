<?php

namespace Tests\Feature\Api;

use App\User;
use App\Group;
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
}
