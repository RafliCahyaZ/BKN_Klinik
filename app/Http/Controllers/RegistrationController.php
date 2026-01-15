<?php

namespace App\Http\Controllers;

use App\DTO\RegistrationDTO;
use App\Repository\PatientRepository;
use App\Repository\RegistrationRepository;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{

    public function __construct(
        private PatientRepository $patientRepository,
        private RegistrationRepository $registrationRepository
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

    public function store($patientId, Request $request)
    {
        $patient = $this->patientRepository->getById($patientId);

        if (!$patient) {
            abort(404);
        }

        $registrationDto = new RegistrationDTO();
        $registrationDto->patient = $patient;
        $registrationDto->complaint = $request["complaint"];

        $insertResult = $this->registrationRepository->insert($registrationDto);

        if (!$insertResult) {
            return back()->withInput();
        }

        return redirect()->route("patient.queue");
    }
}
