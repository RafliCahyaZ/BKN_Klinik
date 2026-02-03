<?php

namespace App\Repository;

use App\DTO\UserDTO;
use App\Helper\UserRoleHelper;
use App\Models\User;
use App\Type\UserRole;
use Carbon\Carbon;

class UserRepositoryEloquent implements UserRepository
{
    public function getAll(): array
    {
        $usersCollections = User::all();

        $formatedUsers = $usersCollections->map(function ($user) {
            return $this->mapUserModelToUserDto($user);
        });

        $users = $formatedUsers->toArray();
        return $users;
    }

    public function getByUsername(string $username): ?UserDTO
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return null;
        }

        return $this->mapUserModelToUserDto($user);
    }

    public function getByRole(UserRole $role): array
    {
        $usersCollections = User::where('role', $role->value)->get();

        $formatedUsers = $usersCollections->map(function ($user) {
            return $this->mapUserModelToUserDto($user);
        });

        $users = $formatedUsers->toArray();
        return $users;
    }

    public function getFailedLoginAttempsById(string $id): int
    {
        $user = User::find($id);
        if(!$user) {
            return 0;
        }
        return $user ? $user->failed_login_attempts : 0;
    }

    public function insert(UserDTO $userDTO): ?UserDTO
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

    public function edit(string $id, UserDTO $userDTO): ?UserDTO
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

    public function updateLastLoginAt(string $id, Carbon $lastLoginAt): bool
    {
        $user = User::find($id);

        if (!$user) {
            return false;
        }

        $user->last_login_at = $lastLoginAt;
        $user->save();

        return true;
    }

    public function activate(string $id): ?UserDTO
    {
        $user = User::find($id);

        if (!$user) {
            return null;
        }

        $user->is_active = true;
        $user->failed_login_attempts = 0;
        $user->save();

        return $this->mapUserModelToUserDto($user);
    }

    function deactivate(string $id): ?UserDTO
    {
        $user = User::find($id);

        if (!$user) {
            return null;
        }

        $user->is_active = false;
        $user->save();

        return $this->mapUserModelToUserDto($user);
    }

    function resetFailedLoginAttempts(string $id): bool
    {
        $user = User::find($id);

        if (!$user) {
            return false;
        }

        $user->failed_login_attempts = 0;
        $user->save();

        return true;
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

    function updateFailedLoginAttemptsById(string $id, int $failedLoginAttempts): bool
    {
        $user = User::find($id);

        if (!$user) {
            return false;
        }

        $user->failed_login_attempts = $failedLoginAttempts;
        $user->save();

        return true;
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
        $formatedUser->isActive = $user->is_active;
        $formatedUser->failedLoginAttempts = $user->failed_login_attempts;
        $formatedUser->lastLoginAt = $user->last_login_at ? Carbon::parse($user->last_login_at) : null;
        $formatedUser->createdAt = $user->created_at;
        $formatedUser->updatedAt = $user->updated_at;

        return $formatedUser;
    }
}
