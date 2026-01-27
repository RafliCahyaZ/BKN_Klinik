<?php

namespace App\Repository;

use App\DTO\UserDTO;
use App\Helper\UserRoleHelper;
use App\Models\User;
use App\Type\UserRole;

class UserRepositoryEloquent implements UserRepository
{
    function getAll(): array
    {
        $usersCollections = User::all();

        $formatedUsers = $usersCollections->map(function ($user) {
            return $this->mapUserModelToUserDto($user);
        });

        $users = $formatedUsers->toArray();
        return $users;
    }

    function getByRole(string $role): array
    {
        $usersCollections = User::where('role', $role)->get();

        $formatedUsers = $usersCollections->map(function ($user) {
            return $this->mapUserModelToUserDto($user);
        });

        $users = $formatedUsers->toArray();
        return $users;
    }

    function getByEmail(string $email): ?UserDTO
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return null;
        }

        return $this->mapUserModelToUserDto($user);
    }

    function insert(UserDTO $userDTO): ?UserDTO
    {
        $user = new User();
        $user->fill(
            [
                'username' => $userDTO->username,
                'password' => $userDTO->password,
                'fullname' => $userDTO->fullname,
                'email' => $userDTO->email,
            ]
        );
        $user->role = $userDTO->role;
        $user->save();
        $userDTO->id = $user->id;
        $userDTO->createdAt = $user->created_at;
        $userDTO->updatedAt = $user->updated_at;

        return $userDTO;
    }

    function edit(string $id, UserDTO $userDTO): ?UserDTO
    {
        $updateData = [
            'fullname' => $userDTO->fullname ?? null,
            'password' => $userDTO->password ?? null,
            'role' => $userDTO->role ?? null,
        ];
        $filteredUpdateData = array_filter($updateData);
        $userId = $id;

        if (!empty($filteredUpdateData)) {
            $edit = User::where('id', $userId)
                ->update($filteredUpdateData);

            if ($edit > 0) {
                $updatedData = User::find($id);

                return $this->mapUserModelToUserDto($updatedData);
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    function getById(string $id): ?UserDTO
    {
        $user = User::find($id);

        if (!$user) {
            return null;
        }

        return $this->mapUserModelToUserDto($user);
    }

    function getLatestId(): int
    {
        $latestId = User::max('id');
        if (!$latestId) {
            return 0;
        }
        return $latestId;
    }

    function delete(string $id): bool
    {
        $user = User::find($id);

        if ($user) {
            $result = $user->delete();
            return $result;
        }

        return false;
    }

    public static function mapUserModelToUserDto(User $user): UserDTO
    {
        $formatedUser = new UserDTO();
        $formatedUser->id = $user->id;
        $formatedUser->username = $user->username;
        $formatedUser->fullname = $user->fullname;
        $formatedUser->email = $user->email;
        $formatedUser->password = $user->password;
        $formatedUser->role = UserRoleHelper::stringToUserRole($user->role);
        $formatedUser->createdAt = $user->created_at;
        $formatedUser->updatedAt = $user->updated_at;

        return $formatedUser;
    }
}
