<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    public function index()
    {
        return view("dashboard.diagnosis.home");
    }
}
