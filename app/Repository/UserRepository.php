<?php

namespace App\Repository;

use App\DTO\UserDTO;
use App\Type\UserRole;
use Carbon\Carbon;

interface UserRepository
{
    public function getAll(): array;

    public function getById(string $id): ?UserDTO;

    public function getByUsername(string $username): ?UserDTO;

    public function getFailedLoginAttempsById(string $id): ?int;

    public function getByRole(UserRole $role): array;

    public function getLatestId(): int;
    
    public function insert(UserDTO $userDTO): ?UserDTO;
    
    public function edit(string $id, UserDTO $userDTO): ?UserDTO;

    public function updateLastLoginAt(string $id, Carbon $lastLoginAt): bool;
    
    public function activate(string $id): ?UserDTO;

    public function deactivate(string $id): ?UserDTO;

    public function resetFailedLoginAttempts(string $id): bool;

    public function updateFailedLoginAttemptsById(string $id, int $failedLoginAttempts): bool;
    
    public function delete(string $id): bool;
}
