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
        // User::factory(10)->create();
        $this->call(
            [
                UnitSeeder::class,
            ]
        );
        User::factory(1)->create();
        /* Patient::factory(100)->create(); */
    }
}
