<?php

namespace App\Repository;

use App\DTO\UserDTO;
use App\Models\User;

class UserRepositoryEloquent implements UserRepository
{
    private function toDto(User $user): UserDTO
    {
        $dto = new UserDTO();
        $dto->id = (string) $user->id;
        $dto->username = $user->username;
        $dto->password = $user->password;
        $dto->fullname = $user->fullname;
        $dto->email = $user->email;
        $dto->role = $user->role;
        $dto->createdAt = $user->created_at;
        $dto->updatedAt = $user->updated_at;

        return $dto;
    }

    public function getAll(): array
    {
        return User::all()->map(fn (User $user) => $this->toDto($user))->toArray();
    }

    public function getById(string $id): ?UserDTO
    {
        $user = User::find($id);

        return $user ? $this->toDto($user) : null;
    }

    public function getByEmail(string $email): ?UserDTO
    {
        $user = User::where('email', $email)->first();

        return $user ? $this->toDto($user) : null;
    }

    public function insert(UserDTO $userDTO): ?UserDTO
    {
        $user = User::create([
            'username' => $userDTO->username,
            'password' => $userDTO->password,
            'fullname' => $userDTO->fullname,
            'email'    => $userDTO->email,
        ]);

        $user->role = $userDTO->role;
        $user->save();

        return $this->toDto($user);
    }

    public function edit(string $id, UserDTO $userDTO): ?UserDTO
    {
        $user = User::find($id);
        if (!$user) {
            return null;
        }

        $updateData = array_filter([
            'username' => $userDTO->username ?? null,
            'password' => $userDTO->password ?? null,
            'fullname' => $userDTO->fullname ?? null,
            'email'    => $userDTO->email ?? null,
        ], fn ($value) => !is_null($value));

        if (!empty($updateData)) {
            $user->fill($updateData);
        }
        if (!is_null($userDTO->role ?? null)) {
            $user->role = $userDTO->role;
        }

        $user->save();

        return $this->toDto($user);
    }

    public function delete(string $id): bool
    {
        $user = User::find($id);

        return $user ? (bool) $user->delete() : false;
    }

    public function getLatestId(): int
    {
        return User::max('id') ?? 0;
    }
}
