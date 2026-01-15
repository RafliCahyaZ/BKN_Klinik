<?php

namespace App\Http\Controllers;

use App\DTO\DiagnosisDTO;
use App\DTO\TreatmentDTO;
use App\Models\User;
use App\Repository\DiagnosisRepository;
use App\Repository\PatientRepository;
use App\Repository\RegistrationRepository;
use App\Repository\TreatmentRepository;
use App\Repository\UserRepository;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(
        private RegistrationRepository $registrationRepository,
        private DiagnosisRepository $diagnosisRepository,
        private TreatmentRepository $treatmentRepository,
        private UserRepository $userRepository
    ) {}

    public function create($id)
    {
        $registrationDto = $this->registrationRepository->getById($id);

        if (!$registrationDto) {
            abort(404);
        }

        $doctors = $this->userRepository->getByRole("doctor");
        $diagnoses = $this->diagnosisRepository->getAll();
        $treatments = $this->treatmentRepository->getAll();

        return view("dashboard.patient.appointment.create", [
            "doctors" => $doctors,
            "diagnoses" => $diagnoses,
            "treatments" => $treatments,
            "registration" => $registrationDto
        ]);
    }

    public function createAction($id, Request $request)
    {
        $registration = $this->registrationRepository->getById($id);

        if (!$registration) {
            abort(404);
        }

        $diagnoses = collect($request["diagnosis_ids"])->map(function ($item) {
            $diagnosisDto = new DiagnosisDTO();
            $diagnosisDto->id = $item;

            return $diagnosisDto;
        });

        $treatments = collect($request["treatment_ids"])->map(function ($item) {
            $treatmentDto = new TreatmentDTO();
            $treatmentDto->id = $item;

            return $treatmentDto;
        });

        dd($request["receipt"]);
    }
}
