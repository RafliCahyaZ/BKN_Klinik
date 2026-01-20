<?php

namespace App\Repository;

use App\DTO\MedicineStockTransactionDTO;

class MedicineStockTransactionRepositoryImpl implements MedicineStockTransactionRepository
{
    /* return harus array of MedicineStockTransactionDTO */
    public function getAll(): array
    {
        return [];
    }

    public function insert(MedicineStockTransactionDTO $medicineStockTransactionDTO): ?MedicineStockTransactionDTO
    {
        return null;
    }

    public function edit(string $id, MedicineStockTransactionDTO $medicineStockTransactionDTO): ?MedicineStockTransactionDTO
    {
        return null;
    }

    public function getById(string $id): ?MedicineStockTransactionDTO
    {
        return null;
    }

    public function getLatestId(): int
    {
        return 0;
    }

    public function delete(string $id): bool
    {
        return true;
    }

    public function getByMedicineId(string $medicineId): array
    {
        return [];
    }

    public function getByType(string $type): array
    {
        return [];
    }
}
