<?php

namespace App\Http\Controllers;

use App\Repository\PatientRepository;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(PatientRepository $patientRepository)
    {
        return view("dashboard/patient.home", [
            "patients" => $patientRepository->getAll()
        ]);
    }

    public function queue()
    {
        return view("dashboard/patient.queue");
    }

    public function history()
    {
        return view("dashboard/patient.history");
    }
}
