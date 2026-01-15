<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\DiagnosisRepository;
use App\Repository\RegistrationRepository;
use App\Repository\TreatmentRepository;
use App\Repository\UserRepository;

class HistoryController extends Controller
{
    public function __construct(
        private RegistrationRepository $registrationRepository,
        private DiagnosisRepository $diagnosisRepository,
        private TreatmentRepository $treatmentRepository,
        private UserRepository $userRepository
    ) {}

    public function show($id)
    {
        $registration = $this->registrationRepository->getById($id);
        $diagnoses = $this->diagnosisRepository->getAll();
        $treatments = $this->treatmentRepository->getAll();

        $registration->diagnoses = $diagnoses;
        $registration->treatments = $treatments;

        return view("dashboard.patient.history.show", [
            "registration" => $registration
        ]);
    }
}
