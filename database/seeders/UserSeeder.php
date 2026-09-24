<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'username' => 'farhannivta',
            'password' => Hash::make('AdminPassword123!'),
            'fullname' => 'Farhan Naufal Rivta',
            'email' => 'admin@clinic.com',
            'role' => 'admin',
            'is_active' => true,
            'failed_login_attempts' => 0,
            'last_login_at' => null,
        ]);

        // Doctor User
        User::create([
            'username' => 'dr.smith',
            'password' => Hash::make('DoctorPassword123!'),
            'fullname' => 'Dr. Jane Smith',
            'email' => 'doctor@clinic.com',
            'role' => 'doctor',
            'is_active' => true,
            'failed_login_attempts' => 0,
            'last_login_at' => null,
        ]);

        // Inventory Officer User
        User::create([
            'username' => 'inventory_officer',
            'password' => Hash::make('InventoryPassword123!'),
            'fullname' => 'John Inventory',
            'email' => 'inventory@clinic.com',
            'role' => 'inventory_officer',
            'is_active' => true,
            'failed_login_attempts' => 0,
            'last_login_at' => null,
        ]);

        User::create([
            'username' => 'dr.ahmad',
            'password' => Hash::make('DoctorAhmad123!'),
            'fullname' => 'Dr. Ahmad Rahman',
            'email' => 'ahmad@clinic.com',
            'role' => 'doctor',
            'is_active' => true,
            'failed_login_attempts' => 0,
            'last_login_at' => null,
        ]);

        User::create([
            'username' => 'man united menager',
            'password' => Hash::make('DoctorAhmad123!'),
            'fullname' => 'Dr. Ahmad Rahman',
            'email' => 'man@clinic.com',
            'role' => 'doctor',
            'is_active' => false,
            'failed_login_attempts' => 3,
            'last_login_at' => null,
        ]);

        // Additional Inventory Officer
        User::create([
            'username' => 'inventory_officer2',
            'password' => Hash::make('InventorySarah123!'),
            'fullname' => 'Sarah Inventory',
            'email' => 'sarah@clinic.com',
            'role' => 'inventory_officer',
            'is_active' => true,
            'failed_login_attempts' => 0,
            'last_login_at' => null,
        ]);

        // Officer User
        User::create([
            'username' => 'officer_john',
            'password' => Hash::make('OfficerPassword123!'),
            'fullname' => 'John Officer',
            'email' => 'officer@clinic.com',
            'role' => 'officer',
            'is_active' => true,
            'failed_login_attempts' => 0,
            'last_login_at' => null,
        ]);
    }
}
