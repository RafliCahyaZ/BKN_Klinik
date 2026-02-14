<?php

namespace App\Repository;

use App\DTO\DiagnosisDTO;
use App\DTO\RegistrationDTO;
use App\DTO\TreatmentDTO;
use App\Models\Registration;
use Illuminate\Database\QueryException;

class AppointmentRepositoryEloquent implements AppointmentRepository
{
    public function attachDiagnosisToRegistration(RegistrationDTO $registrationDTO, DiagnosisDTO $diagnosisDTO): bool
    {
        try {
            $registration = Registration::find($registrationDTO->id);
            $registration->diagnoses()->attach($diagnosisDTO->id);

            return true;
        } catch (QueryException $e) {
            return false;
        }

        return false;
    }

    public function attachTreatmentToRegistration(RegistrationDTO $registrationDTO, TreatmentDTO $treatmentDTO): bool
    {
        try {
            $registration = Registration::find($registrationDTO->id);
            $registration->treatments()->attach($treatmentDTO->id);

            return true;
        } catch (QueryException $e) {
            return false;
        }

        return false;
    }

    public function attachManyDiagnosesToRegistration(RegistrationDTO $registrationDTO, array $diagnoses): bool
    {
        if (! count($diagnoses)) {
            return false;
        }
        try {
            $registration = Registration::find($registrationDTO->id);
            $diagnosisIds = array_map(fn ($diagnosis) => $diagnosis->id, $diagnoses);
            $registration->diagnoses()->syncWithoutDetaching($diagnosisIds);

            return true;
        } catch (QueryException $e) {
            return false;
        }
    }

    public function attachManyTreatmentsToRegistration(RegistrationDTO $registrationDTO, array $treatments): bool
    {
        if (! count($treatments)) {
            return false;
        }
        try {
            $registration = Registration::find($registrationDTO->id);
            $treatmentIds = array_map(fn ($treatment) => $treatment->id, $treatments);
            $registration->treatments()->syncWithoutDetaching($treatmentIds);

            return true;
        } catch (QueryException $e) {
            return false;
        }
    }
}
