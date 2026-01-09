<?php

namespace App\Repository;

use App\DTO\UserDTO;
use Carbon\Carbon;

class UserRepositoryImpl implements UserRepository
{
    public function getAll(): array
    {
        // 1
        $user1 = new UserDTO();
        $user1->id = '1';
        $user1->username = 'admin';
        $user1->password = 'password';
        $user1->fullname = 'Administrator Sistem';
        $user1->email = 'admin@example.com';
        $user1->role = 'ADMIN';
        $user1->createdAt = Carbon::now()->subMonths(2);
        $user1->updatedAt = Carbon::now()->subDays(5);

        // 2
        $user2 = new UserDTO();
        $user2->id = '2';
        $user2->username = 'dokter1';
        $user2->password = 'password';
        $user2->fullname = 'Dr. Andi Wijaya';
        $user2->email = 'andi@example.com';
        $user2->role = 'DOCTOR';
        $user2->createdAt = Carbon::now()->subMonth();
        $user2->updatedAt = Carbon::now()->subDays(3);

        // 3
        $user3 = new UserDTO();
        $user3->id = '3';
        $user3->username = 'perawat1';
        $user3->password = 'password';
        $user3->fullname = 'Siti Aminah';
        $user3->email = 'siti@example.com';
        $user3->role = 'NURSE';
        $user3->createdAt = Carbon::now()->subWeeks(3);
        $user3->updatedAt = Carbon::now()->subDays(2);

        // 4
        $user4 = new UserDTO();
        $user4->id = '4';
        $user4->username = 'resepsionis';
        $user4->password = 'password';
        $user4->fullname = 'Dewi Lestari';
        $user4->email = 'dewi@example.com';
        $user4->role = 'RECEPTIONIST';
        $user4->createdAt = Carbon::now()->subWeeks(2);
        $user4->updatedAt = Carbon::now()->subDay();

        // 5
        $user5 = new UserDTO();
        $user5->id = '5';
        $user5->username = 'user1';
        $user5->password = 'password';
        $user5->fullname = 'Rizky Pratama';
        $user5->email = 'rizky@example.com';
        $user5->role = 'USER';
        $user5->createdAt = Carbon::now()->subDays(5);
        $user5->updatedAt = Carbon::now();

        return [$user1, $user2, $user3, $user4, $user5];
    }

    public function getById(string $id): ?UserDTO
    {
        foreach ($this->getAll() as $user) {
            if ($user->id === $id) {
                return $user;
            }
        }

        return null;
    }

    public function getByEmail(string $email): ?UserDTO
    {
        foreach ($this->getAll() as $user) {
            if ($user->email === $email) {
                return $user;
            }
        }

        return null;
    }

    public function insert(UserDTO $userDTO): ?UserDTO
    {
        return $userDTO;
    }

    public function edit(string $id, UserDTO $userDTO): ?UserDTO
    {
        return $userDTO;
    }

    public function delete(string $id): bool
    {
        return false;
    }

    public function getLatestId(): int
    {
        return 5;
    }
}

