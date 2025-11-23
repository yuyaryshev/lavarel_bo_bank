<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_retrieved_by_id()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'birth_date' => '1995-05-20',
            'balance' => 100.00,
        ]);

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $user->id,
                     'name' => 'John Doe',
                     'email' => 'john@example.com',
                     'birth_date' => '1995-05-20',
                 ])
                 ->assertJsonPath('balance', '100.00');
    }

    public function test_not_found_when_user_is_missing()
    {
        $this->getJson('/api/users/non-existent-id')
             ->assertStatus(404);
    }
}
