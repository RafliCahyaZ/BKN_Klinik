<?php

namespace App\Repository;

use App\DTO\TreatmentDTO;
use App\Models\Treatment;
use App\Repository\TreatmentRepository;

class TreatmentRepositoryEloquent implements TreatmentRepository
{
    function getAll(): array
    {
        $treatment_collections = Treatment::all();

        $formated_treatments = $treatment_collections->map(function ($treatment) {
            $formated_treatment = new TreatmentDTO();
            $formated_treatment->id = $treatment->id;
            $formated_treatment->treatmentName = $treatment->treatment_name;
            $formated_treatment->treatmentCode = $treatment->treatment_code;
            $formated_treatment->createdAt = $treatment->created_at;
            $formated_treatment->updatedAt = $treatment->updated_at;
            return $formated_treatment;
        });

        $treatments = $formated_treatments->toArray();
        return $treatments;
    }

    function insert(TreatmentDTO $treatmentDTO): ?TreatmentDTO
    {
        $treatment = Treatment::create([
            'treatment_name' => $treatmentDTO->treatmentName,
            'treatment_code' => $treatmentDTO->treatmentCode,
        ]);

        $treatmentDTO->id = $treatment->id;
        $treatmentDTO->createdAt = $treatment->created_at;
        $treatmentDTO->updatedAt = $treatment->updated_at;

        return $treatmentDTO;
    }

    function edit(string $id, TreatmentDTO $treatmentDTO): ?TreatmentDTO
    {
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
    }

    function getById(string $id): ?TreatmentDTO
    {
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
    }

    function getLatestId(): int
    {
        $latestId = Treatment::max('id');
        if (!$latestId) {
            return 0;
        }
        return $latestId;
    }

    function delete(string $id): bool
    {
        $treatment = Treatment::find($id);

        if ($treatment) {
            $result = $treatment->delete();
            return $result;
        }

        return false;
    }
}

?>