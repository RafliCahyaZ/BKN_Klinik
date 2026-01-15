<?php

namespace App\Http\Controllers;

use App\DTO\PatientDTO;
use App\Repository\DiagnosisRepository;
use App\Repository\PatientRepository;
use App\Repository\RegistrationRepository;
use App\Repository\TreatmentRepository;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    public function __construct(
        private RegistrationRepository $registrationRepository,
        private UserRepository $userRepository,
        private DiagnosisRepository $diagnosisRepository,
        private TreatmentRepository $treatmentRepository
    ) {}

    public function destroy($id, PatientRepository $patientRepository)
    {
        $patient = $patientRepository->getById($id);

        if (!$patient) {
            abort(404);
        }

        $deleteResult = $patientRepository->delete($id);

        if (!$deleteResult) {
            return redirect()->route("patient.index")->with("failed", "Gagal! Gagal hapus data");
        }

        return redirect()->route("patient.index")->with("success", "Sukses! Berhasil hapus data");
    }

    public function index(PatientRepository $patientRepository)
    {
        return view("dashboard/patient.home", [
            "patients" => $patientRepository->getAll()
        ]);
    }

    public function queue()
    {
        $registrations = $this->registrationRepository->getWhereCheckDateIsNull();
        return view(
            "dashboard/patient.queue",
            [
                "registrations" => $registrations
            ]
        );
    }

    public function history()
    {
        $histories = $this->registrationRepository->getWhereCheckDateIsNotNull();

        return view(
            "dashboard/patient.history",
            [
                "histories" => $histories
            ]
        );
    }

    public function create()
    {
        return view("dashboard.patient.create");
    }

    public function edit(int $id, PatientRepository $patientRepository)
    {
        $patient = $patientRepository->getById($id);

        if (!$patient) {
            abort(404);
        }

        return view("dashboard.patient.edit", [
            "patient" => $patient
        ]);
    }

    public function editAction(int $id, Request $request, PatientRepository $patientRepository)
    {
        $validated = $this->getPatientInputValidation($request);
        $rmUrl = null;

        if ($request->hasFile('medical-record')) {
            $rmUrl = $request->file("medical-record")->store('medical-record', 'public');
            $rmUrl = Storage::disk('public')->url($rmUrl);
        }

        $patientDto = new PatientDTO();
        $patientDto->name = $validated["name"];

        if ($validated["gender"] === "M") {
            $patientDto->gender = "male";
        } else {
            $patientDto->gender = "female";
        }
        $patientDto->address = $validated["address"];
        $patientDto->history = $validated["history"];
        $patientDto->telpNumber = $validated["phone-number"];

        if ($rmUrl) {
            $patientDto->medicalRecordFileUrl = $rmUrl;
        }

        $editResult = $patientRepository->edit($id, $patientDto);

        if (!$editResult) {
            return redirect()->route("patient.index")->with("failed", "Gagal! Data gagal diedit");
        }

        return redirect()->route("patient.index")->with("success", "Sukses! Data berhasil diedit");
    }

    public function print(PatientRepository $patientRepository)
    {
        $patients = $patientRepository->getAll();

        return view("dashboard.patient.print", [
            "patients" => $patients
        ]);
    }

    public function store(Request $request, PatientRepository $patientRepository)
    {
        $validated = $this->getPatientInputValidation($request);
        $rmUrl = null;

        if ($request->hasFile('medical-record')) {
            $rmUrl = $request->file("medical-record")->store('medical-record', 'public');
            $rmUrl = Storage::disk('public')->url($rmUrl);
        }

        $latestPatientId = $patientRepository->getLatestId();

        $medicalRecordNumber = 'MR-' . str_pad($latestPatientId + 1, 6, '0', STR_PAD_LEFT);

        $patientDto = new PatientDTO();
        $patientDto->medicalRecordNumber = $medicalRecordNumber;
        $patientDto->name = $validated["name"];
        if ($validated["gender"] === "M") {
            $patientDto->gender = "male";
        } else {
            $patientDto->gender = "female";
        }
        $patientDto->dateOfBirth = Carbon::parse($validated["date-of-birth"]);
        $patientDto->address = $validated["address"];
        $patientDto->history = $validated["history"];
        $patientDto->telpNumber = $validated["phone-number"];

        $patientDto->medicalRecordFileUrl = ($rmUrl) ?? "";

        $insertResult = $patientRepository->insert($patientDto);

        if ($insertResult) {
            return redirect()->route("patient.index")->with("success", "Sukses! Data berhasil disimpan");
        } else {
            return redirect()->route("patient.index")->with("failed", "Failed! Data gagal disimpan");
        }
    }

    private function getPatientInputValidation(Request $request)
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

        return $validated;
    }
}
