<?php

namespace App\Repository;

use App\DTO\DiagnosisDTO;
use App\DTO\RegistrationDTO;
use App\DTO\TreatmentDTO;

interface AppointmentRepository
{
    public function attachDiagnosisToRegistration(RegistrationDTO $registrationDTO, DiagnosisDTO $diagnosisDTO): bool;

    public function attachTreatmentToRegistration(RegistrationDTO $registrationDTO, TreatmentDTO $treatmentDTO): bool;

    public function attachManyDiagnosesToRegistration(RegistrationDTO $registrationDTO, array $diagnoses): bool;

    public function attachManyTreatmentsToRegistration(RegistrationDTO $registrationDTO, array $diagnoses): bool;
}
