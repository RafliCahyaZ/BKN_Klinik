<?php

namespace App\Repository;

use App\DTO\MedicineStockTransactionDTO;
use App\Models\MedicineStockTransaction;
use App\Repository\MedicineStockTransactionRepository;
use App\Type\TransactionType;
use Carbon\Carbon;

class MedicineStockTransactionRepositoryEloquent implements MedicineStockTransactionRepository
{
    protected $medicineRepository;

    public function __construct()
    {
        $this->medicineRepository = new MedicineRepositoryEloquent();
    }

    protected function getBeginningStock($medicineId)
    {
        try {
            $medicine = $this->medicineRepository->getById($medicineId);
            $beginningStock = $medicine->stock;
            return $beginningStock;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    protected function calculateLastStock($beginningStock, $quantity, $transactionType)
    {
        try {

            if ($transactionType == TransactionType::IN) {
                return $beginningStock + $quantity;
            }
            return $beginningStock - $quantity;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    function getAll(): array
    {
        try {
            $transactions_collections = MedicineStockTransaction::all();

            $formated_transactions = $transactions_collections->map(function ($transaction) {
                $formated_transaction = new MedicineStockTransactionDTO();
                $formated_transaction->id = $transaction->id;
                $formated_transaction->inventoryOfficerId = $transaction->inventory_officer_id;
                $formated_transaction->medicineId = $transaction->medicine_id;
                $formated_transaction->registrationId = $transaction->registration_id;
                $formated_transaction->type = $transaction->type;
                $formated_transaction->quantity = $transaction->quantity;
                $formated_transaction->beginningStock = $transaction->beginning_stock;
                $formated_transaction->lastStock = $transaction->last_stock;
                $formated_transaction->expiredDate = $transaction->expired_date ? Carbon::parse($transaction->expired_date) : null;
                $formated_transaction->notes = $transaction->notes;
                $formated_transaction->createdAt = Carbon::parse($transaction->created_at);
                $formated_transaction->updatedAt = Carbon::parse($transaction->updated_at);
                return $formated_transaction;
            });

            $transactions = $formated_transactions->toArray();

            return $transactions;
        } catch (\Exception $e) {
            return [];
        }
    }


    function insert(MedicineStockTransactionDTO $medicineStockTransactionDTO): ?MedicineStockTransactionDTO
    {
        try {
            $medicineId = $medicineStockTransactionDTO->medicineId;
            $beginningStock = $this->getBeginningStock($medicineId);
            $quantity = $medicineStockTransactionDTO->quantity;
            $transactionType = $medicineStockTransactionDTO->type;
            $lastStock = $this->calculateLastStock($beginningStock, $quantity, $transactionType);

            $transaction = MedicineStockTransaction::create([
                'inventory_officer_id' => $medicineStockTransactionDTO->inventoryOfficerId,
                'medicine_id' => $medicineStockTransactionDTO->medicineId,
                'registration_id' => $medicineStockTransactionDTO->registrationId,
                'type' => $medicineStockTransactionDTO->type,
                'quantity' => $medicineStockTransactionDTO->quantity,
                'beginning_stock' => $beginningStock,
                'last_stock' => $lastStock,
                'expired_date' => $medicineStockTransactionDTO->expiredDate ? $medicineStockTransactionDTO->expiredDate->toDateString() : null,
                'notes' => $medicineStockTransactionDTO->notes,
            ]);

            $medicineStockTransactionDTO->id = $transaction->id;
            $medicineStockTransactionDTO->beginningStock = $transaction->beginning_stock;
            $medicineStockTransactionDTO->lastStock = $transaction->last_stock;
            $medicineStockTransactionDTO->createdAt = $transaction->created_at;
            $medicineStockTransactionDTO->updatedAt = $transaction->updated_at;

            return $medicineStockTransactionDTO;
        } catch (\Exception $e) {
            return null;
        }
    }

    function insertMany(array $medicineStockTransactionDTOs): bool
    {
        try {
            $newData = collect($medicineStockTransactionDTOs)->map(function ($dto) {
                $medicineId = $dto->medicineId;
                $beginningStock = $this->getBeginningStock($medicineId);
                $quantity = $dto->quantity;
                $transactionType = $dto->type;
                $lastStock = $this->calculateLastStock($beginningStock, $quantity, $transactionType);
                return [
                    'inventory_officer_id' => $dto->inventoryOfficerId,
                    'medicine_id' => $dto->medicineId,
                    'registration_id' => $dto->registrationId,
                    'type' => $dto->type,
                    'quantity' => $dto->quantity,
                    'beginning_stock' => $beginningStock,
                    'last_stock' => $lastStock,
                    'expired_date' => $dto->expiredDate ? $dto->expiredDate->toDateString() : null,
                    'notes' => $dto->notes,
                ];
            });
            $newDataArray = $newData->toArray();
            MedicineStockTransaction::insert($newDataArray);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    function edit(string $id, MedicineStockTransactionDTO $medicineStockTransactionDTO): ?MedicineStockTransactionDTO
    {
        try {
            $updateData = [
                'inventory_officer_id' => $medicineStockTransactionDTO->inventoryOfficerId ?? null,
                'medicine_id' => $medicineStockTransactionDTO->medicineId ?? null,
                'registration_id' => $medicineStockTransactionDTO->registrationId ?? null,
                'type' => $medicineStockTransactionDTO->type ?? null,
                'quantity' => $medicineStockTransactionDTO->quantity ?? null,
                'beginning_stock' => $medicineStockTransactionDTO->beginningStock ?? null,
                'last_stock' => $medicineStockTransactionDTO->lastStock ?? null,
                'expired_date' => $medicineStockTransactionDTO->expiredDate ?? null,
                'notes' => $medicineStockTransactionDTO->notes ?? null,
            ];
            $filteredUpdateData = array_filter($updateData, function ($value) {
                return $value !== null;
            });
            $transactionId = $id;

            if (!empty($filteredUpdateData)) {

                $edit = MedicineStockTransaction::where('id', $transactionId)
                    ->update($filteredUpdateData);

                if ($edit > 0) {
                    $updatedData = MedicineStockTransaction::find($id);

                    $formatedUpdatedData = new MedicineStockTransactionDTO();
                    $formatedUpdatedData->id = $updatedData->id;
                    $formatedUpdatedData->inventoryOfficerId = $updatedData->inventory_officer_id;
                    $formatedUpdatedData->medicineId = $updatedData->medicine_id;
                    $formatedUpdatedData->registrationId = $updatedData->registration_id;
                    $formatedUpdatedData->type = $updatedData->type;
                    $formatedUpdatedData->quantity = $updatedData->quantity;
                    $formatedUpdatedData->beginningStock = $updatedData->beginning_stock;
                    $formatedUpdatedData->lastStock = $updatedData->last_stock;
                    $formatedUpdatedData->expiredDate = $updatedData->expired_date ? Carbon::parse($updatedData->expired_date) : null;
                    $formatedUpdatedData->notes = $updatedData->notes;
                    $formatedUpdatedData->createdAt = $updatedData->created_at;
                    $formatedUpdatedData->updatedAt = $updatedData->updated_at;

                    return $formatedUpdatedData;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    function getById(string $id): ?MedicineStockTransactionDTO
    {
        try {
            $transaction = MedicineStockTransaction::find($id);

            if (!$transaction) {
                return null;
            }

            $formatedTransaction = new MedicineStockTransactionDTO();
            $formatedTransaction->id = $transaction->id;
            $formatedTransaction->inventoryOfficerId = $transaction->inventory_officer_id;
            $formatedTransaction->medicineId = $transaction->medicine_id;
            $formatedTransaction->registrationId = $transaction->registration_id;
            $formatedTransaction->type = $transaction->type;
            $formatedTransaction->quantity = $transaction->quantity;
            $formatedTransaction->beginningStock = $transaction->beginning_stock;
            $formatedTransaction->lastStock = $transaction->last_stock;
            $formatedTransaction->expiredDate = $transaction->expired_date ? Carbon::parse($transaction->expired_date) : null;
            $formatedTransaction->notes = $transaction->notes;
            $formatedTransaction->createdAt = $transaction->created_at;
            $formatedTransaction->updatedAt = $transaction->updated_at;
            return $formatedTransaction;
        } catch (\Exception $e) {
            return null;
        }
    }

    function getLatestId(): int
    {
        try {
            $latestId = MedicineStockTransaction::max('id');
            if (!$latestId) {
                return 0;
            }
            return $latestId;
        } catch (\Exception $e) {
            return 0;
        }
    }

    function delete(string $id): bool
    {
        try {
            $transaction = MedicineStockTransaction::find($id);

            if ($transaction) {
                $result = $transaction->delete();
                return $result;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    function getByMedicineId(string $medicineId): array
    {
        try {
            $transactions_collections = MedicineStockTransaction::where('medicine_id', $medicineId)->get();

            $formated_transactions = $transactions_collections->map(function ($transaction) {
                $formated_transaction = new MedicineStockTransactionDTO();
                $formated_transaction->id = $transaction->id;
                $formated_transaction->inventoryOfficerId = $transaction->inventory_officer_id;
                $formated_transaction->medicineId = $transaction->medicine_id;
                $formated_transaction->registrationId = $transaction->registration_id;
                $formated_transaction->type = $transaction->type;
                $formated_transaction->quantity = $transaction->quantity;
                $formated_transaction->beginningStock = $transaction->beginning_stock;
                $formated_transaction->lastStock = $transaction->last_stock;
                $formated_transaction->expiredDate = $transaction->expired_date ? Carbon::parse($transaction->expired_date) : null;
                $formated_transaction->notes = $transaction->notes;
                $formated_transaction->createdAt = $transaction->created_at;
                $formated_transaction->updatedAt = $transaction->updated_at;
                return $formated_transaction;
            });

            return $formated_transactions->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
    function getByType(TransactionType $type): array
    {
        try {
            $transactions_collections = MedicineStockTransaction::where('type', $type->value)->get();

            $formated_transactions = $transactions_collections->map(function ($transaction) {
                $formated_transaction = new MedicineStockTransactionDTO();
                $formated_transaction->id = $transaction->id;
                $formated_transaction->inventoryOfficerId = $transaction->inventory_officer_id;
                $formated_transaction->medicineId = $transaction->medicine_id;
                $formated_transaction->registrationId = $transaction->registration_id;
                $formated_transaction->type = $transaction->type;
                $formated_transaction->quantity = $transaction->quantity;
                $formated_transaction->beginningStock = $transaction->beginning_stock;
                $formated_transaction->lastStock = $transaction->last_stock;
                $formated_transaction->expiredDate = $transaction->expired_date ? Carbon::parse($transaction->expired_date) : null;
                $formated_transaction->notes = $transaction->notes;
                $formated_transaction->createdAt = $transaction->created_at;
                $formated_transaction->updatedAt = $transaction->updated_at;
                return $formated_transaction;
            });

            return $formated_transactions->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
}
