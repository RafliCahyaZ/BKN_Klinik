<?php

namespace App\Http\Controllers;

use App\Repository\RegistrationRepository;

class HistoryController extends Controller
{
    public function __construct(
        private RegistrationRepository $registrationRepository
    ) {}

    public function show($id)
    {
        $registration = $this->registrationRepository->getById($id);

        return view("dashboard.patient.history.show", [
            "registration" => $registration
        ]);
        // return view("pdf.registration", [
        //     "registration" => $registration
        // ]);
    }
}
