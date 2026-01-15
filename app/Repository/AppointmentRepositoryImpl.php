<?php

namespace App\Repository;

use App\DTO\DiagnosisDTO;
use App\DTO\RegistrationDTO;
use App\DTO\TreatmentDTO;
use Carbon\Carbon;

class AppointmentRepositoryImpl implements AppointmentRepository
{
    public function attachDiagnosisToRegistration(RegistrationDTO $registrationDTO, DiagnosisDTO $diagnosisDTO): bool
    {
        // $diagnosis = new DiagnosisDTO();
        // $diagnosis->id = '1';
        // $diagnosis->icdCode = 'I10';
        // $diagnosis->diseaseName = 'Hipertensi Esensial';
        // $diagnosis->createdAt = Carbon::now()->subDays(5);
        // $diagnosis->updatedAt = Carbon::now()->subDays(2);

        return true;
    }

    public function attachTreatmentToRegistration(RegistrationDTO $registrationDTO, TreatmentDTO $treatmentDTO): bool
    {
        // $treatment = new TreatmentDTO();
        // $treatment->id = '1';
        // $treatment->treatmentCode = 'TRT-001';
        // $treatment->treatmentName = 'Terapi Medikamentosa';
        // $treatment->createdAt = Carbon::now()->subDays(5);
        // $treatment->updatedAt = Carbon::now()->subDays(2);

        return true;
    }

    public function attachManyDiagnosesToRegistration(RegistrationDTO $registrationDTO, array $diagnoses): bool
    {
        // $diagnosis1 = new DiagnosisDTO();
        // $diagnosis1->id = '1';
        // $diagnosis1->icdCode = 'I10';
        // $diagnosis1->diseaseName = 'Hipertensi Esensial';
        // $diagnosis1->createdAt = Carbon::now()->subDays(5);
        // $diagnosis1->updatedAt = Carbon::now()->subDays(2);

        // $diagnosis2 = new DiagnosisDTO();
        // $diagnosis2->id = '2';
        // $diagnosis2->icdCode = 'E11';
        // $diagnosis2->diseaseName = 'Diabetes Melitus Tipe 2';
        // $diagnosis2->createdAt = Carbon::now()->subDays(10);
        // $diagnosis2->updatedAt = Carbon::now()->subDays(3);

        // $diagnosis3 = new DiagnosisDTO();
        // $diagnosis3->id = '3';
        // $diagnosis3->icdCode = 'J45';
        // $diagnosis3->diseaseName = 'Asma Bronkial';
        // $diagnosis3->createdAt = Carbon::now()->subDays(7);
        // $diagnosis3->updatedAt = Carbon::now()->subDays(1);

        return true;
    }

    public function attachManyTreatmentsToRegistration(RegistrationDTO $registrationDTO, array $diagnoses): bool
    {
        // $treatment1 = new TreatmentDTO();
        // $treatment1->id = '1';
        // $treatment1->treatmentCode = 'TRT-001';
        // $treatment1->treatmentName = 'Terapi Medikamentosa';
        // $treatment1->createdAt = Carbon::now()->subDays(5);
        // $treatment1->updatedAt = Carbon::now()->subDays(2);

        // $treatment2 = new TreatmentDTO();
        // $treatment2->id = '2';
        // $treatment2->treatmentCode = 'TRT-002';
        // $treatment2->treatmentName = 'Fisioterapi';
        // $treatment2->createdAt = Carbon::now()->subDays(8);
        // $treatment2->updatedAt = Carbon::now()->subDays(1);

        // $treatment3 = new TreatmentDTO();
        // $treatment3->id = '3';
        // $treatment3->treatmentCode = 'TRT-003';
        // $treatment3->treatmentName = 'Terapi Okupasi';
        // $treatment3->createdAt = Carbon::now()->subDays(12);
        // $treatment3->updatedAt = Carbon::now()->subDays(4);

        return true;
    }
}
