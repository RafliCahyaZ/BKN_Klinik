<?php

namespace App\Repository;

use App\DTO\MedicineDTO;
use App\DTO\UnitDTO;
use App\Models\Medicine;

class MedicineRepositoryEloquent implements MedicineRepository
{
    public static function mapMedicineModelToMedicineDto($medicine): MedicineDTO
    {
        $unitDTO = new UnitDTO;
        $unitDTO->id = $medicine->unit->id;
        $unitDTO->name = $medicine->unit->name;

        $medicineDTO = new MedicineDTO;
        $medicineDTO->id = $medicine->id;
        $medicineDTO->name = $medicine->name;
        $medicineDTO->stock = $medicine->stock;
        $medicineDTO->unit = $unitDTO;
        $medicineDTO->createdAt = $medicine->created_at;
        $medicineDTO->updatedAt = $medicine->updated_at;

        return $medicineDTO;
    }

    public function getAll(): array
    {
        try {
            $medicines_collections = Medicine::with(['unit'])->get();

            $formated_medicines = $medicines_collections->map(function ($medicine) {
                return self::mapMedicineModelToMedicineDto($medicine);
            });

            $medicines = $formated_medicines->toArray();

            return $medicines;
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function insert(MedicineDTO $medicineDTO): ?MedicineDTO
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

    public function edit(string $id, MedicineDTO $medicineDTO): ?MedicineDTO
    {
        try {
            $updateData = [
                'name' => $medicineDTO->name ?? null,
                'stock' => $medicineDTO->stock ?? null,
                'unit_id' => $medicineDTO->unit->id ?? null,
            ];

            $filteredUpdateData = array_filter($updateData);
            $medicineId = $id;

            if (! empty($filteredUpdateData)) {

                $edit = Medicine::where('id', $medicineId)
                    ->update($filteredUpdateData);

                if ($edit > 0) {
                    $updatedData = Medicine::with(['unit'])->find($id);

                    $unitDTO = new UnitDTO;
                    $unitDTO->id = $updatedData->unit->id;
                    $unitDTO->name = $updatedData->unit->name;
                    $formatedUpdatedData = new MedicineDTO;
                    $formatedUpdatedData->id = $updatedData->id;
                    $formatedUpdatedData->name = $updatedData->name;
                    $formatedUpdatedData->stock = $updatedData->stock;
                    $formatedUpdatedData->unit = $unitDTO;
                    $formatedUpdatedData->createdAt = $updatedData->created_at;
                    $formatedUpdatedData->updatedAt = $updatedData->updated_at;

                    return $formatedUpdatedData;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function getById(string $id): ?MedicineDTO
    {
        try {
            $medicine = Medicine::with(['unit'])->find($id);

            if (! $medicine) {
                return null;
            }

            return self::mapMedicineModelToMedicineDto($medicine);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getLatestId(): int
    {
        try {
            $latestId = Medicine::max('id');
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
            $medicine = Medicine::find($id);

            if ($medicine) {
                $result = $medicine->delete();

                return $result;
            }

            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
