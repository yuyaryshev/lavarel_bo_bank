<?php

class TransactionRepository
{
    public function create(string $accountId, string $type, float $amount, array $meta = [])
    {
        return Transaction::create([
            'account_id' => $accountId,
            'type' => $type,
            'amount' => $amount,
            'meta' => $meta
        ]);
    }
}
