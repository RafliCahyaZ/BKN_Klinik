<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::create(['name' => 'Tablet']);
        Unit::create(['name' => 'Capsule']);
        Unit::create(['name' => 'Syrup']);
        Unit::create(['name' => 'Injection']);
        Unit::create(['name' => 'Ointment']);
    }
}
