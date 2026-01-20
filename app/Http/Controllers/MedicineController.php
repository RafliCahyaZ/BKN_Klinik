<?php

namespace App\Http\Controllers;

use App\Repository\MedicineRepository;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function __construct(
        private MedicineRepository $medicineRepository
    ) {}

    public function index()
    {
        $medicines = $this->medicineRepository->getAll();
        return view("dashboard.medicine.home", [
            "medicines" => $medicines
        ]);
        /* return view("dashboard.treatment.home", [ */
        /*     "treatments" => $treatmentRepository->getAll() */
        /* ]); */
    }

    public function edit($id)
    {
        /* $treatment = $this->treatmentRepository->getById($id); */
        /**/
        /* return view("dashboard.treatment.edit", [ */
        /*     "treatment" => $treatment */
        /* ]); */
    }

    public function editAction($id, Request $request)
    {
        /* $validated = $this->getTreatmentInputValidation($request); */
        /**/
        /* $treatment = $this->treatmentRepository->getById($id); */
        /**/
        /* if (!$treatment) { */
        /*     abort(404); */
        /* } */
        /**/
        /* $treatment->treatmentCode = $validated["icd_code"]; */
        /* $treatment->treatmentName = $validated["treatment_name"]; */
        /**/
        /* if (!$this->treatmentRepository->edit($id, $treatment)) { */
        /*     return redirect()->route("treatment.index")->with("failed", "Failed! Data gagal diedit"); */
        /* } */
        /* return redirect()->route("treatment.index")->with("success", "Sukses! Data berhasil diedit"); */
    }

    public function create()
    {
        return view("dashboard.medicine.create");
    }

    public function destroy($id)
    {
        /* $treatment = $this->treatmentRepository->getById($id); */
        /**/
        /* if (!$treatment) { */
        /*     abort(404); */
        /* } */
        /**/
        /* if (!$this->treatmentRepository->delete($id)) { */
        /*     return redirect()->route("treatment.index")->with("failed", "Failed! Data gagal dihapus"); */
        /* } */
        /**/
        /* return redirect()->route("treatment.index")->with("success", "Sukses! Data berhasil dihapus"); */
    }


    public function store(Request $request)
    {
        /* $validated = $this->getTreatmentInputValidation($request); */
        /**/
        /* $treatmentDto = new TreatmentDTO(); */
        /* $treatmentDto->treatmentCode = $validated["icd_code"]; */
        /* $treatmentDto->treatmentName = $validated["treatment_name"]; */
        /**/
        /* if (!$this->treatmentRepository->insert($treatmentDto)) { */
        /*     return redirect()->route("treatment.index")->with("failed", "Failed! Data gagal disimpan"); */
        /* } */
        /**/
        /* return redirect()->route("treatment.index")->with("success", "Sukses! Data berhasil disimpan"); */
    }
}
