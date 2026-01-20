<?php

namespace App\Repository;

use App\DTO\UnitDTO;

interface UnitRepository
{
    /* return harus array of UnitDTO */
    public function getAll(): array;

    public function insert(UnitDTO $unitDTO): ?UnitDTO;

    public function edit(string $id, UnitDTO $unitDTO): ?UnitDTO;

    public function getById(string $id): ?UnitDTO;

    public function getLatestId(): int;

    public function delete(string $id): bool;
}
