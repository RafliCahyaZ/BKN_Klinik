<?php

namespace App\Repository;

use App\DTO\PatientDTO;
use App\DTO\RegistrationDTO;
use App\Models\Patient;

interface RegistrationRepository
{
    public function getAll(): array;

    public function getById(): ?RegistrationDTO;

    public function insert(RegistrationDTO $registrationDto): ?RegistrationDTO;
}
