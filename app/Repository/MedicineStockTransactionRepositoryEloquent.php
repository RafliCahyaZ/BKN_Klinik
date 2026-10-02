<?php

namespace App\Repository;

use App\DTO\MedicineDTO;
use App\DTO\MedicineStockTransactionDTO;
use App\DTO\UserDTO;
use App\Models\Medicine;
use App\Models\MedicineStockTransaction;
use App\Type\TransactionType;
use Carbon\Carbon;

class MedicineStockTransactionRepositoryEloquent implements MedicineStockTransactionRepository
{
    public function __construct(
        private MedicineRepository $medicineRepository
    ) {}

    public function getAll(): array
    {
        try {
            $transactionsCollections = MedicineStockTransaction::with(['inventoryOfficer', 'medicine.unit', 'registration.patient', 'registration.doctor'])->get();

            $formatedTransactions = $transactionsCollections->map(function ($transaction) {
                $formatedTransaction = new MedicineStockTransactionDTO;
                $formatedTransaction->id = $transaction->id;

                $inventoryOfficer = new UserDTO;
                if ($transaction->inventoryOfficer) {
                    $inventoryOfficer = UserRepositoryEloquent::mapUserModelToUserDto($transaction->inventoryOfficer);
                }
                $formatedTransaction->inventoryOfficer = $inventoryOfficer;

                $medicine = new MedicineDTO;
                if ($transaction->medicine) {
                    $medicine = MedicineRepositoryEloquent::mapMedicineModelToMedicineDto($transaction->medicine);
                }
                $formatedTransaction->medicine = $medicine;

                $registration = null;
                if ($transaction->registration) {
                    $registration = RegistrationRepositoryEloquent::mapRegistrationModelToRegistrationDto($transaction->registration);
                }

                $formatedTransaction->registration = $registration;

                $formatedTransaction->type = $transaction->type;
                $formatedTransaction->quantity = $transaction->quantity;
                $formatedTransaction->frequency = $transaction->frequency;
                $formatedTransaction->dose = $transaction->dose;
                $formatedTransaction->beginningStock = $transaction->beginning_stock;
                $formatedTransaction->lastStock = $transaction->last_stock;
                $formatedTransaction->notes = $transaction->notes;
                $formatedTransaction->createdAt = Carbon::parse($transaction->created_at);
                $formatedTransaction->updatedAt = Carbon::parse($transaction->updated_at);

                return $formatedTransaction;
            });

            $transactions = $formatedTransactions->toArray();

            return $transactions;
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function insert(MedicineStockTransactionDTO $medicineStockTransactionDTO): ?MedicineStockTransactionDTO
    {

        try {

            $transaction = MedicineStockTransaction::create([
                'inventory_officer_id' => $medicineStockTransactionDTO->inventoryOfficer->id,
                'medicine_id' => $medicineStockTransactionDTO->medicine->id,
                'registration_id' => $medicineStockTransactionDTO->registration ? $medicineStockTransactionDTO->registration->id : null,
                'type' => $medicineStockTransactionDTO->type,
                'quantity' => $medicineStockTransactionDTO->quantity,

                // Frekuensi dan dosis obat
                'frequency' => $medicineStockTransactionDTO->frequency,
                'dose' => $medicineStockTransactionDTO->dose,

                'beginning_stock' => $medicineStockTransactionDTO->beginningStock,
                'last_stock' => $medicineStockTransactionDTO->lastStock,
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

    // function insertMany(array $medicineStockTransactionDTOs): bool
    // {
    //     try {
    //         $newData = collect($medicineStockTransactionDTOs)->map(function ($dto) {
    //             $medicineId = $dto->medicine->id;
    //             $beginningStock = $this->getBeginningStock($medicineId);
    //             $quantity = $dto->quantity;
    //             $transactionType = $dto->type;
    //             $lastStock = $this->calculateLastStock($beginningStock, $quantity, $transactionType);

    //             if ($lastStock < 0) {
    //                 throw new \Exception("Insufficient stock for medicine ID: " . $medicineId);
    //             }
    //             return [
    //                 'inventory_officer_id' => $dto->inventoryOfficer->id,
    //                 'medicine_id' => $dto->medicine->id,
    //                 'registration_id' => $dto->registration ? $dto->registration->id : null,
    //                 'type' => $dto->type,
    //                 'quantity' => $dto->quantity,
    //                 'beginning_stock' => $beginningStock,
    //                 'last_stock' => $lastStock,
    //                 'expired_date' => $dto->expiredDate ? $dto->expiredDate->toDateString() : null,
    //                 'notes' => $dto->notes,
    //                 'created_at' => Carbon::now(),
    //                 'updated_at' => Carbon::now()
    //             ];
    //         });
    //         $newDataArray = $newData->toArray();
    //         MedicineStockTransaction::insert($newDataArray);
    //         return true;
    //     } catch (\Throwable $th) {
    //         return false;
    //     }
    // }

    public function edit(string $id, MedicineStockTransactionDTO $medicineStockTransactionDTO): ?MedicineStockTransactionDTO
    {
        try {
            $updateData = [
                'inventory_officer_id' => $medicineStockTransactionDTO->inventoryOfficer->id ?? null,
                'medicine_id' => $medicineStockTransactionDTO->medicine->id ?? null,
                'registration_id' => $medicineStockTransactionDTO->registration ? $medicineStockTransactionDTO->registration->id : null,
                'type' => $medicineStockTransactionDTO->type ?? null,
                'quantity' => $medicineStockTransactionDTO->quantity ?? null,
                'frequency' => $medicineStockTransactionDTO->frequency ?? null,
                'dose' => $medicineStockTransactionDTO->dose ?? null,
                'beginning_stock' => $medicineStockTransactionDTO->beginningStock ?? null,
                'last_stock' => $medicineStockTransactionDTO->lastStock ?? null,
                'expired_date' => $medicineStockTransactionDTO->expiredDate ?? null,
                'notes' => $medicineStockTransactionDTO->notes ?? null,
            ];
            $filteredUpdateData = array_filter($updateData, function ($value) {
                return $value !== null;
            });
            $transactionId = $id;

            if (! empty($filteredUpdateData)) {

                $edit = MedicineStockTransaction::where('id', $transactionId)
                    ->update($filteredUpdateData);

                if ($edit > 0) {
                    $updatedData = MedicineStockTransaction::with(['inventoryOfficer', 'medicine.unit', 'registration.patient', 'registration.doctor'])->find($id);

                    $formatedUpdatedData = new MedicineStockTransactionDTO;
                    $formatedUpdatedData->id = $updatedData->id;

                    $inventoryOfficer = new UserDTO;
                    if ($updatedData->inventoryOfficer) {
                        $inventoryOfficer = UserRepositoryEloquent::mapUserModelToUserDto($updatedData->inventoryOfficer);
                    }
                    $formatedUpdatedData->inventoryOfficer = $inventoryOfficer;

                    $medicine = new MedicineDTO;
                    if ($updatedData->medicine) {
                        $medicine = MedicineRepositoryEloquent::mapMedicineModelToMedicineDto($updatedData->medicine);
                    }
                    $formatedUpdatedData->medicine = $medicine;

                    $registration = null;
                    if ($updatedData->registration) {
                        $registration = RegistrationRepositoryEloquent::mapRegistrationModelToRegistrationDto($updatedData->registration);
                    }
                    $formatedUpdatedData->registration = $registration;

                    $formatedUpdatedData->type = $updatedData->type;
                    $formatedUpdatedData->quantity = $updatedData->quantity;
                    $formatedUpdatedData->frequency = $updatedData->frequency;
                    $formatedUpdatedData->dose = $updatedData->dose;
                    $formatedUpdatedData->beginningStock = $updatedData->beginning_stock;
                    $formatedUpdatedData->lastStock = $updatedData->last_stock;
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

    public function getById(string $id): ?MedicineStockTransactionDTO
    {
        try {
            $transaction = MedicineStockTransaction::with(['inventoryOfficer', 'medicine.unit', 'registration.patient', 'registration.doctor'])->find($id);

            if (! $transaction) {
                return null;
            }

            $formatedTransaction = new MedicineStockTransactionDTO;
            $formatedTransaction->id = $transaction->id;

            $inventoryOfficer = new UserDTO;
            if ($transaction->inventoryOfficer) {
                $inventoryOfficer = UserRepositoryEloquent::mapUserModelToUserDto($transaction->inventoryOfficer);
            }
            $formatedTransaction->inventoryOfficer = $inventoryOfficer;

            $medicine = new MedicineDTO;
            if ($transaction->medicine) {
                $medicine = MedicineRepositoryEloquent::mapMedicineModelToMedicineDto($transaction->medicine);
            }
            $formatedTransaction->medicine = $medicine;

            $registration = null;
            if ($transaction->registration) {
                $registration = RegistrationRepositoryEloquent::mapRegistrationModelToRegistrationDto($transaction->registration);
            }
            $formatedTransaction->registration = $registration;
            $formatedTransaction->type = $transaction->type;
            $formatedTransaction->quantity = $transaction->quantity;
            $formatedTransaction->frequency = $transaction->frequency;
            $formatedTransaction->dose = $transaction->dose;
            $formatedTransaction->beginningStock = $transaction->beginning_stock;
            $formatedTransaction->lastStock = $transaction->last_stock;
            $formatedTransaction->notes = $transaction->notes;
            $formatedTransaction->createdAt = $transaction->created_at;
            $formatedTransaction->updatedAt = $transaction->updated_at;

            return $formatedTransaction;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getLatestId(): int
    {
        try {
            $latestId = MedicineStockTransaction::max('id');
            if (! $latestId) {
                return 0;
            }

            return $latestId;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public function delete(string $id): bool
    {
        try {
            $transaction = MedicineStockTransaction::find($id);

            if ($transaction) {
                $result = $transaction->delete();

                return $result;
            }

            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function getByMedicineId(string $medicineId): array
    {
        try {
            $transactionsCollections = MedicineStockTransaction::with(['inventoryOfficer', 'medicine.unit', 'registration.patient', 'registration.doctor'])
                ->where('medicine_id', $medicineId)
                ->get();

            $formated_transactions = $transactionsCollections->map(function ($transaction) {
                $formated_transaction = new MedicineStockTransactionDTO;
                $formated_transaction->id = $transaction->id;

                $inventoryOfficer = new UserDTO;
                if ($transaction->inventoryOfficer) {
                    $inventoryOfficer = UserRepositoryEloquent::mapUserModelToUserDto($transaction->inventoryOfficer);
                }
                $formated_transaction->inventoryOfficer = $inventoryOfficer;

                $medicine = new MedicineDTO;
                if ($transaction->medicine) {
                    $medicine = MedicineRepositoryEloquent::mapMedicineModelToMedicineDto($transaction->medicine);
                }
                $formated_transaction->medicine = $medicine;

                $registration = null;
                if ($transaction->registration) {
                    $registration = RegistrationRepositoryEloquent::mapRegistrationModelToRegistrationDto($transaction->registration);
                }

                $formated_transaction->registration = $registration;
                $formated_transaction->type = $transaction->type;
                $formated_transaction->quantity = $transaction->quantity;
                $formated_transaction->frequency = $transaction->frequency;
                $formated_transaction->dose = $transaction->dose;
                $formated_transaction->beginningStock = $transaction->beginning_stock;
                $formated_transaction->lastStock = $transaction->last_stock;
                $formated_transaction->notes = $transaction->notes;
                $formated_transaction->createdAt = $transaction->created_at;
                $formated_transaction->updatedAt = $transaction->updated_at;

                return $formated_transaction;
            });

            return $formated_transactions->toArray();
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function getByType(TransactionType $type): array
    {
        try {
            $transactionsCollections = MedicineStockTransaction::with(['inventoryOfficer', 'medicine.unit', 'registration.patient', 'registration.doctor'])
                ->where('type', $type->value)
                ->get();

            $formatedTransactions = $transactionsCollections->map(function ($transaction) {

                $inventoryOfficer = new UserDTO;
                if ($transaction->inventoryOfficer) {
                    $inventoryOfficer = UserRepositoryEloquent::mapUserModelToUserDto($transaction->inventoryOfficer);
                }

                $medicine = new MedicineDTO;
                if ($transaction->medicine) {
                    $medicine = MedicineRepositoryEloquent::mapMedicineModelToMedicineDto($transaction->medicine);
                }

                $registration = null;
                if ($transaction->registration) {
                    $registration = RegistrationRepositoryEloquent::mapRegistrationModelToRegistrationDto($transaction->registration);
                }

                $formatedTransaction = new MedicineStockTransactionDTO;
                $formatedTransaction->id = $transaction->id;
                $formatedTransaction->inventoryOfficer = $inventoryOfficer;
                $formatedTransaction->medicine = $medicine;
                $formatedTransaction->registration = $registration;
                $formatedTransaction->type = $transaction->type;
                $formatedTransaction->quantity = $transaction->quantity;
                $formatedTransaction->frequency = $transaction->frequency;
                $formatedTransaction->dose = $transaction->dose;
                $formatedTransaction->beginningStock = $transaction->beginning_stock;
                $formatedTransaction->lastStock = $transaction->last_stock;
                $formatedTransaction->notes = $transaction->notes;
                $formatedTransaction->createdAt = $transaction->created_at;
                $formatedTransaction->updatedAt = $transaction->updated_at;

                return $formatedTransaction;
            });

            return $formatedTransactions->toArray();
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function getByPeriod($startDate, $endDate): array
    {
        try {
            $medicineStocks = Medicine::select('id', 'name')
                ->addSelect([
                    'beginning_stock' => MedicineStockTransaction::selectRaw('SUM(CASE WHEN type = "in" THEN quantity ELSE -quantity END)')
                        ->whereColumn('medicine_id', 'medicines.id')
                        ->whereDate('created_at', '<', $startDate),

                    'last_stock' => MedicineStockTransaction::selectRaw('SUM(CASE WHEN type = "in" THEN quantity ELSE -quantity END)')
                        ->whereColumn('medicine_id', 'medicines.id')
                        ->whereDate('created_at', '<=', $endDate),
                ])
                ->get()
                ->map(function ($medicine) {
                    $medicine->beginning_stock = $medicine->beginning_stock ?? 0;
                    $medicine->last_stock = $medicine->last_stock ?? 0;

                    return $medicine;
                });

            return $medicineStocks->toArray();
        } catch (\Throwable $th) {
            return [];
        }
    }
}
