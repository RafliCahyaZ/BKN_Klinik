<?php

namespace App\Http\Controllers;

use App\Repository\RegistrationRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Registration;

class ReportController extends Controller
{
    public function index()
    {
        return view("dashboard.patient.report.create");
    }

    public function print(Request $request, RegistrationRepository $registrationRepository)
    {
        try {
            $validated = $this->periodicInputValidation($request);
            $start = Carbon::parse($validated['start-date'])->startOfDay();
            $end = Carbon::parse($validated['end-date'])->endOfDay();
    
            $registrations = $registrationRepository->getByRangeDate($start, $end);
            $timestamp = Carbon::now()->format('Y-m-d_His');
    
            $pdf = Pdf::loadView('pdf.report', compact('registrations', 'start', 'end'));
            return $pdf->stream("laporan_{$timestamp}.pdf");
        } catch (\Throwable $th) {
            return back()->with("failed", "Terjadi kesalahan saat membuat laporan");
        }
    }

    public function periodicInputValidation(Request $request)
    {
        $validated = $request->validate([
            'start-date' => 'required|date',
            'end-date' => 'required|date|after_or_equal:start-date',
        ]);

        return $validated;
    }

    public function patientsStatus()
    {
        try {
            $today = Carbon::today();
            $checkedCount = Registration::whereDate('created_at', $today)
                ->whereNotNull('check_date')
                ->count();
            $uncheckedCount = Registration::whereDate('created_at', $today)
                ->whereNull('check_date')
                ->count();
            return response()->json([
                'checked' => $checkedCount,
                'unchecked' => $uncheckedCount
            ]);
        } catch (\Throwable $th) {
            return back()->with("failed", "Terjadi kesalahan saat mengambil data status pasien");
        }
    }


    public function weeklyPatientsChart()
    {
        try {
            $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $days = [];
    
            for ($i = 0; $i < 5; $i++) {
                $date = $startOfWeek->copy()->addDays($i);
    
                $registrations = Registration::whereDate('created_at', $date);
                $checked = Registration::whereDate('created_at', $date)
                    ->whereNotNull('check_date')
                    ->count();
    
                $days[] = [
                    'label' => $date->translatedFormat('D, d M'),
                    'registrations' => $registrations->count(),
                    'checked' => $checked,
                ];
            }
    
            return response()->json($days);
        } catch (\Throwable $th) {
            return back()->with("failed", "Terjadi kesalahan saat mengambil data grafik pasien mingguan");
        }
    }

}
