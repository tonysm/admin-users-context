<?php

namespace Tests\Feature\Api;

use App\User;
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
}
