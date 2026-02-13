<?php

namespace App\DTO;

use App\Type\UserRole;
use Carbon\CarbonInterface;

class UserDTO
{
    public string $id;
    public string $username;
    public string $password;
    public string $fullname;
    public string $email;
    public UserRole $role;
    public bool $isActive;
    public int $failedLoginAttempts;
    public ?CarbonInterface $lastLoginAt = null;
    public CarbonInterface $createdAt;
    public CarbonInterface $updatedAt;

    static function createFromArray(array $data): UserDTO
    {
        $userDTO = new UserDTO();
        $userDTO->id = $data['id'];
        $userDTO->username = $data['username'];
        $userDTO->password = $data['password'];
        $userDTO->fullname = $data['fullname'];
        $userDTO->email = $data['email'];
        $userDTO->role = UserRole::from($data['role']);
        $userDTO->isActive = $data['is_active'];
        $userDTO->failedLoginAttempts = $data['failed_login_attempts'];
        $userDTO->lastLoginAt = $data['last_login_at'];
        $userDTO->createdAt = $data['created_at'];
        $userDTO->updatedAt = $data['updated_at'];

        return $userDTO;
    }
}
