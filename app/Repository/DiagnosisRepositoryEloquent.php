<?php

namespace App\Repository;

use App\DTO\DiagnosisDTO;
use App\Models\Diagnosis;
use App\Repository\DiagnosisRepository;

class DiagnosisRepositoryEloquent implements DiagnosisRepository
{
    function getAll(): array
    {
        try {
            $diagnosis_collections = Diagnosis::all();

            $formated_diagnosis = $diagnosis_collections->map(function ($patient) {
                $diagnosisDto = new DiagnosisDTO();
                $diagnosisDto->id = $patient->id;
                $diagnosisDto->icdCode = $patient->icd_code;
                $diagnosisDto->diseaseName = $patient->disease_name;
                $diagnosisDto->createdAt = $patient->created_at;
                $diagnosisDto->updatedAt = $patient->updated_at;

                return $diagnosisDto;
            });

            $diagnoses = $formated_diagnosis->toArray();
            return $diagnoses;
        } catch (\Throwable $th) {
            return [];
        }
    }

    function insert(DiagnosisDTO $diagnosisDTO): ?DiagnosisDTO
    {
        try {
            $diagnosis = Diagnosis::create([
                'icd_code' => $diagnosisDTO->icdCode,
                'disease_name' => $diagnosisDTO->diseaseName,
            ]);

            $diagnosisDTO->id = $diagnosis->id;
            $diagnosisDTO->createdAt = $diagnosis->created_at;
            $diagnosisDTO->updatedAt = $diagnosis->updated_at;

            return $diagnosisDTO;
        } catch (\Throwable $th) {
            return new DiagnosisDTO();
        }
    }

    function edit(string $id, DiagnosisDTO $diagnosisDTO): ?DiagnosisDTO
    {
        try {
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
        } catch (\Throwable $th) {
            return null;
        }
    }

    function getById(string $id): ?DiagnosisDTO
    {
        try {
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
        } catch (\Throwable $th) {
            return null;
        }
    }


    function getLatestId(): int
    {
        try {
            $latestId = Diagnosis::max('id');
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
            $diagnosis = Diagnosis::find($id);

            if ($diagnosis) {
                $result = $diagnosis->delete();
                return $result;
            }

            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
