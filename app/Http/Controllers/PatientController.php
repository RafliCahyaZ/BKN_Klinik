<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        return view("dashboard/patient.home");
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
