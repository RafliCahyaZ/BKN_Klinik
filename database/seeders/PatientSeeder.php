<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;
use Carbon\Carbon;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Patient::create([
            'fullname' => 'Budi Santoso',
            'gender' => 'male',
            'address' => 'Jl. Malioboro No. 10, Yogyakarta',
            'date_of_birth' => Carbon::now()->subYears(34)->toDateString(),
            'telp_number' => '081234567890',
            'history' => 'Riwayat hipertensi',
            'medical_record_file_url' => 'medical-records/budi-santoso.pdf',
            'medical_record_number' => 'MR-001',
        ]);

        Patient::create([
            'fullname' => 'Siti Aminah',
            'gender' => 'female',
            'address' => 'Jl. Kaliurang Km 7, Sleman',
            'date_of_birth' => Carbon::now()->subYears(28)->toDateString(),
            'telp_number' => '082198765432',
            'history' => "Riwayat hipertensi",
            'medical_record_file_url' => 'medical-records/siti-aminah.pdf',
            'medical_record_number' => 'MR-002',
        ]);

        Patient::create([
            'fullname' => 'Ahmad Hidayat',
            'gender' => 'male',
            'address' => 'Jl. Sudirman No. 45, Jakarta',
            'date_of_birth' => Carbon::now()->subYears(45)->toDateString(),
            'telp_number' => '085612345678',
            'history' => 'Riwayat diabetes mellitus',
            'medical_record_file_url' => 'medical-records/ahmad-hidayat.pdf',
            'medical_record_number' => 'MR-003',
        ]);

        Patient::create([
            'fullname' => 'Dewi Lestari',
            'gender' => 'female',
            'address' => 'Jl. Diponegoro No. 20, Bandung',
            'date_of_birth' => Carbon::now()->subYears(32)->toDateString(),
            'telp_number' => '081298765432',
            'history' => 'Riwayat asma',
            'medical_record_file_url' => 'medical-records/dewi-lestari.pdf',
            'medical_record_number' => 'MR-004',
        ]);

        Patient::create([
            'fullname' => 'Rudi Hermawan',
            'gender' => 'male',
            'address' => 'Jl. Ahmad Yani No. 88, Surabaya',
            'date_of_birth' => Carbon::now()->subYears(50)->toDateString(),
            'telp_number' => '087654321098',
            'history' => 'Riwayat jantung koroner',
            'medical_record_file_url' => 'medical-records/rudi-hermawan.pdf',
            'medical_record_number' => 'MR-005',
        ]);
    }
}
