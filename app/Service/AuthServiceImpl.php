<?php

namespace App\Service;

class AuthServiceImpl implements AuthService
{
    public function attempt(string $login, string $password): bool
    {
        return true;
    }
}
