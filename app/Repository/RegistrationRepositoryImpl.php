<?php

namespace App\Repository;

use App\DTO\RegistrationDTO;
use Carbon\Carbon;

class RegistrationRepositoryImpl implements RegistrationRepository
{
    public function getAll(): array{
        #1
        $registration1 = new RegistrationDTO();
        $registration1->id = "1";
        $registration1->patientId = "P001";
        $registration1->registrationDate = Carbon::parse('2024-01-15');
        $registration1->status = "completed";

        #2



    }

    public function getById(string $id): ?RegistrationDTO{

    }

    public function insert(RegistrationDTO $registrationDTO): ?RegistrationDTO{

    }

    public function edit(string $id, RegistrationDTO $registrationDTO): ?RegistrationDTO{

    }

    public function getLatestId(): int{

    }

    public function delete(string $id): bool{

    }
}
