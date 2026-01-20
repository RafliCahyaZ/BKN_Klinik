<?php

namespace App\Repository;

use App\DTO\MedicineDTO;
use App\Models\Medicine;
use App\Repository\MedicineRepository;

class MedicineRepositoryEloquent implements MedicineRepository
{
    function getAll(): array
    {
        try {
            $medicines_collections = Medicine::with(['unit'])->get();

            $formated_medicines = $medicines_collections->map(function ($medicine) {
                $formated_medicine = new MedicineDTO();
                $formated_medicine->id = $medicine->id;
                $formated_medicine->name = $medicine->name;
                $formated_medicine->stock = $medicine->stock;
                $formated_medicine->unit->id = $medicine->unit->id;
                $formated_medicine->unit->name = $medicine->unit->name;
                $formated_medicine->createdAt = $medicine->created_at;
                $formated_medicine->updatedAt = $medicine->updated_at;
                return $formated_medicine;
            });

            $medicines = $formated_medicines->toArray();

            return $medicines;
        } catch (\Exception $e) {
            return [];
        }
    }


    function insert(MedicineDTO $medicineDTO): ?MedicineDTO
    {
        try {
            $medicine = Medicine::create([
                'name' => $medicineDTO->name,
                'stock' => $medicineDTO->stock,
                'unit_id' => $medicineDTO->unit->id,
            ]);

            $medicineDTO->id = $medicine->id;
            $medicineDTO->createdAt = $medicine->created_at;
            $medicineDTO->updatedAt = $medicine->updated_at;

            return $medicineDTO;
        } catch (\Exception $e) {
            return null;
        }
    }

    function edit(string $id, MedicineDTO $medicineDTO): ?MedicineDTO
    {
        try {
            $updateData = [
                'name' => $medicineDTO->name ?? null,
                'stock' => $medicineDTO->stock ?? null,
                'unit_id' => $medicineDTO->unit->id ?? null,
            ];
            $filteredUpdateData = array_filter($updateData);
            $medicineId = $id;

            if (!empty($filteredUpdateData)) {

                $edit = Medicine::where('id', $medicineId)
                    ->update($filteredUpdateData);

                if ($edit > 0) {
                    $updatedData = Medicine::find($id);

                    $formatedUpdatedData = new MedicineDTO();
                    $formatedUpdatedData->id = $updatedData->id;
                    $formatedUpdatedData->name = $updatedData->name;
                    $formatedUpdatedData->stock = $updatedData->stock;
                    $formatedUpdatedData->unit->id = $updatedData->unit->id;
                    $formatedUpdatedData->unit->name = $updatedData->unit->name;
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

    function getById(string $id): ?MedicineDTO
    {
        try {
            $medicine = Medicine::with(['unit'])->find($id);

            if (!$medicine) {
                return null;
            }

            $formatedMedicine = new MedicineDTO();
            $formatedMedicine->id = $medicine->id;
            $formatedMedicine->name = $medicine->name;
            $formatedMedicine->stock = $medicine->stock;
            $formatedMedicine->unit->id = $medicine->unit->id;
            $formatedMedicine->unit->name = $medicine->unit->name;
            $formatedMedicine->createdAt = $medicine->created_at;
            $formatedMedicine->updatedAt = $medicine->updated_at;
            return $formatedMedicine;
        } catch (\Exception $e) {
            return null;
        }
    }

    function getLatestId(): int
    {
        try {
            $latestId = Medicine::max('id');
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
            $medicine = Medicine::find($id);

            if ($medicine) {
                $result = $medicine->delete();
                return $result;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
