<?php

namespace App\Repository;

use App\DTO\UnitDTO;
use Carbon\Carbon;

class UnitRepositoryImpl implements UnitRepository
{
    /** @var UnitDTO[] */
    private static array $units = [];

    public function __construct()
    {
        // Seed data hanya sekali
        if (empty(self::$units)) {
            self::$units = $this->seed();
        }
    }

    public function getAll(): array
    {
        return array_values(self::$units);
    }

    public function getById(string $id): ?UnitDTO
    {
        return self::$units[$id] ?? null;
    }

    public function insert(UnitDTO $unitDTO): ?UnitDTO
    {
        self::$units[$unitDTO->id] = $unitDTO;
        return $unitDTO;
    }

    public function edit(string $id, UnitDTO $unitDTO): ?UnitDTO
    {
        if (!isset(self::$units[$id])) {
            return null;
        }

        $unitDTO->updatedAt = Carbon::now();
        self::$units[$id] = $unitDTO;

        return $unitDTO;
    }

    public function delete(string $id): bool
    {
        if (!isset(self::$units[$id])) {
            return false;
        }

        unset(self::$units[$id]);
        return true;
    }

    public function getLatestId(): int
    {
        if (empty(self::$units)) {
            return 0;
        }

        $ids = array_keys(self::$units);

        $numbers = array_map(function ($id) {
            return (int) str_replace('UNIT-', '', $id);
        }, $ids);

        return max($numbers);
    }

    private function seed(): array
    {
        $now = Carbon::now();

        $units = [];

        $data = [
            'Tablet',
            'Kapsul',
            'Botol',
            'Strip',
            'Sachet',
        ];

        foreach ($data as $index => $name) {
            $unit = new UnitDTO();
            $unit->id = 'UNIT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $unit->name = $name;
            $unit->createdAt = $now;
            $unit->updatedAt = $now;

            $units[$unit->id] = $unit;
        }

        return $units;
    }
}
