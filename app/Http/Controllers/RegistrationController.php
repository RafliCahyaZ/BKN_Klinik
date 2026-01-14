<?php

namespace App\Http\Controllers;

use App\Repository\PatientRepository;
use App\Repository\UserRepository;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{

    public function __construct(
        private PatientRepository $patientRepository
    ) {}

    public function create($patientId)
    {
        $patient = $this->patientRepository->getById($patientId);

        if (!$patient) {
            abort(404);
        }

        return view(
            "dashboard.registration.create",
            [
                "patient" => $patient
            ]
        );
    }
}
