<?php

namespace App\Http\Controllers;

use App\DTO\PatientDTO;
use App\Repository\PatientRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    public function index(PatientRepository $patientRepository)
    {
        return view("dashboard/patient.home", [
            "patients" => $patientRepository->getAll()
        ]);
    }

    public function queue()
    {
        return view("dashboard/patient.queue");
    }

    public function history()
    {
        return view("dashboard/patient.history");
    }

    public function create()
    {
        return view("dashboard.patient.create");
    }

    public function store(Request $request, PatientRepository $patientRepository)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],

            'date-of-birth' => [
                'required',
                'date',
                'before:today',
            ],

            'gender' => [
                'required',
                'in:M,F',
            ],

            'address' => [
                'required',
                'string',
            ],

            'history' => [
                'nullable',
                'string',
            ],

            'phone-number' => [
                'required',
                'digits_between:10,15',
            ],

            'medical-record' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:2048', // 2MB
            ],
        ]);


        $rmUrl = $request->file("medical-record")->store('medical-record', 'public');
        $rmUrl = Storage::disk('public')->url($rmUrl);

        $latestPatientId = $patientRepository->getLatestId();

        $medicalRecordNumber = 'MR-' . str_pad($latestPatientId, 6, '0', STR_PAD_LEFT);

        $patientDto = new PatientDTO();
        $patientDto->medicalRecordNumber = $medicalRecordNumber;
        $patientDto->name = $validated["name"];
        $patientDto->gender = $validated["gender"];
        $patientDto->address = $validated["address"];
        $patientDto->history = $validated["history"];
        $patientDto->telpNumber = $validated["phone-number"];
        $patientDto->medicalRecordFileUrl = $rmUrl;

        $insertResult = $patientRepository->insert($patientDto);

        if ($insertResult) {
            return redirect()->route("patient.index")->with("success", "Sukses! Data berhasil disimpan");
        } else {
            return redirect()->route("patient.index")->with("failed", "Failed! Data gagal disimpan");
        }
    }
}
