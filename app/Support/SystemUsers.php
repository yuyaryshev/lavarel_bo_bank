<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Str;

class SystemUsers
{
    public const SYSTEM_USERS_IDS = [
        'Deposit' => '00000000-0000-0000-0000-000000000001',
    ];

    public static function nameFor(string $code): string
    {
        return 'System: ' . $code;
    }

    public static function emailFor(string $code): string
    {
        return 'system-' . Str::slug($code, '-') . '@example.com';
    }

    public static function ensureSystemUser(?string $code = null)
    {
        if ($code && !isset(static::SYSTEM_USERS_IDS[$code])) {
            throw new \InvalidArgumentException("Unknown system user code [{$code}]");
        }

        $targets = $code
            ? [$code => static::SYSTEM_USERS_IDS[$code]]
            : static::SYSTEM_USERS_IDS;

        $users = [];

        foreach ($targets as $userCode => $id) {
            $user = User::find($id);

            if (!$user) {
                $user = new User();
                $user->id = $id;
            }

            $user->forceFill([
                'name' => static::nameFor($userCode),
                'email' => static::emailFor($userCode),
                'is_system' => true,
                'balance' => $user->balance ?? 0,
            ]);

            if (!$user->balance) {
                $user->balance = 0;
            }

            $user->save();

            $users[$userCode] = $user;
        }

        return $code ? $users[$code] : $users;
    }
}
