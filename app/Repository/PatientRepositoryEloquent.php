<?php

namespace App\Repository;

use App\DTO\PatientDTO;
use App\Models\Patient;
use Carbon\Carbon;

class PatientRepositoryEloquent implements PatientRepository
{
    public function getAll(): array
    {
        try {
            $patiensCollections = Patient::all();

            $formatedPatients = $patiensCollections->map(function ($patient) {
                $formatedPatient = new PatientDTO;
                $formatedPatient->id = $patient->id;
                $formatedPatient->name = $patient->fullname;
                $formatedPatient->address = $patient->address;
                $formatedPatient->age = $patient->getAgeAttribute();
                $carbonDateOfBirth = Carbon::parse($patient->date_of_birth);
                $formatedPatient->dateOfBirth = $carbonDateOfBirth;
                $formatedPatient->telpNumber = $patient->telp_number;
                $formatedPatient->gender = ($patient->gender === 'female') ? 'F' : 'M';
                $formatedPatient->history = $patient->history;
                $formatedPatient->medicalRecordFileUrl = $patient->medical_record_file_url;
                $formatedPatient->medicalRecordNumber = $patient->medical_record_number;
                $formatedPatient->createdAt = $patient->created_at;
                $formatedPatient->updatedAt = $patient->updated_at;

                return $formatedPatient;
            });

            $patients = $formatedPatients->toArray();

            return $patients;
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function insert(PatientDTO $patientDTO): ?PatientDTO
    {
        try {
            $patient = Patient::create([
                'fullname' => $patientDTO->name,
                'gender' => $patientDTO->gender,
                'address' => $patientDTO->address,
                'date_of_birth' => $patientDTO->dateOfBirth->toDateString(),
                'telp_number' => $patientDTO->telpNumber,
                'history' => $patientDTO->history,
                'medical_record_file_url' => $patientDTO->medicalRecordFileUrl,
                'medical_record_number' => $patientDTO->medicalRecordNumber,
            ]);

            $patientDTO->id = $patient->id;
            $patientDTO->age = $patient->getAgeAttribute();
            $patientDTO->createdAt = $patient->created_at;
            $patientDTO->updatedAt = $patient->updated_at;

            return $patientDTO;
        } catch (\Throwable $th) {
            // throw $th;
            return null;
        }
    }

    public function edit(string $id, PatientDTO $patientDTO): ?PatientDTO
    {
        try {
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

            if (! empty($filteredUpdateData)) {

                $edit = Patient::where('id', $patientId)
                    ->update($filteredUpdateData);

                if ($edit > 0) {
                    $updatedData = Patient::find($id);

                    $formatedUpdatedData = new PatientDTO;
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
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function getById(string $id): ?PatientDTO
    {
        try {
            $patient = Patient::find($id);

            if (! $patient) {
                return null;
            }

            $formatedpatient = new PatientDTO;
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
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function getLatestId(): int
    {
        try {
            $latestId = Patient::max('id');
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
            $patient = Patient::find($id);

            if ($patient) {
                $result = $patient->delete();

                return $result;
            }

            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
