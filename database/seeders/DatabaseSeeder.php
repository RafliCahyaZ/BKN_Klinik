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
        $this->call([
            UnitSeeder::class,          
            UserSeeder::class,
            PatientSeeder::class,           
            DiagnosisSeeder::class,         
            TreatmentSeeder::class,         
            MedicineSeeder::class,
            RegistrationSeeder::class,  
            MedicineStockTransactionSeeder::class,
            RolePermissionSeeder::class,    
        ]);
    }
}
