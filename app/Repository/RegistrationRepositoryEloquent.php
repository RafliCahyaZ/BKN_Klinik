<?php

namespace App\Repository;

use App\DTO\PatientDTO;
use App\DTO\RegistrationDTO;
use App\DTO\UserDTO;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class RegistrationRepositoryEloquent implements RegistrationRepository
{
    function getAll(): array
    {
        try {
            $registrationsCollections = Registration::with(['patient', 'doctor'])->get();

            $formatedRegistrations = $registrationsCollections->map(function ($registration) {
                $formatedRegistration = new RegistrationDTO();
                $formatedRegistration->id = $registration->id;
                
                $patient = new PatientDTO();
                $patient->id = $registration->patient->id;
                
                $doctor = new UserDTO();
                if ($registration->doctor) {
                    $doctor->id = $registration->doctor->id;
                }

                $formatedRegistration->patient = $patient;
                $formatedRegistration->doctor = $doctor;
                $formatedRegistration->complaint = $registration->complaint;
                $formatedRegistration->receipt = $registration->receipt;
                $formatedRegistration->checkDate = Carbon::parse($registration->check_date);
                $formatedRegistration->createdAt = $registration->created_at;
                $formatedRegistration->updatedAt = $registration->updated_at;
                
                return $formatedRegistration;
            });

            $registrations = $formatedRegistrations->toArray();
            return $registrations;
        } catch (\Exception $e) {
            return [];
        }
    }

    function getById(string $id): ?RegistrationDTO
    {
        try {
            $registration = Registration::with(['patient', 'doctor'])->find($id);

            if (!$registration) {
                return null;
            }

            $formated_registration = new RegistrationDTO();
            $formated_registration->id = $registration->id;
            
            $patient = new PatientDTO();
            $patient->id = $registration->patient->id;

            $doctor = new UserDTO();
            if ($registration->doctor) {
                $doctor->id = $registration->doctor->id;
            }
            
            $formated_registration->patient = $patient;
            $formated_registration->doctor = $doctor;
            $formated_registration->complaint = $registration->complaint;
            $formated_registration->receipt = $registration->receipt;
            $formated_registration->checkDate = Carbon::parse($registration->check_date);
            $formated_registration->createdAt = $registration->created_at;
            $formated_registration->updatedAt = $registration->updated_at;
            
            return $formated_registration;
        } catch (\Exception $e) {
            return null;
        }
    }

    function insert(RegistrationDTO $registrationDTO): ?RegistrationDTO
    {
        try {
            $registration = new Registration();
            $registration->fill([
                'patient_id' => $registrationDTO->patient->id,
                'receipt' => $registrationDTO->receipt,
                'complaint' => $registrationDTO->complaint,
                'check_date' => $registrationDTO->checkDate,
            ]);
            $registration->save();
            
            $registrationDTO->id = $registration->id;
            $registrationDTO->receipt = $registration->receipt;
            $registrationDTO->complaint = $registration->complaint;
            $registrationDTO->checkDate = Carbon::parse($registration->check_date);
            $registrationDTO->createdAt = $registration->created_at;
            $registrationDTO->updatedAt = $registration->updated_at;

            return $registrationDTO;
        } catch (\Exception $e) {
            return null;
        }
    }
    

    function edit(RegistrationDTO $registrationDTO): ?RegistrationDTO
    {
        try {
            $updateData = [
                'doctor_id' => $registrationDTO->doctor->id ?? null,
                'receipt' => $registrationDTO->receipt ?? null,
                'check_date' => $registrationDTO->checkDate ?? null,
            ];
            $filteredUpdateData = array_filter($updateData);
            $registrationId = $registrationDTO->id;

            if (!empty($filteredUpdateData)) {
                $edit = Registration::where('id', $registrationId)
                    ->update($filteredUpdateData);

                if ($edit > 0) {
                    $updatedData = Registration::find($registrationId);

                    $formatedUpdatedData = new RegistrationDTO();
                    $formatedUpdatedData->id = $updatedData->id;
                    
                    $patient = new PatientDTO();
                    $patient->id = $updatedData->patient_id;
                    
                    $doctor = new UserDTO();
                    $doctor->id = $updatedData->doctor_id;

                    $formatedUpdatedData->patient = $patient;
                    $formatedUpdatedData->doctor = $doctor;
                    $formatedUpdatedData->complaint = $updatedData->complaint;
                    $formatedUpdatedData->receipt = $updatedData->receipt;
                    $formatedUpdatedData->checkDate = Carbon::parse($updatedData->check_date);
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

    function getWhereCheckDateIsNull(): array
    {
        try {
            $nullCheckDateRegistrationsCollections = Registration::with(['patient', 'doctor'])
                ->whereNull('check_date')
                ->get();

            $formatedRegistrations = $nullCheckDateRegistrationsCollections->map(function ($registration) {
                $formatedRegistration = new RegistrationDTO();
                $formatedRegistration->id = $registration->id;
                
                $patient = new PatientDTO();
                $patient->id = $registration->patient->id;
                
                $doctor = new UserDTO();
                if ($registration->doctor) {
                    $doctor->id = $registration->doctor->id;
                }

                $formatedRegistration->patient = $patient;
                $formatedRegistration->doctor = $doctor;
                $formatedRegistration->complaint = $registration->complaint;
                $formatedRegistration->receipt = $registration->receipt;
                $formatedRegistration->checkDate = Carbon::parse($registration->check_date);
                $formatedRegistration->createdAt = $registration->created_at;
                $formatedRegistration->updatedAt = $registration->updated_at;
                
                return $formatedRegistration;
            });

            $registrations = $formatedRegistrations->toArray();
            return $registrations;
        } catch (\Exception $e) {
            return [];
        }
    }

    function getWhereCheckDateIsNotNull(): array
    {
        try {
            $notNullCheckDateRegistrationsCollections = Registration::with(['patient', 'doctor'])
                ->whereNotNull('check_date')
                ->get();

            $formatedRegistrations = $notNullCheckDateRegistrationsCollections->map(function ($registration) {
                $formatedRegistration = new RegistrationDTO();
                $formatedRegistration->id = $registration->id;
                
                $patient = new PatientDTO();
                $patient->id = $registration->patient->id;
                
                $doctor = new UserDTO();
                if ($registration->doctor) {
                    $doctor->id = $registration->doctor->id;
                }

                $formatedRegistration->patient = $patient;
                $formatedRegistration->doctor = $doctor;
                $formatedRegistration->complaint = $registration->complaint;
                $formatedRegistration->receipt = $registration->receipt;
                $formatedRegistration->checkDate = Carbon::parse($registration->check_date);
                $formatedRegistration->createdAt = $registration->created_at;
                $formatedRegistration->updatedAt = $registration->updated_at;
                
                return $formatedRegistration;
            });

            $registrations = $formatedRegistrations->toArray();
            return $registrations;
        } catch (\Exception $e) {
            return [];
        }
    }
}
?>
