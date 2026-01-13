<?php

namespace App\DTO;

use Carbon\CarbonInterface;

class UserDTO
{
    public string $id;
    public string $username;
    public string $password;
    public string $fullname;
    public string $email;
    public string $role;
    public CarbonInterface $createdAt;
    public CarbonInterface $updatedAt;
}
