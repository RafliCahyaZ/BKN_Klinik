<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Registration;
use App\Models\Patient;
use App\Models\User;
use App\Models\Diagnosis;
use App\Models\Treatment;
use Carbon\Carbon;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get patients
        $patient1 = Patient::where('medical_record_number', 'MR-001')->first();
        $patient2 = Patient::where('medical_record_number', 'MR-002')->first();
        $patient3 = Patient::where('medical_record_number', 'MR-003')->first();
        $patient4 = Patient::where('medical_record_number', 'MR-004')->first();
        
        // Get doctor
        $doctor = User::where('role', 'doctor')->first();
        
        // Get diagnoses
        $hypertension = Diagnosis::where('icd_code', 'I10')->first();
        $cold = Diagnosis::where('icd_code', 'J00')->first();
        $diabetes = Diagnosis::where('icd_code', 'E11')->first();
        $asthma = Diagnosis::where('icd_code', 'J45')->first();
        $headache = Diagnosis::where('icd_code', 'R51')->first();
        
        // Get treatments
        $consultation = Treatment::where('treatment_code', 'T001')->first();
        $bloodPressure = Treatment::where('treatment_code', 'T002')->first();
        $bloodSugar = Treatment::where('treatment_code', 'T003')->first();
        $nebulizer = Treatment::where('treatment_code', 'T006')->first();
        $physicalExam = Treatment::where('treatment_code', 'T008')->first();

        // Registration 1 - Already checked
        $registration1 = Registration::create([
            'patient_id' => $patient1->id,
            'doctor_id' => $doctor->id,
            'complaint' => 'Pusing dan mual',
            'receipt' => 'Istirahat cukup, minum obat teratur',
            'check_date' => Carbon::now()->subDays(5),
        ]);
        $registration1->diagnoses()->attach([$hypertension->id]);
        $registration1->treatments()->attach([$consultation->id, $bloodPressure->id]);

        // Registration 2 - Not yet checked
        $registration2 = Registration::create([
            'patient_id' => $patient2->id,
            'doctor_id' => null,
            'complaint' => 'Batuk dan pilek',
            'receipt' => null,
            'check_date' => null,
        ]);

        // Registration 3 - Already checked
        $registration3 = Registration::create([
            'patient_id' => $patient3->id,
            'doctor_id' => $doctor->id,
            'complaint' => 'Kontrol gula darah',
            'receipt' => 'Diet rendah gula, olahraga teratur',
            'check_date' => Carbon::now()->subDays(10),
        ]);
        $registration3->diagnoses()->attach([$diabetes->id]);
        $registration3->treatments()->attach([$consultation->id, $bloodSugar->id]);

        // Registration 4 - Already checked
        $registration4 = Registration::create([
            'patient_id' => $patient4->id,
            'doctor_id' => $doctor->id,
            'complaint' => 'Sesak nafas',
            'receipt' => 'Hindari pemicu asma, gunakan inhaler',
            'check_date' => Carbon::now()->subDays(3),
        ]);
        $registration4->diagnoses()->attach([$asthma->id]);
        $registration4->treatments()->attach([$consultation->id, $nebulizer->id]);

        // Registration 5 - Not yet checked
        $registration5 = Registration::create([
            'patient_id' => $patient1->id,
            'doctor_id' => null,
            'complaint' => 'Sakit kepala',
            'receipt' => null,
            'check_date' => null,
        ]);
    }
}
