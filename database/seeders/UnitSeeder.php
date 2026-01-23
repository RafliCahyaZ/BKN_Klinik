<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;

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