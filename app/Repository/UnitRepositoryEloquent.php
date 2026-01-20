<?php

namespace App\Repository;

use App\DTO\UnitDTO;
use App\Models\Unit;
use App\Repository\UnitRepository;

class UnitRepositoryEloquent implements UnitRepository
{
    function getAll(): array
    {
        try {
            $units_collections = Unit::all();

            $formated_units = $units_collections->map(function ($unit) {
                $formated_unit = new UnitDTO();
                $formated_unit->id = $unit->id;
                $formated_unit->name = $unit->name;
                $formated_unit->createdAt = $unit->created_at;
                $formated_unit->updatedAt = $unit->updated_at;
                return $formated_unit;
            });

            $units = $formated_units->toArray();

            return $units;
        } catch (\Exception $e) {
            return [];
        }
    }


    function insert(UnitDTO $unitDTO): ?UnitDTO
    {
        try {
            $unit = Unit::create([
                'name' => $unitDTO->name,
            ]);

            $unitDTO->id = $unit->id;
            $unitDTO->createdAt = $unit->created_at;
            $unitDTO->updatedAt = $unit->updated_at;

            return $unitDTO;
        } catch (\Exception $e) {
            return null;
        }
    }

    function edit(string $id, UnitDTO $unitDTO): ?UnitDTO
    {
        try {
            $updateData = [
                'name' => $unitDTO->name ?? null,
            ];
            $filteredUpdateData = array_filter($updateData);
            $unitId = $id;

            if (!empty($filteredUpdateData)) {

                $edit = Unit::where('id', $unitId)
                    ->update($filteredUpdateData);

                if ($edit > 0) {
                    $updatedData = Unit::find($id);

                    $formatedUpdatedData = new UnitDTO();
                    $formatedUpdatedData->id = $updatedData->id;
                    $formatedUpdatedData->name = $updatedData->name;
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

    function getById(string $id): ?UnitDTO
    {
        try {
            $unit = Unit::find($id);

            if (!$unit) {
                return null;
            }

            $formatedUnit = new UnitDTO();
            $formatedUnit->id = $unit->id;
            $formatedUnit->name = $unit->name;
            $formatedUnit->createdAt = $unit->created_at;
            $formatedUnit->updatedAt = $unit->updated_at;
            return $formatedUnit;
        } catch (\Exception $e) {
            return null;
        }
    }

    function getLatestId(): int
    {
        try {
            $latestId = Unit::max('id');
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
            $unit = Unit::find($id);

            if ($unit) {
                $result = $unit->delete();
                return $result;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}

?>
