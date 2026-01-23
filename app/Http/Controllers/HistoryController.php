<?php

namespace App\Http\Controllers;

use App\Repository\RegistrationRepository;

class HistoryController extends Controller
{
    public function __construct(
        private RegistrationRepository $registrationRepository
    ) {}

    public function print($id)
    {
        $registration = $this->registrationRepository->getById($id);
        $diagnoses = $this->diagnosisRepository->getAll();
        $treatments = $this->treatmentRepository->getAll();

        $registration->diagnoses = $diagnoses;
        $registration->treatments = $treatments;

        return view("dashboard.patient.history.print", [
            "registration" => $registration
        ]);
    }



    public function show($id)
    {
        $registration = $this->registrationRepository->getById($id);

        return view("dashboard.patient.history.show", [
            "registration" => $registration
        ]);
    }
}
