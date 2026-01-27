<?php

namespace App\Http\Controllers;

use App\Repository\RegistrationRepository;
use Barryvdh\DomPDF\Facade\Pdf;

class HistoryController extends Controller
{
    public function __construct(
        private RegistrationRepository $registrationRepository
    ) {}

    public function print($id)
    {
        $registration = $this->registrationRepository->getById($id);
        $pdf = Pdf::loadView('pdf.history', compact('registration'));
        return $pdf->download('history_rekam_medis_' . $registration->patient->name . '.pdf');
    }

    public function printAll() {
        $registrations = $this->registrationRepository->getAll();

        $pdf = Pdf::loadView('pdf.all-history', compact('registrations'));
        return $pdf->download('all_patient_histories.pdf');
    }

    public function show($id)
    {
        $registration = $this->registrationRepository->getById($id);

        return view("dashboard.patient.history.show", [
            "registration" => $registration
        ]);
       
    }
}
