<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User (dari factory)
        User::create([
            'username' => 'farhannivta',
            'password' => Hash::make('AdminPassword123!'),
            'fullname' => 'Farhan Naufal Rivta',
            'email' => 'admin@clinic.com',
            'role' => 'admin',
        ]);

        // Doctor User
        User::create([
            'username' => 'dr.smith',
            'password' => Hash::make('DoctorPassword123!'),
            'fullname' => 'Dr. Jane Smith',
            'email' => 'doctor@clinic.com',
            'role' => 'doctor',
        ]);

        // Inventory Officer User
        User::create([
            'username' => 'inventory_officer',
            'password' => Hash::make('InventoryPassword123!'),
            'fullname' => 'John Inventory',
            'email' => 'inventory@clinic.com',
            'role' => 'inventory_officer',
        ]);

        // Additional Doctor
        User::create([
            'username' => 'dr.ahmad',
            'password' => Hash::make('DoctorAhmad123!'),
            'fullname' => 'Dr. Ahmad Rahman',
            'email' => 'ahmad@clinic.com',
            'role' => 'doctor',
        ]);

        // Additional Inventory Officer
        User::create([
            'username' => 'inventory_officer2',
            'password' => Hash::make('InventorySarah123!'),
            'fullname' => 'Sarah Inventory',
            'email' => 'sarah@clinic.com',
            'role' => 'inventory_officer',
        ]);
    }
}
