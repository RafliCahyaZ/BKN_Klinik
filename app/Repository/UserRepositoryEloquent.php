<?php

namespace App\Repository;

use App\DTO\UserDTO;
use App\Models\User;

class UserRepositoryEloquent implements UserRepository
{
    function getAll(): array
    {
        $usersCollections = User::all();

        $formatedUsers = $usersCollections->map(function ($user) {
            $formatedUser = new UserDTO();
            $formatedUser->id = $user->id;
            $formatedUser->username = $user->username;
            $formatedUser->fullname = $user->fullname;
            $formatedUser->email = $user->email;
            $formatedUser->password = $user->password;
            $formatedUser->role = $user->role;
            $formatedUser->createdAt = $user->created_at;
            $formatedUser->updatedAt = $user->updated_at;
            return $formatedUser;
        });

        $users = $formatedUsers->toArray();
        return $users;
    }

    function getByRole(string $role): array
    {
        $usersCollections = User::where('role', $role)->get();

        $formatedUsers = $usersCollections->map(function ($user) {
            $formatedUser = new UserDTO();
            $formatedUser->id = $user->id;
            $formatedUser->username = $user->username;
            $formatedUser->fullname = $user->fullname;
            $formatedUser->email = $user->email;
            $formatedUser->password = $user->password;
            $formatedUser->role = $user->role;
            $formatedUser->createdAt = $user->created_at;
            $formatedUser->updatedAt = $user->updated_at;
            return $formatedUser;
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

        $formatedUser = new UserDTO();
        $formatedUser->id = $user->id;
        $formatedUser->username = $user->username;
        $formatedUser->fullname = $user->fullname;
        $formatedUser->email = $user->email;
        $formatedUser->password = $user->password;
        $formatedUser->role = $user->role;
        $formatedUser->createdAt = $user->created_at;
        $formatedUser->updatedAt = $user->updated_at;
        return $formatedUser;
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
        $user->role = $userDTO->role;;
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

                $formatedUpdatedData = new UserDTO();
                $formatedUpdatedData->id = $updatedData->id;
                $formatedUpdatedData->username = $updatedData->username;
                $formatedUpdatedData->fullname = $updatedData->fullname;
                $formatedUpdatedData->email = $updatedData->email;
                $formatedUpdatedData->password = $updatedData->password;
                $formatedUpdatedData->role = $updatedData->role;
                $formatedUpdatedData->createdAt = $updatedData->created_at;
                $formatedUpdatedData->updatedAt = $updatedData->updated_at;

                return $formatedUpdatedData;
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

        $formatedUser = new UserDTO();
        $formatedUser->id = $user->id;
        $formatedUser->fullname = $user->fullname;
        $formatedUser->username = $user->username;
        $formatedUser->password = $user->password;
        $formatedUser->email = $user->email;
        $formatedUser->role = $user->role;
        $formatedUser->createdAt = $user->created_at;
        $formatedUser->updatedAt = $user->updated_at;
        return $formatedUser;
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
}
?>
