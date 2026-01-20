<?php

namespace App\Repository;

use App\DTO\MedicineDTO;
use App\DTO\UnitDTO;
use Carbon\Carbon;

class MedicineRepositoryImpl implements MedicineRepository
{
    /** @var MedicineDTO[] */
    private static array $medicines = [];

    public function __construct()
    {
        // seed data hanya sekali
        if (empty(self::$medicines)) {
            self::$medicines = $this->seed();
        }
    }

    public function getAll(): array
    {
        return array_values(self::$medicines);
    }

    public function getById(string $id): ?MedicineDTO
    {
        return self::$medicines[$id] ?? null;
    }

    public function insert(MedicineDTO $medicineDTO): ?MedicineDTO
    {
        $medicineDTO->id = "MED-" . $this->getLatestId() + 1;
        self::$medicines[$medicineDTO->id] = $medicineDTO;
        return $medicineDTO;
    }

    public function edit(string $id, MedicineDTO $medicineDTO): ?MedicineDTO
    {
        if (!isset(self::$medicines[$id])) {
            return null;
        }

        $medicineDTO->updatedAt = Carbon::now();
        self::$medicines[$id] = $medicineDTO;

        return $medicineDTO;
    }

    public function delete(string $id): bool
    {
        if (!isset(self::$medicines[$id])) {
            return false;
        }

        unset(self::$medicines[$id]);
        return true;
    }

    public function getLatestId(): int
    {
        if (empty(self::$medicines)) {
            return 0;
        }

        $ids = array_keys(self::$medicines);

        $numbers = array_map(function ($id) {
            return (int) str_replace('MED-', '', $id);
        }, $ids);

        return max($numbers);
    }

    private function seed(): array
    {
        $unitTablet = new UnitDTO();
        $unitTablet->id = 'UNIT-001';
        $unitTablet->name = 'Tablet';
        $unitTablet->createdAt = Carbon::now();
        $unitTablet->updatedAt = Carbon::now();

        $medicine = new MedicineDTO();
        $medicine->id = 'MED-001';
        $medicine->name = 'Paracetamol';
        $medicine->stock = 100;
        $medicine->unit = $unitTablet;
        $medicine->createdAt = Carbon::now();
        $medicine->updatedAt = Carbon::now();

        return [
            $medicine->id => $medicine
        ];
    }
}
