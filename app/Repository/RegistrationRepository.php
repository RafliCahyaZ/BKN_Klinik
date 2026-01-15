<?php

namespace App\Repository;

use App\DTO\PatientDTO;
use App\DTO\RegistrationDTO;
use App\Models\Patient;
use Carbon\CarbonInterface;

interface RegistrationRepository
{
    public function getAll(): array;

    public function getById(string $id): ?RegistrationDTO;

    public function insert(RegistrationDTO $registrationDto): ?RegistrationDTO;

    public function edit(RegistrationDTO $registrationDTO): ?RegistrationDTO;

    public function getWhereCheckDateIsNull(): array;

    public function getWhereCheckDateIsNotNull(): array;

    public function getByRangeDate(CarbonInterface $startDate, CarbonInterface $endDate): array;
}
