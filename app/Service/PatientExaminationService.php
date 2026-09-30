<?php

namespace App\Service;

use App\DTO\RegistrationDTO;

interface PatientExaminationService
{
    public function execute(
        RegistrationDTO $registrationDto,
        array $diagnoses,
        array $treatments,
    ): bool;
}

// Disable supaya ga perlu input data obat
// Nif - 17 April 2026
// interface PatientExaminationService
// {
//     public function execute(
//         RegistrationDTO $registrationDto,
//         array $diagnoses,
//         array $treatments,
//         array $medicines
//     ): bool;
// }
