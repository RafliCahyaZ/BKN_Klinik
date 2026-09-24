<?php

namespace Database\Seeders;

use App\Models\Treatment;
use Illuminate\Database\Seeder;

class TreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Treatment::create([
            'treatment_code' => 'T001',
            'treatment_name' => 'Konsultasi Dokter Umum',
        ]);

        Treatment::create([
            'treatment_code' => 'T002',
            'treatment_name' => 'Pemeriksaan Tekanan Darah',
        ]);

        Treatment::create([
            'treatment_code' => 'T003',
            'treatment_name' => 'Pemeriksaan Gula Darah',
        ]);

        Treatment::create([
            'treatment_code' => 'T004',
            'treatment_name' => 'Pemberian Infus',
        ]);

        Treatment::create([
            'treatment_code' => 'T005',
            'treatment_name' => 'Injeksi Intramuskular',
        ]);

        Treatment::create([
            'treatment_code' => 'T006',
            'treatment_name' => 'Nebulizer',
        ]);

        Treatment::create([
            'treatment_code' => 'T007',
            'treatment_name' => 'Perawatan Luka',
        ]);

        Treatment::create([
            'treatment_code' => 'T008',
            'treatment_name' => 'Pemeriksaan Fisik',
        ]);
    }
}
