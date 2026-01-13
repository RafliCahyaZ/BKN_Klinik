<?php

namespace App\Repository;

use App\DTO\UserDTO;

interface UserRepository
{
    public function getAll(): array;

    public function getById(string $id): ?UserDTO;

    public function getByEmail(string $email): ?UserDTO;

    public function insert(UserDTO $userDTO): ?UserDTO;

    public function edit(string $id, UserDTO $userDTO): ?UserDTO;

    public function delete(string $id): bool;

    public function getLatestId(): int;
}
