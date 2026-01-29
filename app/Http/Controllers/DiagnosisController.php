<?php

namespace App\Http\Controllers;

use App\DTO\DiagnosisDTO;
use Illuminate\Http\Request;
use App\Repository\DiagnosisRepository;
use Barryvdh\DomPDF\Facade\Pdf;

class DiagnosisController extends Controller
{
    public function __construct(
        private DiagnosisRepository $diagnosisRepository
    ) {}

    public function index()
    {
        return view("dashboard.diagnosis.home", [
            "diagnoses" => $this->diagnosisRepository->getAll()
        ]);
    }

    public function edit($id)
    {
        $diagnosis = $this->diagnosisRepository->getById($id);

        return view("dashboard.diagnosis.edit", [
            "diagnosis" => $diagnosis
        ]);
    }

    public function create()
    {
        return view("dashboard.diagnosis.create");
    }

    public function editAction($id, Request $request)
    {
        $validated = $this->getDiagnosisInputValidation($request);

        $diagnosis = $this->diagnosisRepository->getById($id);

        if (!$diagnosis) {
            abort(404);
        }

        $diagnosis->icdCode = $validated["icd_code"];
        $diagnosis->diseaseName = $validated["disease_name"];

        if (!$this->diagnosisRepository->edit($id, $diagnosis)) {
            return redirect()->route("diagnosis.index")->with("failed", "Failed! Data gagal diedit");
        }
        return redirect()->route("diagnosis.index")->with("success", "Sukses! Data berhasil diedit");
    }

    public function store(Request $request)
    {
        $validated = $this->getDiagnosisInputValidation($request);

        $diagnosisDto = new DiagnosisDTO();
        $diagnosisDto->icdCode = $validated['icd_code'];
        $diagnosisDto->diseaseName = $validated['disease_name'];

        $insertResult = $this->diagnosisRepository->insert($diagnosisDto);

        if (!$insertResult) {
            return redirect()->route("diagnosis.index")->with("failed", "Failed! Data gagal disimpan");
        }

        return redirect()->route("diagnosis.index")->with("success", "Sukses! Data berhasil disimpan");
    }

    public function print()
    {
        $diagnoses = $this->diagnosisRepository->getAll();

        $pdf = Pdf::loadView('pdf.diagnoses', compact('diagnoses'));
        return $pdf->download('master_data_diagnosa.pdf');
    }

    public function destroy($id)
    {
        $diagnosis = $this->diagnosisRepository->getById($id);

        if (!$diagnosis) {
            abort(404);
        }

        if (!$this->diagnosisRepository->delete($id)) {
            return redirect()->route("diagnosis.index")->with("failed", "Failed! Data gagal dihapus");
        }

        return redirect()->route("diagnosis.index")->with("success", "Sukses! Data berhasil dihapus");
    }

    private function getDiagnosisInputValidation(Request $request)
    {
        $validated = $request->validate([
            'icd_code' => [
                'required',
                'string',
                'min:1',
                'max:100'
            ],
            'disease_name' => [
                'required',
                'string',
                'min:5',
                'max:255'
            ]
        ]);

        return $validated;
    }
}
