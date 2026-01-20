<?php

namespace App\Service;

use Illuminate\Support\Facades\Auth;

class AuthServiceImpl implements AuthService
{
    public function attempt(string $username, string $password): bool
    {
        if (Auth::attempt([
            'username' => $username,
            'password' => $password,
        ])) {
            return true;
        }
        return false;
    }

    public function logout(): bool
    {
        Auth::guard('web')->logout();

        return true;
    }
}
