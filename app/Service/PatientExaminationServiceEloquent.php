<?php

namespace App\Service;

use App\DTO\RegistrationDTO;
use App\Repository\AppointmentRepositoryEloquent;
use App\Repository\MedicineStockTransactionRepositoryEloquent;
use App\Repository\RegistrationRepositoryEloquent;
use Illuminate\Support\Facades\DB;

class PatientExaminationServiceEloquent implements PatientExaminationService
{
    public function __construct(
        private RegistrationRepositoryEloquent $registrationRepository,
        private AppointmentRepositoryEloquent $appointmentRepository,
        private MedicineStockTransactionRepositoryEloquent $medicineStockTransactionRepository,
    ) {}
    public function execute(
        RegistrationDTO $registrationDto,
        array $diagnoses,
        array $treatments,
        array $medicines
    ): bool {

        return DB::transaction(function () use (
            $registrationDto,
            $diagnoses,
            $treatments,
            $medicines
        ) {
            $editResult = true;
            if (!$this->appointmentRepository->attachManyDiagnosesToRegistration($registrationDto, $diagnoses)) {
                dd("haii");
                $editResult = false;
            }
            if (!$this->appointmentRepository->attachManyTreatmentsToRegistration($registrationDto, $treatments)) {
                dd("hello");
                $editResult = false;
            }
            if (!$this->registrationRepository->edit($registrationDto)) {
                dd("aiii");
                $editResult = false;
            }

            if (!$this->medicineStockTransactionRepository->insertMany($medicines)) {
                dd("asu");
                $editResult = false;
            }

            return $editResult;
        });
    }
}
