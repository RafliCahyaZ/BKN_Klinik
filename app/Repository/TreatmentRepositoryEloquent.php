<?php

namespace App\Repository;

use App\DTO\TreatmentDTO;
use App\Models\Treatment;
use App\Repository\TreatmentRepository;

class TreatmentRepositoryEloquent implements TreatmentRepository
{
    function getAll(): array
    {
        try {
            $treatmentCollections = Treatment::all();

            $formatedTreatments = $treatmentCollections->map(function ($treatment) {
                $formatedTreatment = new TreatmentDTO();
                $formatedTreatment->id = $treatment->id;
                $formatedTreatment->treatmentName = $treatment->treatment_name;
                $formatedTreatment->treatmentCode = $treatment->treatment_code;
                $formatedTreatment->createdAt = $treatment->created_at;
                $formatedTreatment->updatedAt = $treatment->updated_at;
                return $formatedTreatment;
            });

            $treatments = $formatedTreatments->toArray();
            return $treatments;
        } catch (\Throwable $th) {
            return [];
        }
    }

    function insert(TreatmentDTO $treatmentDTO): ?TreatmentDTO
    {
        try {
            $treatment = Treatment::create([
                'treatment_name' => $treatmentDTO->treatmentName,
                'treatment_code' => $treatmentDTO->treatmentCode,
            ]);

            $treatmentDTO->id = $treatment->id;
            $treatmentDTO->createdAt = $treatment->created_at;
            $treatmentDTO->updatedAt = $treatment->updated_at;

            return $treatmentDTO;
        } catch (\Throwable $th) {
            return null;
        }
    }

    function edit(string $id, TreatmentDTO $treatmentDTO): ?TreatmentDTO
    {
        try {
            $updateData = [
                'treatment_name' => $treatmentDTO->treatmentName ?? null,
                'treatment_code' => $treatmentDTO->treatmentCode ?? null,
            ];
            $filteredUpdateData = array_filter($updateData);

            if (!empty($filteredUpdateData)) {
                $edit = Treatment::where('id', $id)
                    ->update($filteredUpdateData);

                if ($edit > 0) {
                    $updatedData = Treatment::find($id);

                    $formatedUpdatedData = new TreatmentDTO();
                    $formatedUpdatedData->id = $updatedData->id;
                    $formatedUpdatedData->treatmentName = $updatedData->treatment_name;
                    $formatedUpdatedData->treatmentCode = $updatedData->treatment_code;
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

    function getById(string $id): ?TreatmentDTO
    {
        try {
            $treatment = Treatment::find($id);
            if (!$treatment) {
                return null;
            }
            
            $formatedTreatment = new TreatmentDTO();
            $formatedTreatment->id = $treatment->id;
            $formatedTreatment->treatmentCode = $treatment->treatment_code;
            $formatedTreatment->treatmentName = $treatment->treatment_name;
            $formatedTreatment->createdAt = $treatment->created_at;
            $formatedTreatment->updatedAt = $treatment->updated_at;
            return $formatedTreatment;
        } catch (\Throwable $th) {
            return null;
        }
    }

    function getLatestId(): int
    {
        try {
            $latestId = Treatment::max('id');
            if (!$latestId) {
                return 0;
            }
            return $latestId;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    function delete(string $id): bool
    {
        try {
            $treatment = Treatment::find($id);

            if ($treatment) {
                $result = $treatment->delete();
                return $result;
            }

            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }
}

?>