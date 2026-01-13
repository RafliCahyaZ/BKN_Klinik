<?php

namespace App\Repository;

use App\DTO\DiagnosisDTO;
use App\Models\Diagnosis;
use App\Repository\DiagnosisRepository;

class DiagnosisRepositoryEloquent implements DiagnosisRepository
{
    function getAll(): array
    {
        $diagnosis_collections = Diagnosis::all();

        $formated_diagnosis = $diagnosis_collections->map(function ($patient) {
            $formated_patient = new DiagnosisDTO();
            $formated_patient->id = $patient->id;
            $formated_patient->icdCode = $patient->icd_code;
            $formated_patient->diseaseName = $patient->disease_name;
            $formated_patient->createdAt = $patient->created_at;
            $formated_patient->updatedAt = $patient->updated_at;
        });

        $diagnoses = $formated_diagnosis->toArray();
        return $diagnoses;
    }

    function insert(DiagnosisDTO $diagnosisDTO): ?DiagnosisDTO
    {
        $diagnosis = Diagnosis::create([
            'icd_code' => $diagnosisDTO->icdCode,
            'disease_name' => $diagnosisDTO->diseaseName,
        ]);

        $diagnosisDTO->id = $diagnosis->id;
        $diagnosisDTO->createdAt = $diagnosis->created_at;
        $diagnosisDTO->updatedAt = $diagnosis->updated_at;

        return $diagnosisDTO;
    }

    function edit(string $id, DiagnosisDTO $diagnosisDTO): ?DiagnosisDTO
    {
        $updateData = [
            'icd_code' => $diagnosisDTO->icdCode ?? null,
            'disease_name' => $diagnosisDTO->diseaseName ?? null,
        ];
        $filteredUpdateData = array_filter($updateData);
        $diagnosisId = $id;

        if (!empty($filteredUpdateData)) {

            $edit = Diagnosis::where('id', $diagnosisId)
                ->update($filteredUpdateData);

            if ($edit > 0) {
                $updatedData = Diagnosis::find($id);

                $formatedUpdatedData = new DiagnosisDTO();
                $formatedUpdatedData->id = $updatedData->id;
                $formatedUpdatedData->icdCode = $updatedData->icd_code;
                $formatedUpdatedData->diseaseName = $updatedData->disease_name;
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

    function getById(string $id): ?DiagnosisDTO
    {
        $diagnosis = Diagnosis::find($id);
        if (!$diagnosis) {
            return null;
        }
        $formatedDiagnosis = new DiagnosisDTO();
        $formatedDiagnosis->id = $diagnosis->id;
        $formatedDiagnosis->icdCode = $diagnosis->icd_code;
        $formatedDiagnosis->diseaseName = $diagnosis->disease_name;
        $formatedDiagnosis->createdAt = $diagnosis->created_at;
        $formatedDiagnosis->updatedAt = $diagnosis->updated_at;
        return $formatedDiagnosis;
    }


    function getLatestId(): int
    {
        $latestId = Diagnosis::max('id');
        if (!$latestId) {
            return 0;
        }
        return $latestId;
    }

    function delete(string $id): bool
    {
        $diagnosis = Diagnosis::find($id);

        if ($diagnosis) {
            $result = $diagnosis->delete();
            return $result;
        }

        return false;
    }
}



?>
