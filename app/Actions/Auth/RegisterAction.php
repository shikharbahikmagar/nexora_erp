<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterAction
{
    public function execute(
        string $name,
        string $email,
        string $password
    ): array {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $token = $user->createToken('nexora-api')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
