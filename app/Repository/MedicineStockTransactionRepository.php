<?php

namespace App\Repository;

use App\DTO\MedicineStockTransactionDTO;
use App\Type\TransactionType;

interface MedicineStockTransactionRepository
{
    /* return harus array of MedicineStockTransactionDTO */
    public function getAll(): array;

    public function insert(MedicineStockTransactionDTO $medicineStockTransactionDTO): ?MedicineStockTransactionDTO;

    public function insertMany(array $medicineStockTransactionDTOs): bool;

    public function edit(string $id, MedicineStockTransactionDTO $medicineStockTransactionDTO): ?MedicineStockTransactionDTO;

    public function getById(string $id): ?MedicineStockTransactionDTO;

    public function getLatestId(): int;

    public function delete(string $id): bool;

    public function getByMedicineId(string $medicineId): array;

    public function getByType(TransactionType $type): array;

    public function getByPeriod($startDate, $endDate): array;
}
