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
}
