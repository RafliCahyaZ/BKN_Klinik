<?php

namespace App\Http\Controllers;

use App\DTO\DiagnosisDTO;
use App\DTO\MedicineDTO;
use App\DTO\MedicineStockTransactionDTO;
use App\DTO\RegistrationDTO;
use App\DTO\TreatmentDTO;
use App\DTO\UserDTO;
use App\Repository\DiagnosisRepository;
use App\Repository\MedicineRepository;
use App\Repository\RegistrationRepository;
use App\Repository\TreatmentRepository;
use App\Service\PatientExaminationServiceEloquent;
use App\Type\TransactionType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function __construct(
        private RegistrationRepository $registrationRepository,
        private DiagnosisRepository $diagnosisRepository,
        private TreatmentRepository $treatmentRepository,
        private MedicineRepository $medicineRepository,
        private PatientExaminationServiceEloquent $patientExaminationService
    ) {}

    public function create($id)
    {
        $registrationDto = $this->registrationRepository->getById($id);

        if (! $registrationDto) {
            abort(404);
        }

        $user = Auth::user();

        $doctor = new UserDTO;
        $doctor->id = $user->id;
        $doctor->fullname = $user->fullname;
        $diagnoses = $this->diagnosisRepository->getAll();
        $treatments = $this->treatmentRepository->getAll();
        $medicines = $this->medicineRepository->getAll();

        return view('dashboard.patient.appointment.create', [
            'doctor' => $doctor,
            'diagnoses' => $diagnoses,
            'treatments' => $treatments,
            'registration' => $registrationDto,
            'medicines' => $medicines,
        ]);
    }

    public function createAction($id, Request $request)
    {
        try {
            $registration = $this->registrationRepository->getById($id);

            if (! $registration) {
                abort(404);
            }

            $user = Auth::user();

            $doctor = new UserDTO;
            $doctor->id = $user->id;
            $registration->checkDate = Carbon::now();
            $registration->doctor = $doctor;
            $registration->receipt = $request['receipt'];
            $registration->complaint = $request['keluhan'];

            $diagnoses = collect($request['diagnosis_ids'])->map(function ($item) {
                $diagnosisDto = new DiagnosisDTO;
                $diagnosisDto->id = $item;

                return $diagnosisDto;
            });

            $treatments = collect($request['treatment_ids'])->map(function ($item) {
                $treatmentDto = new TreatmentDTO;
                $treatmentDto->id = $item;

                return $treatmentDto;
            });

            $aggregatedmedicineIds = $this->aggregateMedicineIds($request['medicine_ids']);

            $medicineAvailables = [];

            foreach ($aggregatedmedicineIds as $medicine) {
                $medicineAvailable = $this->isMedicineStockAvailable(
                    $medicine["'id'"],
                    $medicine["'quantity'"]
                );
                if (is_null($medicineAvailable)) {
                    return redirect()->back()->with('error_alert', 'Maaf, obat tidak ditemukan!')->withInput();
                }
                $medicineAvailables[] = $medicineAvailable;
            }

            $medicineInputs = $aggregatedmedicineIds;
            foreach ($medicineAvailables as $index => $medicineAvailable) {
                $medicineInputs[$index]["'stock'"] = $medicineAvailables[$index]->stock;
            }

            $medicinesTransaction = $this->mapInputToMedicineStockTransactionDTO($id, $medicineInputs);

            $patientExaminationResult = $this->patientExaminationService->execute(
                $registration,

                $diagnoses->toArray(),
                $treatments->toArray(),
                $medicinesTransaction
            );

            if (! $patientExaminationResult) {
                return back()->with('failed', 'Failed make appointment');
            }

            return redirect()->route('patient.history.show', $id)->with('success', 'Berhasil melakukan pemeriksaan');
        } catch (\Throwable $th) {
            return back()->with('failed', 'Terjadi kesalahan saat melakukan pemeriksaan');
        }
    }

    private function aggregateMedicineIds(array $medicineIds)
    {
        $idMap = [];
        $aggregatedmedicineIds = [];
        foreach ($medicineIds as $index => $medicineId) {
            if (array_key_exists($medicineId["'id'"], $idMap)) {
                $existingIndex = $idMap[$medicineId["'id'"]];
                $aggregatedmedicineIds[$existingIndex]["'quantity'"] += $medicineId["'quantity'"];
            } else {
                $idMap[$medicineId["'id'"]] = $index;
                $aggregatedmedicineIds[] = $medicineId;
            }
        }

        return $aggregatedmedicineIds;
    }

    private function isMedicineStockAvailable(string $medicineId, int $requestedQuantity)
    {
        $medicine = $this->medicineRepository->getById($medicineId);
        if (! $medicine) {
            return null;
        }
        if ($medicine->stock >= $requestedQuantity) {
            return $medicine;
        } else {
            return false;
        }
    }

    public function cancel($id)
    {
        try {
            $registration = $this->registrationRepository->getById($id);

            if (! $registration) {
                abort(404);
            }

            $registration->cancelledAt = Carbon::now();

            $cancelResult = $this->registrationRepository->edit($registration);

            if (! $cancelResult) {
                return back()->with('failed', 'Gagal membatalkan janji');
            }

            return redirect()->route('patient.queue')->with('success', 'Berhasil membatalkan janji');
        } catch (\Throwable $th) {
            // throw $th;
        }
    }

    private function mapInputToMedicineStockTransactionDTO($registrationId, $medicineInputs)
    {
        $medicineStocks = [];

        foreach ($medicineInputs as $medicineInput) {
            $medicineStockTransactionDto = new MedicineStockTransactionDTO;
            $medicineStockTransactionDto->inventoryOfficer = new UserDTO;
            $medicineStockTransactionDto->inventoryOfficer->id = Auth::id();
            $medicineStockTransactionDto->medicine = new MedicineDTO;
            $medicineStockTransactionDto->medicine->id = $medicineInput["'id'"];
            $medicineStockTransactionDto->medicine->stock = $medicineInput["'stock'"];
            $medicineStockTransactionDto->beginningStock = $medicineInput["'stock'"];
            $medicineStockTransactionDto->lastStock = $medicineInput["'stock'"] - $medicineInput["'quantity'"];
            $medicineStockTransactionDto->registration = new RegistrationDTO;
            $medicineStockTransactionDto->registration->id = $registrationId;
            $medicineStockTransactionDto->type = TransactionType::OUT;
            $medicineStockTransactionDto->quantity = $medicineInput["'quantity'"];
            $medicineStockTransactionDto->createdAt = Carbon::now();
            $medicineStockTransactionDto->updatedAt = Carbon::now();

            $medicineStocks[] = $medicineStockTransactionDto;
        }

        return $medicineStocks;
    }
}
