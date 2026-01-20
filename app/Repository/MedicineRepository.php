<?php

namespace App\Repository;

use App\DTO\MedicineDTO;

interface MedicineRepository
{
    /* return harus array of MedicineDTO */
    public function getAll(): array;

    public function insert(MedicineDTO $medicineDTO): ?MedicineDTO;

    public function edit(string $id, MedicineDTO $medicineDTO): ?MedicineDTO;

    public function getById(string $id): ?MedicineDTO;

    public function getLatestId(): int;

    public function delete(string $id): bool;
}
