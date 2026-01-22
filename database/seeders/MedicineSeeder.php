<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Medicine;
use App\Models\Unit;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tablet = Unit::where('name', 'Tablet')->first();
        $capsule = Unit::where('name', 'Capsule')->first();
        $syrup = Unit::where('name', 'Syrup')->first();
        $injection = Unit::where('name', 'Injection')->first();
        $ointment = Unit::where('name', 'Ointment')->first();

        Medicine::create([
            'name' => 'Paracetamol',
            'stock' => 100,
            'unit_id' => $tablet->id,
        ]);

        Medicine::create([
            'name' => 'Amoxicillin',
            'stock' => 50,
            'unit_id' => $capsule->id,
        ]);

        Medicine::create([
            'name' => 'OBH Combi',
            'stock' => 30,
            'unit_id' => $syrup->id,
        ]);

        Medicine::create([
            'name' => 'Vitamin B Complex',
            'stock' => 75,
            'unit_id' => $tablet->id,
        ]);

        Medicine::create([
            'name' => 'Cetirizine',
            'stock' => 60,
            'unit_id' => $tablet->id,
        ]);

        Medicine::create([
            'name' => 'Insulin',
            'stock' => 20,
            'unit_id' => $injection->id,
        ]);

        Medicine::create([
            'name' => 'Salep 88',
            'stock' => 40,
            'unit_id' => $ointment->id,
        ]);

        Medicine::create([
            'name' => 'Ibuprofen',
            'stock' => 80,
            'unit_id' => $tablet->id,
        ]);
    }
}
