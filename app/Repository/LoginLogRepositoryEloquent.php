<?php

namespace App\Repository;

use App\DTO\LoginLogDTO;
use App\Models\LoginLog;
use Carbon\Carbon;

class LoginLogRepositoryEloquent implements LoginLogRepository
{
    public function getAll(): array
    {
        $loginLogs = LoginLog::with('user')->orderBy('login_at', 'desc')->get();

        return $loginLogs->map(function ($loginLog) {
            return $this->mapLoginLogModelToDTO($loginLog);
        })->toArray();
    }

    public function getById(int $id): ?LoginLogDTO
    {
        $loginLog = LoginLog::with('user')->find($id);

        if (!$loginLog) {
            return null;
        }

        return $this->mapLoginLogModelToDTO($loginLog);
    }

    public function getByUserId(string $userId): array
    {
        $loginLogs = LoginLog::with('user')
            ->where('user_id', $userId)
            ->orderBy('login_at', 'desc')
            ->get();

        return $loginLogs->map(function ($loginLog) {
            return $this->mapLoginLogModelToDTO($loginLog);
        })->toArray();
    }

    public function getSuccessfulLogins(): array
    {
        $loginLogs = LoginLog::with('user')
            ->where('status', 'success')
            ->orderBy('login_at', 'desc')
            ->get();

        return $loginLogs->map(function ($loginLog) {
            return $this->mapLoginLogModelToDTO($loginLog);
        })->toArray();
    }

    public function getFailedLogins(): array
    {
        $loginLogs = LoginLog::with('user')
            ->where('status', 'failed')
            ->orderBy('login_at', 'desc')
            ->get();

        return $loginLogs->map(function ($loginLog) {
            return $this->mapLoginLogModelToDTO($loginLog);
        })->toArray();
    }

    public function getFailedLoginsByEmail(string $email, int $minutes = 15): array
    {
        $timeThreshold = Carbon::now()->subMinutes($minutes);

        $loginLogs = LoginLog::where('email_attempt', $email)
            ->where('status', 'failed')
            ->where('login_at', '>=', $timeThreshold)
            ->orderBy('login_at', 'desc')
            ->get();

        return $loginLogs->map(function ($loginLog) {
            return $this->mapLoginLogModelToDTO($loginLog);
        })->toArray();
    }

    public function getRecentLoginsByUserId(string $userId, int $limit = 10): array
    {
        $loginLogs = LoginLog::with('user')
            ->where('user_id', $userId)
            ->orderBy('login_at', 'desc')
            ->limit($limit)
            ->get();

        return $loginLogs->map(function ($loginLog) {
            return $this->mapLoginLogModelToDTO($loginLog);
        })->toArray();
    }

    public function insert(LoginLogDTO $loginLogDTO): ?LoginLogDTO
    {
        $loginLog = new LoginLog();
        $loginLog->fill([
            'user_id' => $loginLogDTO->userId,
            'ip_address' => $loginLogDTO->ipAddress,
            'username_attempt' => $loginLogDTO->usernameAttempt,
            'device_info' => $loginLogDTO->deviceInfo,
            'login_at' => $loginLogDTO->loginAt ?? Carbon::now(),
            'is_success' => $loginLogDTO->isSuccess,
            'failure_reason' => $loginLogDTO->failureReason,
        ]);

        $loginLog->save();

        $loginLogDTO->id = $loginLog->id;
        $loginLogDTO->createdAt = $loginLog->created_at;
        $loginLogDTO->updatedAt = $loginLog->updated_at;

        return $loginLogDTO;
    }

    public function delete(int $id): bool
    {
        $loginLog = LoginLog::find($id);

        if ($loginLog) {
            return $loginLog->delete();
        }

        return false;
    }

    public function getLatestId(): int
    {
        $latestId = LoginLog::max('id');
        
        if ($latestId === null) {
            return 0;
        }

        return $latestId;
    }

    private function mapLoginLogModelToDTO(LoginLog $loginLog): LoginLogDTO
    {
        $dto = new LoginLogDTO();
        $dto->id = $loginLog->id;
        $dto->userId = $loginLog->user_id;
        $dto->ipAddress = $loginLog->ip_address;
        $dto->usernameAttempt = $loginLog->username_attempt;
        $dto->deviceInfo = $loginLog->device_info;
        $dto->loginAt = Carbon::parse($loginLog->login_at);
        $dto->isSuccess = $loginLog->is_success;
        $dto->failureReason = $loginLog->failure_reason;
        $dto->createdAt = $loginLog->created_at;
        $dto->updatedAt = $loginLog->updated_at;

        return $dto;
    }
}
