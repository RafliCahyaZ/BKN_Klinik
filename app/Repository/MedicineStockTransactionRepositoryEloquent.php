<?php

namespace App\Repository;

use App\DTO\MedicineStockTransactionDTO;
use App\DTO\UserDTO;
use App\DTO\MedicineDTO;
use App\Models\Medicine;
use App\Models\MedicineStockTransaction;
use App\Repository\MedicineStockTransactionRepository;
use App\Type\TransactionType;
use Carbon\Carbon;

class MedicineStockTransactionRepositoryEloquent implements MedicineStockTransactionRepository
{
    public function __construct(
        private MedicineRepository $medicineRepository
    ) {}

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
            } else if ($transactionType == TransactionType::OUT || $transactionType == TransactionType::EXPIRED) {
                return $beginningStock - $quantity;
            } else if ($transactionType == TransactionType::ADJUSTMENT) {
                return $quantity;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    function getAll(): array
    {
        try {
            $transactionsCollections = MedicineStockTransaction::with(['inventoryOfficer', 'medicine.unit', 'registration.patient', 'registration.doctor'])->get();

            $formatedTransactions = $transactionsCollections->map(function ($transaction) {
                $formatedTransaction = new MedicineStockTransactionDTO();
                $formatedTransaction->id = $transaction->id;

                $inventoryOfficer = new UserDTO();
                if ($transaction->inventoryOfficer) {
                    $inventoryOfficer = UserRepositoryEloquent::mapUserModelToUserDto($transaction->inventoryOfficer);
                }
                $formatedTransaction->inventoryOfficer = $inventoryOfficer;

                $medicine = new MedicineDTO();
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
                $formatedTransaction->beginningStock = $transaction->beginning_stock;
                $formatedTransaction->lastStock = $transaction->last_stock;
                $formatedTransaction->expiredDate = $transaction->expired_date ? Carbon::parse($transaction->expired_date) : null;
                $formatedTransaction->notes = $transaction->notes;
                $formatedTransaction->createdAt = Carbon::parse($transaction->created_at);
                $formatedTransaction->updatedAt = Carbon::parse($transaction->updated_at);
                return $formatedTransaction;
            });

            $transactions = $formatedTransactions->toArray();

            return $transactions;
        } catch (\Exception $e) {
            return [];
        }
    }


    function insert(MedicineStockTransactionDTO $medicineStockTransactionDTO): ?MedicineStockTransactionDTO
    {
        try {
            $medicine = $this->medicineRepository->getById($medicineStockTransactionDTO->medicine->id);
            $medicineId = $medicineStockTransactionDTO->medicine->id;
            $beginningStock = $medicine->stock;
            $quantity = $medicineStockTransactionDTO->quantity;
            $transactionType = $medicineStockTransactionDTO->type;
            $lastStock = $this->calculateLastStock($beginningStock, $quantity, $transactionType);

            if ($medicineStockTransactionDTO->type == TransactionType::IN) {
                $medicine->stock += $medicineStockTransactionDTO->quantity;
            } else  if ($medicineStockTransactionDTO->type == TransactionType::OUT) {
                if ($medicine->stock < $medicineStockTransactionDTO->quantity) {
                    return null;
                }
                $medicine->stock -= $medicineStockTransactionDTO->quantity;
            } else if ($medicineStockTransactionDTO->type == TransactionType::ADJUSTMENT) {
                $medicine->stock = $medicineStockTransactionDTO->quantity;
            }

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

            $this->medicineRepository->edit($medicineId, $medicine);

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

                if ($lastStock < 0) {
                    throw new \Exception("Insufficient stock for medicine ID: " . $medicineId);
                }
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

                    $inventoryOfficer = new UserDTO();
                    if ($updatedData->inventoryOfficer) {
                        $inventoryOfficer = UserRepositoryEloquent::mapUserModelToUserDto($updatedData->inventoryOfficer);
                    }
                    $formatedUpdatedData->inventoryOfficer = $inventoryOfficer;

                    $medicine = new MedicineDTO();
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
            $transactionsCollections = MedicineStockTransaction::with(['inventoryOfficer', 'medicine.unit', 'registration.patient', 'registration.doctor'])
                ->where('medicine_id', $medicineId)
                ->get();

            $formated_transactions = $transactionsCollections->map(function ($transaction) {
                $formated_transaction = new MedicineStockTransactionDTO();
                $formated_transaction->id = $transaction->id;

                $inventoryOfficer = new UserDTO();
                if ($transaction->inventoryOfficer) {
                    $inventoryOfficer = UserRepositoryEloquent::mapUserModelToUserDto($transaction->inventoryOfficer);
                }
                $formated_transaction->inventoryOfficer = $inventoryOfficer;

                $medicine = new MedicineDTO();
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
            $transactionsCollections = MedicineStockTransaction::with(['inventoryOfficer', 'medicine.unit', 'registration.patient', 'registration.doctor'])
                ->where('type', $type->value)
                ->get();

            $formatedTransactions = $transactionsCollections->map(function ($transaction) {
                
                $inventoryOfficer = new UserDTO();
                if ($transaction->inventoryOfficer) {
                    $inventoryOfficer = UserRepositoryEloquent::mapUserModelToUserDto($transaction->inventoryOfficer);
                }
                
                $medicine = new MedicineDTO();
                if ($transaction->medicine) {
                    $medicine = MedicineRepositoryEloquent::mapMedicineModelToMedicineDto($transaction->medicine);
                }
                
                $registration = null;
                if ($transaction->registration) {
                    $registration = RegistrationRepositoryEloquent::mapRegistrationModelToRegistrationDto($transaction->registration);
                }
                    
                $formatedTransaction = new MedicineStockTransactionDTO();
                $formatedTransaction->id = $transaction->id;
                $formatedTransaction->inventoryOfficer = $inventoryOfficer;
                $formatedTransaction->medicine = $medicine;
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
            });

            return $formatedTransactions->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getByPeriod($startDate, $endDate): array
    {

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
        
    }
}
