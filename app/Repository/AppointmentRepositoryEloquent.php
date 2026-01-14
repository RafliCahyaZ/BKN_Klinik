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
}
?>
