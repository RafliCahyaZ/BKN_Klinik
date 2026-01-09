<?php

namespace App\Repository;

use App\DTO\PatientDTO;

interface PatientRepository
{
    /* return harus array of PatientDTO */
    public function getAll(): array;

    public function insert(PatientDTO $patientDTO): ?PatientDTO;

    public function edit(string $id, PatientDTO $patientDTO): ?PatientDTO;

    public function getById(string $id): ?PatientDTO;

    public function getLatestId(): int;

    public function delete(string $id): bool;
}
