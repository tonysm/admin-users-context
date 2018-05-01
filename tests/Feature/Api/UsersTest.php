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
        $this->withoutExceptionHandling();
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

        $this->assertCount(1, User::where('name', 'John Doe')->get());
    }

    public function xtestValidationFailsWhenSendingWrongDataToCreateUsers()
    {
        
    }

    public function xtestCannotDuplicateUsersEmails()
    {

    }
}
