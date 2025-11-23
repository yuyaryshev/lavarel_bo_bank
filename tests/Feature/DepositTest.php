<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Support\SystemUsers;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DepositTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_balance_is_increased_by_deposit()
    {
        $user = User::factory()->create([
            'balance' => 100.00
        ]);

        $response = $this->postJson("/api/users/{$user->id}/deposit", [
            'amount' => 50.00
        ]);

        $response->assertStatus(200);

        $user->refresh();

        $this->assertEquals(150.00, (float)$user->balance);

        $this->assertDatabaseHas('transactions', [
            'user_from_id' => SystemUsers::SYSTEM_USERS_IDS['Deposit'],
            'user_to_id' => $user->id,
            'text' => 'Deposit',
            'amount' => 50.00,
            'operation_type' => Transaction::OPERATION_DEPOSIT,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => SystemUsers::SYSTEM_USERS_IDS['Deposit'],
            'name' => SystemUsers::nameFor('Deposit'),
            'is_system' => true,
        ]);
    }
}
