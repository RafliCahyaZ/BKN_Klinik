<?php

namespace App\Repository;

use App\DTO\PatientDTO;
use App\Models\Patient;
use App\Repository\PatientRepository;
use Carbon\Carbon;

class PatientRepositoryEloquent implements PatientRepository
{
    function getAll(): array
    {
        $patiens_collections = Patient::all();

        $formated_patients = $patiens_collections->map(function ($patient) {
            $formated_patient = new PatientDTO();
            $formated_patient->id = $patient->id;
            $formated_patient->name = $patient->fullname;
            $formated_patient->address = $patient->address;
            $formated_patient->age = $patient->getAgeAttribute();
            $carbonDateOfBirth = Carbon::parse($patient->date_of_birth);
            $formated_patient->dateOfBirth = $carbonDateOfBirth;
            $formated_patient->telpNumber = $patient->telp_number;
            $formated_patient->gender = $patient->id;
            $formated_patient->history = $patient->history;
            $formated_patient->medicalRecordFileUrl = $patient->medical_record_file_url;
            $formated_patient->medicalRecordNumber = $patient->medical_record_number;
            $formated_patient->createdAt = $patient->created_at;
            $formated_patient->updatedAt = $patient->updated_at;
        });

        $patients = $formated_patients->toArray();
        return $patients;
    }


    function insert(PatientDTO $patientDTO): ?PatientDTO
    {
        $patient = Patient::create([
            'fullname' => $patientDTO->name,
            'gender' => $patientDTO->gender,
            'address' => $patientDTO->address,
            'date_of_birth' => $patientDTO->dateOfBirth->toDateString(),
            'telp_number' => $patientDTO->telpNumber,
            'history' => $patientDTO->history,
            'medical_record_file_url' => $patientDTO->medicalRecordFileUrl,
            'medical_record_number' => $patientDTO->medicalRecordNumber
        ]);

        $patientDTO->id = $patient->id;
        $patientDTO->age = $patient->getAgeAttribute();
        $patientDTO->createdAt = $patient->created_at;
        $patientDTO->updatedAt = $patient->updated_at;

        return $patientDTO;
    }

    function edit(string $id, PatientDTO $patientDTO): ?PatientDTO
    {
        $updateData = [
            'fullname' => $patientDTO->name ?? null,
            'gender' => $patientDTO->gender ?? null,
            'address' => $patientDTO->address ?? null,
            'date_of_birth' => $patientDTO->dateOfBirth ?? null,
            'telp_number' => $patientDTO->telpNumber ?? null,
            'history' => $patientDTO->history ?? null,
            'medical_record_file_url' => $patientDTO->medicalRecordFileUrl ?? null,
            'medical_record_number' => $patientDTO->medicalRecordNumber ?? null,
        ];
        $filteredUpdateData = array_filter($updateData);
        $patientId = $id;

        if (!empty($filteredUpdateData)) {

            $edit = Patient::where('id', $patientId)
                ->update($filteredUpdateData);

            if ($edit > 0) {
                $updatedData = Patient::find($id);

                $formatedUpdatedData = new PatientDTO();
                $formatedUpdatedData->id = $updatedData->id;
                $formatedUpdatedData->name = $updatedData->fullname;
                $formatedUpdatedData->address = $updatedData->address;
                $formatedUpdatedData->age = $updatedData->getAgeAttribute();
                $formatedUpdatedData->dateOfBirth = Carbon::parse($updatedData->date_of_birth);
                $formatedUpdatedData->telpNumber = $updatedData->telp_number;
                $formatedUpdatedData->gender = $updatedData->gender;
                $formatedUpdatedData->history = $updatedData->history;
                $formatedUpdatedData->medicalRecordFileUrl = $updatedData->medical_record_file_url;
                $formatedUpdatedData->medicalRecordNumber = $updatedData->medical_record_number;
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

    function getById(string $id): ?PatientDTO
    {
        $patient = Patient::find($id);

        if (!$patient) {
            return null;
        }

        $formatedpatient = new PatientDTO();
        $formatedpatient->id = $patient->id;
        $formatedpatient->name = $patient->fullname;
        $formatedpatient->address = $patient->address;
        $formatedpatient->age = $patient->getAgeAttribute();
        $formatedpatient->dateOfBirth = Carbon::parse($patient->date_of_birth);
        $formatedpatient->telpNumber = $patient->telp_number;
        $formatedpatient->gender = $patient->gender;
        $formatedpatient->history = $patient->history;
        $formatedpatient->medicalRecordFileUrl = $patient->medical_record_file_url;
        $formatedpatient->medicalRecordNumber = $patient->medical_record_number;
        $formatedpatient->createdAt = $patient->created_at;
        $formatedpatient->updatedAt = $patient->updated_at;
        return $formatedpatient;
    }


    function getLatestId(): int
    {
        $latestId = Patient::max('id');
        if (!$latestId) {
            return 0;
        }
        return $latestId;
    }

    function delete(string $id): bool
    {
        $patient = Patient::find($id);

        if ($patient) {
            $result = $patient->delete();
            return $result;
        }

        return false;
    }
}

?>
