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
        $registrationsCollections = Registration::with(['patient', 'doctor'])->get();

        $formatedRegistrations = $registrationsCollections->map(function ($registration) {
            $formatedRegistration = new RegistrationDTO();
            $formatedRegistration->id = $registration->id;
            
            $patient = new PatientDTO();
            $patient->id = $registration->patient->id;
            
            $doctor = new UserDTO();
            $doctor->id = $registration->doctor->id;

            $formatedRegistration->patient = $patient;
            $formatedRegistration->doctor = $doctor;
            $formatedRegistration->complaint = $registration->complaint;
            $formatedRegistration->receipt = $registration->receipt;
            $formatedRegistration->checkDate = $registration->check_date;
            $formatedRegistration->createdAt = $registration->created_at;
            $formatedRegistration->updatedAt = $registration->updated_at;
            
            return $formatedRegistration;
        });

        $registrations = $formatedRegistrations->toArray();
        return $registrations;
    }

    function getById(string $id): ?RegistrationDTO
    {
        $registration = Registration::with(['patient', 'doctor'])->find($id);

        if (!$registration) {
            return null;
        }

        $formated_registration = new RegistrationDTO();
        $formated_registration->id = $registration->id;
        
        // Format patient data
        $patient = new PatientDTO();
        $patient->id = $registration->patient->id;

        $doctor = new UserDTO();
        $doctor->id = $registration->doctor->id;
        
        $formated_registration->patient = $patient;
        $formated_registration->doctor = $doctor;
        $formated_registration->complaint = $registration->complaint;
        $formated_registration->receipt = $registration->receipt;
        $formated_registration->checkDate = $registration->check_date;
        $formated_registration->createdAt = $registration->created_at;
        $formated_registration->updatedAt = $registration->updated_at;
        
        return $formated_registration;
    }

    function insert(RegistrationDTO $registrationDTO): ?RegistrationDTO
    {
        $registration = new Registration();

        try {
            $registration->fill([
                'patient_id' => $registrationDTO->patient->id,
                'doctor_id' => $registrationDTO->doctor->id,
                'receipt' => $registrationDTO->receipt,
                'complaint' => $registrationDTO->complaint,
            ]);
            $registration->save();
        } catch (QueryException $e) {
            return null;
        }
        
        $registrationDTO->id = $registration->id;
        $registrationDTO->receipt = $registration->receipt;
        $registrationDTO->complaint = $registration->complaint;
        $registrationDTO->checkDate = $registration->check_date;
        $registrationDTO->createdAt = $registration->created_at;
        $registrationDTO->updatedAt = $registration->updated_at;

        return $registrationDTO;
    }
}
?>
