<?php

namespace App\Service;

use App\DTO\RegistrationDTO;
use App\Repository\AppointmentRepositoryEloquent;
use App\Repository\MedicineRepositoryEloquent;
use App\Repository\MedicineStockTransactionRepositoryEloquent;
use App\Repository\RegistrationRepositoryEloquent;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class PatientExaminationServiceEloquent implements PatientExaminationService
{
    public function __construct(
        private RegistrationRepositoryEloquent $registrationRepository,
        private AppointmentRepositoryEloquent $appointmentRepository,
        private MedicineStockTransactionRepositoryEloquent $medicineStockTransactionRepository,
        private MedicineRepositoryEloquent $medicineRepository
    ) {}
    public function execute(
        RegistrationDTO $registrationDto,
        array $diagnoses,
        array $treatments,
        array $medicineTransactions
    ): bool {
        try {
            return DB::transaction(function () use (
                $registrationDto,
                $diagnoses,
                $treatments,
                $medicineTransactions
            ) {

                $editResult = true;
                if (!$this->appointmentRepository->attachManyDiagnosesToRegistration($registrationDto, $diagnoses)) {
                    $editResult = false;
                }
                if (!$this->appointmentRepository->attachManyTreatmentsToRegistration($registrationDto, $treatments)) {
                    $editResult = false;
                }
                if (!$this->registrationRepository->edit($registrationDto)) {
                    $editResult = false;
                }
    
                if (!$this->medicineStockTransactionRepository->insertMany($medicineTransactions)) {
                    $editResult = false;
                }

                
                foreach ($medicineTransactions as $medicineStockTransactionDto) {
                    $medicineDto = $medicineStockTransactionDto->medicine;
                    $medicineDto->stock -= $medicineStockTransactionDto->quantity;
                    $updatedMedicineDto = $this->medicineRepository->edit($medicineDto->id, $medicineDto);
    
                    if (!$updatedMedicineDto) {
                        $editResult = false;
                        break;
                    }
                }
                return $editResult;
            });
        } catch (\Throwable $th) {
            dd($th);
            return false;
        }

    }
}
