<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\BankingService;
use App\Http\Requests\DepositRequest;
use App\Http\Requests\TransferRequest;   

class BankingController extends Controller
{
    public function deposit(DepositRequest $request, string $id, BankingService $service)
    {
        $user = User::findOrFail($id);

        $updated = $service->deposit($user, $request->amount);

        return response()->json($updated);
    }

    public function transfer(TransferRequest $request, string $id, BankingService $service)
    {
        $from = User::findOrFail($id);
        $to   = User::findOrFail($request->user_to_id);

        $service->transfer($from, $to, $request->amount, $request->text);

        return response()->json(['status' => 'ok']);
    }
}
