<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\Unit;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed dalam urutan yang benar (sesuai dengan dependensi)
        $this->call([
            UnitSeeder::class,              // 1. Unit harus pertama (untuk Medicine)
            UserSeeder::class,              // 2. User (admin, doctor, inventory_officer)
            PatientSeeder::class,           // 3. Patient (untuk Registration)
            DiagnosisSeeder::class,         // 4. Diagnosis (untuk Registration)
            TreatmentSeeder::class,         // 5. Treatment (untuk Registration)
            MedicineSeeder::class,          // 6. Medicine (memerlukan Unit dan User sudah ada)
            RegistrationSeeder::class,      // 7. Registration (memerlukan Patient, User/Doctor, Diagnosis, Treatment)
            MedicineStockTransactionSeeder::class,  // 8. Medicine Stock Transaction (memerlukan Medicine, User/Inventory Officer, Registration)
        ]);
    }
}
