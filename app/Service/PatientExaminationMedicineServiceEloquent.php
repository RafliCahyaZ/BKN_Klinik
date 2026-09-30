<?php

namespace App\Service;

use App\DTO\RegistrationDTO;
use App\Repository\AppointmentRepositoryEloquent;
use App\Repository\MedicineRepositoryEloquent;
use App\Repository\MedicineStockTransactionRepositoryEloquent;
use App\Repository\RegistrationRepositoryEloquent;
use App\Repository\RegistrationMedicineRepositoryEloquent;
use Illuminate\Support\Facades\DB;

class PatientExaminationMedicineServiceEloquent implements PatientExaminationMedicineService
{
    public function __construct(
        private RegistrationRepositoryEloquent $registrationRepository,
        private AppointmentRepositoryEloquent $appointmentRepository,
        private MedicineStockTransactionRepositoryEloquent $medicineStockTransactionRepository,
        private MedicineRepositoryEloquent $medicineRepository,
        private RegistrationMedicineRepositoryEloquent $registrationMedicineRepository
    ) {}

public function executeNonObat(
        RegistrationDTO $registrationDto,
        array $diagnoses,
        array $treatments
    ): bool {
        try {
            return DB::transaction(function () use (
                $registrationDto,
                $diagnoses,
                $treatments
            ) {

                $editResult = true;
                if (! $this->appointmentRepository->attachManyDiagnosesToRegistration($registrationDto, $diagnoses)) {
                    $editResult = false;
                }
                if (! $this->appointmentRepository->attachManyTreatmentsToRegistration($registrationDto, $treatments)) {
                    $editResult = false;
                }
                if (! $this->registrationRepository->edit($registrationDto)) {
                    dd('failed edit registration in patient examination service');
                    $editResult = false;
                }

                return $editResult;
            });
        } catch (\Throwable $th) {
            //return false;
            dd($th);
            return back()->with('failed', [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ]);
        }

    }

    // Disable supaya ga perlu input data obat
    // Nif - 17 April 2026
    // Aslinya public function execute
public function execute(
        RegistrationDTO $registrationDto,
        array $diagnoses,
        array $treatments,
        array $medicineTransactions,
        array $registrationMedicines
    ): bool {
        // dd($treatments[0]);
        try {
            return DB::transaction(function () use (
                $registrationDto,
                $diagnoses,
                $treatments,
                $medicineTransactions,
                $registrationMedicines
            ) {

                $editResult = true;
                if (! $this->appointmentRepository->attachManyDiagnosesToRegistration($registrationDto, $diagnoses)) {
                    $editResult = false;
                }
                
                if (sizeof($treatments) == 1) { //ketika user tidak memilih tindakan
                    if ($treatments[0]->id == '99') {
                        // dd($treatments[0]);
                        //tidak perlu simpan tindakan krn tindakannya dummy
                    } else {
                        if (! $this->appointmentRepository->attachManyTreatmentsToRegistration($registrationDto, $treatments)) {
                            $editResult = false;
                        }
                    }
                } else {
                    if (! $this->appointmentRepository->attachManyTreatmentsToRegistration($registrationDto, $treatments)) {
                        $editResult = false;
                    }
                }
                if (! $this->registrationRepository->edit($registrationDto)) {
                    dd('failed edit registration in patient examination service');
                    $editResult = false;
                }

                $medicineUpdates = [];

                foreach ($medicineTransactions as $medicineStockTransactionDto) {
                    if (is_null($this->medicineStockTransactionRepository->insert($medicineStockTransactionDto))) {
                        $editResult = false;
                        break;
                    }

                    $medicineId = $medicineStockTransactionDto->medicine->id;
                    if (! isset($medicineUpdates[$medicineId])) {
                        $medicineUpdates[$medicineId] = [
                            'medicine' => $medicineStockTransactionDto->medicine,
                            'totalQuantity' => 0,
                        ];
                    }
                    $medicineUpdates[$medicineId]['totalQuantity'] += $medicineStockTransactionDto->quantity;
                }

                if ($editResult) {
                    foreach ($registrationMedicines as $registrationMedicineDTO) {
                        if (is_null($this->registrationMedicineRepository->insert($registrationMedicineDTO))
                        ) {
                            $editResult = false;
                            break;
                        }
                    }
                }

                if ($editResult) {
                    foreach ($medicineUpdates as $medicineId => $updateData) {
                        $currentMedicine = $this->medicineRepository->getById($medicineId);
                        if (! $currentMedicine) {
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

                        if (! $updatedMedicineDto) {
                            $editResult = false;
                            break;
                        }
                    }
                } 

                return $editResult;
            });
        } catch (\Throwable $th) {
            //return false;
            dd($th);
            return back()->with('failed', [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ]);
        }

    }
}
