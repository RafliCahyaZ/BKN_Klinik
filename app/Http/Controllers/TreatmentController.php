<?php

namespace App\Http\Controllers;

use App\DTO\TreatmentDTO;
use App\Repository\TreatmentRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function __construct(
        private TreatmentRepository $treatmentRepository
    ) {}

    public function index(TreatmentRepository $treatmentRepository)
    {
        return view('dashboard.treatment.home', [
            'treatments' => $treatmentRepository->getAll(),
        ]);
    }

    public function edit($id)
    {
        $treatment = $this->treatmentRepository->getById($id);

        return view('dashboard.treatment.edit', [
            'treatment' => $treatment,
        ]);
    }

    public function editAction($id, Request $request)
    {
        $validated = $this->getTreatmentInputValidation($request);

        $treatment = $this->treatmentRepository->getById($id);

        if (! $treatment) {
            abort(404);
        }

        $treatment->treatmentCode = $validated['icd_code'];
        $treatment->treatmentName = $validated['treatment_name'];

        if (! $this->treatmentRepository->edit($id, $treatment)) {
            return redirect()->route('treatment.index')->with('failed', 'Failed! Data gagal diedit');
        }

        return redirect()->route('treatment.index')->with('success', 'Sukses! Data berhasil diedit');
    }

    public function create()
    {
        return view('dashboard.treatment.create');
    }

    public function destroy($id)
    {
        $treatment = $this->treatmentRepository->getById($id);

        if (! $treatment) {
            abort(404);
        }

        if (! $this->treatmentRepository->delete($id)) {
            return redirect()->route('treatment.index')->with('failed', 'Failed! Data gagal dihapus');
        }

        return redirect()->route('treatment.index')->with('success', 'Sukses! Data berhasil dihapus');
    }

    public function store(Request $request)
    {
        $validated = $this->getTreatmentInputValidation($request);

        $treatmentDto = new TreatmentDTO;
        $treatmentDto->treatmentCode = $validated['icd_code'];
        $treatmentDto->treatmentName = $validated['treatment_name'];

        if (! $this->treatmentRepository->insert($treatmentDto)) {
            return redirect()->route('treatment.index')->with('failed', 'Failed! Data gagal disimpan');
        }

        return redirect()->route('treatment.index')->with('success', 'Sukses! Data berhasil disimpan');
    }

    public function print()
    {
        $treatments = $this->treatmentRepository->getAll();
        $timestamp = Carbon::now()->format('Y-m-d_His');

        $pdf = Pdf::loadView('pdf.treatments', compact('treatments'));

        return $pdf->stream("master_data_tindakan_{$timestamp}.pdf");
    }

    private function getTreatmentInputValidation(Request $request)
    {
        $validated = $request->validate([
            'icd_code' => [
                'required',
                'string',
                'min:1',
                'max:100',
            ],
            'treatment_name' => [
                'required',
                'string',
                'min:5',
                'max:255',
            ],
        ]);

        return $validated;
    }
}
