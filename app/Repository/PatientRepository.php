<?php

namespace App\Repository;

use App\DTO\PatientDTO;

interface PatientRepository
{
    /* return harus array of PatientDTO */
    public function getAll(): array;

    public function insert(PatientDTO $patientDTO): ?PatientDTO;

    public function getLatestId(): int;
}
