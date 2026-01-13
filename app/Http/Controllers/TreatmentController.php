<?php

namespace App\Http\Controllers;

use App\Repository\TreatmentRepository;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index(TreatmentRepository $treatmentRepository)
    {
        return view("dashboard.treatment.home", [
            "treatments" => $treatmentRepository->getAll()
        ]);
    }
}
