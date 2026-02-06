<?php

namespace App\Service;

use App\DTO\RegistrationDTO;
use App\Repository\AppointmentRepositoryEloquent;
use App\Repository\MedicineRepositoryEloquent;
use App\Repository\MedicineStockTransactionRepositoryEloquent;
use App\Repository\RegistrationRepositoryEloquent;
use Illuminate\Support\Facades\DB;

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

                $medicineUpdates = [];
                
                foreach ($medicineTransactions as $medicineStockTransactionDto) {
                    if (is_null($this->medicineStockTransactionRepository->insert($medicineStockTransactionDto))) {
                        $editResult = false;
                        break;
                    }

                    $medicineId = $medicineStockTransactionDto->medicine->id;
                    if (!isset($medicineUpdates[$medicineId])) {
                        $medicineUpdates[$medicineId] = [
                            'medicine' => $medicineStockTransactionDto->medicine,
                            'totalQuantity' => 0
                        ];
                    }
                    $medicineUpdates[$medicineId]['totalQuantity'] += $medicineStockTransactionDto->quantity;
                }

                if ($editResult) {
                    foreach ($medicineUpdates as $medicineId => $updateData) {
                        $currentMedicine = $this->medicineRepository->getById($medicineId);
                        if (!$currentMedicine) {
                            $editResult = false;
                            break;
                        }

                        $newStock = $currentMedicine->stock - $updateData['totalQuantity'];
                        
                        if ($newStock < 0) {
                            $editResult = false;
                            break;
                        }
                        
                        $currentMedicine->stock = $newStock;
                        $updatedMedicineDto = $this->medicineRepository->edit($medicineId, $currentMedicine);
                        
                        if (!$updatedMedicineDto) {
                            $editResult = false;
                            break;
                        }
                    }
                }

                return $editResult;
            });
        } catch (\Throwable $th) {
            return false;
        }

    }
}
