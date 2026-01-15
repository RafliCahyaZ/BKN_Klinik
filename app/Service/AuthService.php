<?php

namespace App\Service;

interface AuthService
{
    public function attempt(string $login, string $password): bool;

    public function logout(): bool;
}
