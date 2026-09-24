<?php

namespace Database\Seeders;

use App\Models\Diagnosis;
use Illuminate\Database\Seeder;

class DiagnosisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Diagnosis::create([
            'icd_code' => 'I10',
            'disease_name' => 'Essential (primary) hypertension',
        ]);

        Diagnosis::create([
            'icd_code' => 'E11',
            'disease_name' => 'Type 2 diabetes mellitus',
        ]);

        Diagnosis::create([
            'icd_code' => 'J00',
            'disease_name' => 'Acute nasopharyngitis (common cold)',
        ]);

        Diagnosis::create([
            'icd_code' => 'J45',
            'disease_name' => 'Asthma',
        ]);

        Diagnosis::create([
            'icd_code' => 'A09',
            'disease_name' => 'Gastroenteritis and colitis',
        ]);

        Diagnosis::create([
            'icd_code' => 'R50',
            'disease_name' => 'Fever of other and unknown origin',
        ]);

        Diagnosis::create([
            'icd_code' => 'M79.3',
            'disease_name' => 'Myalgia',
        ]);

        Diagnosis::create([
            'icd_code' => 'R51',
            'disease_name' => 'Headache',
        ]);

        Diagnosis::create([
            'icd_code' => 'J06.9',
            'disease_name' => 'Acute upper respiratory infection',
        ]);

        Diagnosis::create([
            'icd_code' => 'K30',
            'disease_name' => 'Dyspepsia',
        ]);
    }
}
