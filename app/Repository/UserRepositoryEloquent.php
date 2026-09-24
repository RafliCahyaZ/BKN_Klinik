<?php

namespace App\Repository;

use App\DTO\UserDTO;
use App\Helper\UserRoleHelper;
use App\Models\User;
use App\Type\UserRole;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserRepositoryEloquent implements UserRepository
{
    public function getAll(): array
    {
        try {
            $usersCollections = User::with('roles')->get();

            $formatedUsers = $usersCollections->map(function ($user) {
                return $this->mapUserModelToUserDto($user);
            });

            $users = $formatedUsers->toArray();

            return $users;
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function getByUsername(string $username): ?UserDTO
    {
        try {
            $user = User::where('username', $username)->first();

            if (! $user) {
                return null;
            }

            return $this->mapUserModelToUserDto($user);
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function getByEmail(string $email): ?UserDTO
    {
        try {
            $user = User::where('email', $email)->first();

            return $user ? $this->mapUserModelToUserDto($user) : null;
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function getByRole(UserRole $role): array
    {
        try {
            $usersCollections = User::role($role->value)->get();

            $formatedUsers = $usersCollections->map(function ($user) {
                return $this->mapUserModelToUserDto($user);
            });

            $users = $formatedUsers->toArray();

            return $users;
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function getFailedLoginAttempsById(string $id): int
    {
        try {
            $user = User::find($id);
            if (! $user) {
                return 0;
            }

            return $user ? $user->failed_login_attempts : 0;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public function insert(UserDTO $userDTO): ?UserDTO
    {
        try {
            $user = new User;
            $user->fill(
                [
                    'username' => $userDTO->username,
                    'password' => $userDTO->password,
                    'fullname' => $userDTO->fullname,
                    'email' => $userDTO->email,
                ]
            );
            $user->role = $userDTO->role->value;

            $role = Role::findByName($userDTO->role->value);
            if (! $role) {
                return null;
            }
            $user->save();
            $user->assignRole($role);
            $userDTO->id = $user->id;
            $userDTO->createdAt = $user->created_at;
            $userDTO->updatedAt = $user->updated_at;

            return $userDTO;
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function edit(string $id, UserDTO $userDTO): ?UserDTO
    {
        try {
            $updateData = [
                'fullname' => $userDTO->fullname ?? null,
            ];

            $filteredUpdateData = array_filter($updateData);
            $userId = $id;

            if (! empty($filteredUpdateData)) {
                $edit = User::where('id', $userId)
                    ->update($filteredUpdateData);

                if ($edit > 0) {
                    $updatedData = User::find($id);
                    if ($userDTO->role) {
                        $updatedData->syncRoles([$userDTO->role->value]);
                    }

                    return $this->mapUserModelToUserDto($updatedData);
                } else {
                    return null;
                }
            } else {
                if (! $userDTO->username && ! $userDTO->password && ! $userDTO->role) {
                    return null;
                }

                $unchangedUser = User::find($id);

                return $unchangedUser ? $this->mapUserModelToUserDto($unchangedUser) : null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function updateLastLoginAt(string $id, Carbon $lastLoginAt): bool
    {
        try {
            $user = User::find($id);

            if (! $user) {
                return false;
            }

            $user->last_login_at = $lastLoginAt;
            $user->save();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function activate(string $id): ?UserDTO
    {
        try {
            $user = User::find($id);

            if (! $user) {
                return null;
            }

            $user->is_active = true;
            $user->failed_login_attempts = 0;
            $user->save();

            return $this->mapUserModelToUserDto($user);
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function deactivate(string $id): ?UserDTO
    {
        try {
            return DB::transaction(function () use ($id) {
                $user = User::find($id);

                if (! $user) {
                    return null;
                }

                $user->is_active = false;
                $user->save();

                if (config('session.driver') === 'database') {
                    DB::table(config('session.table', 'sessions'))
                        ->where('user_id', $id)
                        ->delete();
                }

                return $this->mapUserModelToUserDto($user);
            });
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function resetFailedLoginAttempts(string $id): bool
    {
        try {
            $user = User::find($id);

            if (! $user) {
                return false;
            }

            $user->failed_login_attempts = 0;
            $user->save();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function getById(string $id): ?UserDTO
    {
        try {
            $user = User::find($id);

            if (! $user) {
                return null;
            }

            return $this->mapUserModelToUserDto($user);
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function getLatestId(): string|int
    {
        try {
            $latestId = User::max('id');
            if (! $latestId) {
                return 0;
            }

            return $latestId;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public function updateFailedLoginAttemptsById(string $id, int $failedLoginAttempts): bool
    {
        try {
            $user = User::find($id);

            if (! $user) {
                return false;
            }

            $user->failed_login_attempts = $failedLoginAttempts;
            $user->save();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function delete(string $id): bool
    {
        try {
            $user = User::find($id);

            if ($user) {
                $result = $user->delete();

                return $result;
            }

            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function mapUserModelToUserDto(User $user): UserDTO
    {
        try {
            $formatedUser = new UserDTO;
            $formatedUser->id = $user->id;
            $formatedUser->username = $user->username;
            $formatedUser->fullname = $user->fullname;
            $formatedUser->email = $user->email;
            $formatedUser->password = $user->password;
            $formatedUser->role = UserRoleHelper::stringToUserRole($user->roles[0]->name);
            $formatedUser->isActive = $user->is_active;
            $formatedUser->failedLoginAttempts = $user->failed_login_attempts;
            $formatedUser->lastLoginAt = $user->last_login_at ? Carbon::parse($user->last_login_at) : null;
            $formatedUser->createdAt = $user->created_at;
            $formatedUser->updatedAt = $user->updated_at;

            return $formatedUser;
        } catch (\Throwable $th) {
            return new UserDTO;
        }
    }
}
