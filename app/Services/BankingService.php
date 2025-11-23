<?php

namespace App\Services;

use App\Models\User;
use App\Models\Transaction;
use App\Support\SystemUsers;
use Illuminate\Support\Facades\DB;

class BankingService
{
    public function deposit(User $user, float $amount): User
    {
        return DB::transaction(function () use ($user, $amount) {
            $systemUser = SystemUsers::ensureSystemUser('Deposit');

            $u = User::where('id', $user->id)->lockForUpdate()->first();
            $u->balance += $amount;
            $u->save();

            Transaction::create([
                'dt' => now()->toDateString(),
                'user_from_id' => $systemUser->id,
                'user_to_id' => $u->id,
                'text' => 'Deposit',
                'amount' => $amount,
                'operation_type' => Transaction::OPERATION_DEPOSIT,
            ]);

            return $u;
        });
    }

    public function transfer(User $from, User $to, float $amount, string $text): void
    {
        DB::transaction(function () use ($from, $to, $amount, $text) {

            $fromLocked = User::where('id', $from->id)->lockForUpdate()->first();
            $toLocked = User::where('id', $to->id)->lockForUpdate()->first();

            if ($fromLocked->balance < $amount) {
                throw new \Exception('Insufficient funds');
            }

            $fromLocked->balance -= $amount;
            $toLocked->balance += $amount;

            $fromLocked->save();
            $toLocked->save();

            Transaction::create([
                'dt' => now()->toDateString(),
                'user_from_id' => $fromLocked->id,
                'user_to_id' => $toLocked->id,
                'text' => $text,
                'amount' => $amount,
                'operation_type' => Transaction::OPERATION_TRANSFER,
            ]);
        });
    }
}
