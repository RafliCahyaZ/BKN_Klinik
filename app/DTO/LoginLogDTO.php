<?php

namespace App\DTO;

use Carbon\CarbonInterface;

class LoginLogDTO
{
    public ?int $id = null;
    public ?string $userId = null;
    public ?string $ipAddress = null;
    public string $usernameAttempt;
    public ?string $deviceInfo = null;
    public ?CarbonInterface $loginAt = null;
    public bool $isSuccess = false;
    public ?string $failureReason = null;
    public ?CarbonInterface $createdAt = null;
    public ?CarbonInterface $updatedAt = null;
}
