<?php

namespace Tests\Feature\Api;

use App\Users\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanCreateUsers()
    {
        $admin = factory(User::class)->states(['admin'])->create();

        $response = $this->actingAs($admin, 'api')
            ->postJson('/api/users', [
                'name' => 'John Doe',
            ]);

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'name' => 'John Doe',
            ],
        ]);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
            ],
        ]);

        $this->assertCount(1, User::where('name', 'John Doe')->get());
    }

    public function testCannotCreateUsersWithoutName()
    {
        $admin = factory(User::class)->states(['admin'])->create();

        $response = $this->actingAs($admin, 'api')
            ->postJson('/api/users', []);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ],
        ]);

        $this->assertCount(0, User::where('is_admin', false)->get());
    }

    public function testNamesAreStrings()
    {
        $admin = factory(User::class)->states(['admin'])->create();

        $response = $this->actingAs($admin, 'api')
            ->postJson('/api/users', [
                'name' => 123,
            ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ],
        ]);

        $this->assertCount(0, User::where('is_admin', false)->get());
    }

    public function testNamesLength()
    {
        $admin = factory(User::class)->states(['admin'])->create();

        $response = $this->actingAs($admin, 'api')
            ->postJson('/api/users', [
                'name' => str_random(256),
            ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ],
        ]);

        $this->assertCount(0, User::where('is_admin', false)->get());
    }

    public function testOnlyAdminsCanCreateUsers()
    {
        $nonAdmin = factory(User::class)->create();

        $response = $this->actingAs($nonAdmin, 'api')
            ->postJson('/api/users', [
                'name' => 'John Doe',
            ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertCount(0, User::where('name', 'John Doe')->get());
    }

    public function testDeletesUsers()
    {
        $admin = factory(User::class)->states(['admin'])->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($admin, 'api')
            ->deleteJson('/api/users/' . $user->id);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertNull($user->fresh());
    }

    public function testOnlyAdminsCanDeleteUsers()
    {
        $nonAdmin = factory(User::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($nonAdmin, 'api')
            ->deleteJson('/api/users/' . $user->id);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $this->assertNotNull($user->fresh());
    }

    public function testAdminsCanListUsers()
    {
        $admin = factory(User::class)->states(['admin'])->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($admin, 'api')
            ->getJson('/api/users');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    'id' => $admin->id,
                ],
                [
                    'id' => $user->id,
                ],
            ],
        ]);
    }

    public function testOnlyAdminsCanListUsers()
    {
        $nonAdmin = factory(User::class)->create();

        $response = $this->actingAs($nonAdmin, 'api')
            ->getJson('/api/users');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
