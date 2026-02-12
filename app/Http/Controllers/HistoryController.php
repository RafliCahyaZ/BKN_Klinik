<?php

namespace App\Http\Controllers;

use App\Repository\RegistrationRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

class HistoryController extends Controller
{
    public function __construct(
        private RegistrationRepository $registrationRepository
    ) {}

    public function print($id)
    {
        $registration = $this->registrationRepository->getById($id);
        $timestamp = Carbon::now()->format('Y-m-d_His');
        
        $pdf = Pdf::loadView('pdf.history', compact('registration'));
        return $pdf->stream("histori_rekam_medis_{$registration->patient->name}_{$timestamp}.pdf");
    }

    public function printAll() {
        $registrations = $this->registrationRepository->getAll();
        $timestamp = Carbon::now()->format('Y-m-d_His');

        $pdf = Pdf::loadView('pdf.histories', compact('registrations'));
        return $pdf->stream("seluruh_histori_pasien_{$timestamp}.pdf");
    }

    public function show($id)
    {
        $registration = $this->registrationRepository->getById($id);

        return view("dashboard.patient.history.show", [
            "registration" => $registration
        ]);
       
    }
}
