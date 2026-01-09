<?php

namespace App\Repository;

interface DiagnosisRepository
{
    /* return harus array of DiagnosisDTO */
    public function getAll(): array;

    public function getById(string $id): ?DiagnosisDTO;

    public function insert(DiagnosisDTO $diagnosisDTO): ?DiagnosisDTO;

    public function edit(string $id, DiagnosisDTO $diagnosisDTO): ?DiagnosisDTO;

    public function getLatestId(): int;
    
    public function delete(string $id): bool;
}
