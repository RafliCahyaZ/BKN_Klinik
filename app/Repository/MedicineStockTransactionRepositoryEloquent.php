<?php

namespace App\Repository;

use App\DTO\MedicineStockTransactionDTO;
use App\DTO\UserDTO;
use App\DTO\MedicineDTO;
use App\Models\MedicineStockTransaction;
use App\Repository\MedicineStockTransactionRepository;
use App\Type\TransactionType;
use Carbon\Carbon;

class MedicineStockTransactionRepositoryEloquent implements MedicineStockTransactionRepository
{
    protected $medicineRepository;
    protected $userRepository;

    public function __construct()
    {
        $this->medicineRepository = new MedicineRepositoryEloquent();
        $this->userRepository = new UserRepositoryEloquent();
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
            $transactions_collections = MedicineStockTransaction::with(['inventoryOfficer', 'medicine.unit', 'registration.patient', 'registration.doctor'])->get();

            $formated_transactions = $transactions_collections->map(function ($transaction) {
                $formated_transaction = new MedicineStockTransactionDTO();
                $formated_transaction->id = $transaction->id;

                // Map inventory officer to UserDTO
                $inventoryOfficer = new UserDTO();
                if ($transaction->inventoryOfficer) {
                    $inventoryOfficer = UserRepositoryEloquent::mapUserModelToUserDto($transaction->inventoryOfficer);
                }
                $formated_transaction->inventoryOfficer = $inventoryOfficer;

                // Map medicine to MedicineDTO
                $medicine = new MedicineDTO();
                if ($transaction->medicine) {
                    $medicine = MedicineRepositoryEloquent::mapMedicineModelToMedicineDto($transaction->medicine);
                }
                $formated_transaction->medicine = $medicine;

                // Map registration to RegistrationDTO
                $registration = null;
                if ($transaction->registration) {
                    $registration = RegistrationRepositoryEloquent::mapRegistrationModelToRegistrationDto($transaction->registration);
                }
                $formated_transaction->registration = $registration;

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
            $medicineId = $medicineStockTransactionDTO->medicine->id;
            $beginningStock = $this->getBeginningStock($medicineId);
            $quantity = $medicineStockTransactionDTO->quantity;
            $transactionType = $medicineStockTransactionDTO->type;
            $lastStock = $this->calculateLastStock($beginningStock, $quantity, $transactionType);

            $transaction = MedicineStockTransaction::create([
                'inventory_officer_id' => $medicineStockTransactionDTO->inventoryOfficer->id,
                'medicine_id' => $medicineStockTransactionDTO->medicine->id,
                'registration_id' => $medicineStockTransactionDTO->registration ? $medicineStockTransactionDTO->registration->id : null,
                'type' => $medicineStockTransactionDTO->type,
                'quantity' => $medicineStockTransactionDTO->quantity,
                'beginning_stock' => $beginningStock,
                'last_stock' => $lastStock,
                'expired_date' => $medicineStockTransactionDTO->expiredDate ? $medicineStockTransactionDTO->expiredDate->toDateString() : null,
                'notes' => $medicineStockTransactionDTO->notes,
            ]);

            dd($transaction);

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
                $medicineId = $dto->medicine->id;
                $beginningStock = $this->getBeginningStock($medicineId);
                $quantity = $dto->quantity;
                $transactionType = $dto->type;
                $lastStock = $this->calculateLastStock($beginningStock, $quantity, $transactionType);
                return [
                    'inventory_officer_id' => $dto->inventoryOfficer->id,
                    'medicine_id' => $dto->medicine->id,
                    'registration_id' => $dto->registration ? $dto->registration->id : null,
                    'type' => $dto->type,
                    'quantity' => $dto->quantity,
                    'beginning_stock' => $beginningStock,
                    'last_stock' => $lastStock,
                    'expired_date' => $dto->expiredDate ? $dto->expiredDate->toDateString() : null,
                    'notes' => $dto->notes,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
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
                'inventory_officer_id' => $medicineStockTransactionDTO->inventoryOfficer->id ?? null,
                'medicine_id' => $medicineStockTransactionDTO->medicine->id ?? null,
                'registration_id' => $medicineStockTransactionDTO->registration ? $medicineStockTransactionDTO->registration->id : null,
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
                    $updatedData = MedicineStockTransaction::with(['inventoryOfficer', 'medicine.unit', 'registration.patient', 'registration.doctor'])->find($id);

                    $formatedUpdatedData = new MedicineStockTransactionDTO();
                    $formatedUpdatedData->id = $updatedData->id;

                    // Map inventory officer to UserDTO
                    $inventoryOfficer = new UserDTO();
                    if ($updatedData->inventoryOfficer) {
                        $inventoryOfficer = UserRepositoryEloquent::mapUserModelToUserDto($updatedData->inventoryOfficer);
                    }
                    $formatedUpdatedData->inventoryOfficer = $inventoryOfficer;

                    // Map medicine to MedicineDTO
                    $medicine = new MedicineDTO();
                    if ($updatedData->medicine) {
                        $medicine = MedicineRepositoryEloquent::mapMedicineModelToMedicineDto($updatedData->medicine);
                    }
                    $formatedUpdatedData->medicine = $medicine;

                    // Map registration to RegistrationDTO
                    $registration = null;
                    if ($updatedData->registration) {
                        $registration = RegistrationRepositoryEloquent::mapRegistrationModelToRegistrationDto($updatedData->registration);
                    }
                    $formatedUpdatedData->registration = $registration;

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
            $transaction = MedicineStockTransaction::with(['inventoryOfficer', 'medicine.unit', 'registration.patient', 'registration.doctor'])->find($id);

            if (!$transaction) {
                return null;
            }

            $formatedTransaction = new MedicineStockTransactionDTO();
            $formatedTransaction->id = $transaction->id;

            // Map inventory officer to UserDTO
            $inventoryOfficer = new UserDTO();
            if ($transaction->inventoryOfficer) {
                $inventoryOfficer = UserRepositoryEloquent::mapUserModelToUserDto($transaction->inventoryOfficer);
            }
            $formatedTransaction->inventoryOfficer = $inventoryOfficer;

            // Map medicine to MedicineDTO
            $medicine = new MedicineDTO();
            if ($transaction->medicine) {
                $medicine = MedicineRepositoryEloquent::mapMedicineModelToMedicineDto($transaction->medicine);
            }
            $formatedTransaction->medicine = $medicine;

            // Map registration to RegistrationDTO
            $registration = null;
            if ($transaction->registration) {
                $registration = RegistrationRepositoryEloquent::mapRegistrationModelToRegistrationDto($transaction->registration);
            }
            $formatedTransaction->registration = $registration;

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
            $transactions_collections = MedicineStockTransaction::with(['inventoryOfficer', 'medicine.unit', 'registration.patient', 'registration.doctor'])
                ->where('medicine_id', $medicineId)
                ->get();

            $formated_transactions = $transactions_collections->map(function ($transaction) {
                $formated_transaction = new MedicineStockTransactionDTO();
                $formated_transaction->id = $transaction->id;

                // Map inventory officer to UserDTO
                $inventoryOfficer = new UserDTO();
                if ($transaction->inventoryOfficer) {
                    $inventoryOfficer = UserRepositoryEloquent::mapUserModelToUserDto($transaction->inventoryOfficer);
                }
                $formated_transaction->inventoryOfficer = $inventoryOfficer;

                // Map medicine to MedicineDTO
                $medicine = new MedicineDTO();
                if ($transaction->medicine) {
                    $medicine = MedicineRepositoryEloquent::mapMedicineModelToMedicineDto($transaction->medicine);
                }
                $formated_transaction->medicine = $medicine;

                // Map registration to RegistrationDTO
                $registration = null;
                if ($transaction->registration) {
                    $registration = RegistrationRepositoryEloquent::mapRegistrationModelToRegistrationDto($transaction->registration);
                }
                $formated_transaction->registration = $registration;

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
            $transactions_collections = MedicineStockTransaction::with(['inventoryOfficer', 'medicine.unit', 'registration.patient', 'registration.doctor'])
                ->where('type', $type->value)
                ->get();

            $formated_transactions = $transactions_collections->map(function ($transaction) {
                $formated_transaction = new MedicineStockTransactionDTO();
                $formated_transaction->id = $transaction->id;

                // Map inventory officer to UserDTO
                $inventoryOfficer = new UserDTO();
                if ($transaction->inventoryOfficer) {
                    $inventoryOfficer = UserRepositoryEloquent::mapUserModelToUserDto($transaction->inventoryOfficer);
                }
                $formated_transaction->inventoryOfficer = $inventoryOfficer;

                // Map medicine to MedicineDTO
                $medicine = new MedicineDTO();
                if ($transaction->medicine) {
                    $medicine = MedicineRepositoryEloquent::mapMedicineModelToMedicineDto($transaction->medicine);
                }
                $formated_transaction->medicine = $medicine;

                // Map registration to RegistrationDTO
                $registration = null;
                if ($transaction->registration) {
                    $registration = RegistrationRepositoryEloquent::mapRegistrationModelToRegistrationDto($transaction->registration);
                }
                $formated_transaction->registration = $registration;

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
