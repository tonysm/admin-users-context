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
}
