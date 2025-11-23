<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_updated()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'birth_date' => '1990-01-01'
        ]);

        $response = $this->putJson("/api/users/{$user->id}", [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'birth_date' => '2000-01-01'
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name' => 'New Name',
                     'email' => 'new@example.com',
                     'birth_date' => '2000-01-01'
                 ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'new@example.com',
            'birth_date' => '2000-01-01'
        ]);
    }
}
