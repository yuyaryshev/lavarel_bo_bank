<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransferTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_transfer_between_users()
    {
        $from = User::factory()->create([
            'balance' => 200.00
        ]);

        $to = User::factory()->create([
            'balance' => 20.00
        ]);

        $response = $this->postJson("/api/users/{$from->id}/transfer", [
            'user_to_id' => $to->id,
            'amount' => 70.00,
            'text' => 'Payment'
        ]);

        $response->assertStatus(200);

        $from->refresh();
        $to->refresh();

        $this->assertEquals(130.00, (float)$from->balance);
        $this->assertEquals(90.00, (float)$to->balance);

        $this->assertDatabaseHas('transactions', [
            'user_from_id' => $from->id,
            'user_to_id' => $to->id,
            'amount' => 70.00,
            'text' => 'Payment',
            'operation_type' => Transaction::OPERATION_TRANSFER,
        ]);
    }

    public function test_transfer_fails_when_balance_is_not_enough()
    {
        $from = User::factory()->create([
            'balance' => 10.00
        ]);

        $to = User::factory()->create([
            'balance' => 0.00
        ]);

        $response = $this->postJson("/api/users/{$from->id}/transfer", [
            'user_to_id' => $to->id,
            'amount' => 100.00,
            'text' => 'Fail Test'
        ]);

        $response->assertStatus(500);

        $from->refresh();
        $to->refresh();

        $this->assertEquals(10.00, (float)$from->balance);
        $this->assertEquals(0.00, (float)$to->balance);

        $this->assertDatabaseMissing('transactions', [
            'text' => 'Fail Test'
        ]);
    }
}
