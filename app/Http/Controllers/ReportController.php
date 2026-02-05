<?php

namespace App\Http\Controllers;

use App\Repository\RegistrationRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view("dashboard.report.create");
    }

    public function print(Request $request, RegistrationRepository $registrationRepository)
    {
        $validated = $this->periodicInputValidation($request);
        $start = Carbon::parse($validated['start-date'])->startOfDay();
        $end = Carbon::parse($validated['end-date'])->endOfDay();

        $registrations = $registrationRepository->getByRangeDate($start, $end);
        $timestamp = Carbon::now()->format('Y-m-d_His');

        $pdf = Pdf::loadView('pdf.report', compact('registrations', 'start', 'end'));
        return $pdf->stream("laporan_{$timestamp}.pdf");
    }

    public function periodicInputValidation(Request $request)
    {
        $validated = $request->validate([
            'start-date' => 'required|date',
            'end-date' => 'required|date|after_or_equal:start-date',
        ]);

        return $validated;
    }

    public function create(Request $request)
    {
        dd($request);
    }
}
