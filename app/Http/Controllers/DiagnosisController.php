<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\DiagnosisRepository;

class DiagnosisController extends Controller
{
    public function index(DiagnosisRepository $diagnosisRepository)
    {
        return view("dashboard.diagnosis.home", [
            "diagnoses" => $diagnosisRepository->getAll()
        ]);
    }
}
