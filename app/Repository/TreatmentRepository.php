<?php

namespace App\Repository;

use App\DTO\TreatmentDTO;

interface TreatmentRepository

{
    public function getAll(): array;

    public function getById(string $id): ?TreatmentDTO;

    public function insert(TreatmentDTO $treatmentDTO): ?TreatmentDTO;

    public function edit(string $id, TreatmentDTO $treatmentDTO): ?TreatmentDTO;

    public function getLatestId(): int;

    public function delete(string $id): bool;
}
