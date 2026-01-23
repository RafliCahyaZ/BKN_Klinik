<?php

namespace App\Service;

use App\DTO\RegistrationDTO;

interface PatientExaminationService
{
    public function execute(
        RegistrationDTO $registrationDto,
        array $diagnoses,
        array $treatments,
        array $medicines
    ): bool;
}
