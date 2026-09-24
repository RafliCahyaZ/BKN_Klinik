<?php

namespace App\Repository;

use App\DTO\LoginLogDTO;

interface LoginLogRepository
{
    public function getAll(): array;

    public function getById(int $id): ?LoginLogDTO;

    public function getByUserId(string $userId): array;

    public function getSuccessfulLogins(): array;

    public function getFailedLogins(): array;

    public function getFailedLoginsByEmail(string $email, int $minutes = 15): array;

    public function getRecentLoginsByUserId(string $userId, int $limit = 10): array;

    public function insert(LoginLogDTO $loginLogDTO): ?LoginLogDTO;

    public function delete(int $id): bool;

    public function getLatestId(): int;
}
