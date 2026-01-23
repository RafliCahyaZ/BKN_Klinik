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
            'password' => Hash::make('password'),
            'fullname' => 'Farhan Naufal Rivta',
            'email' => 'admin@clinic.com',
            'role' => 'admin',
        ]);

        // Doctor User
        User::create([
            'username' => 'dr.smith',
            'password' => Hash::make('password'),
            'fullname' => 'Dr. Jane Smith',
            'email' => 'doctor@clinic.com',
            'role' => 'doctor',
        ]);

        // Inventory Officer User
        User::create([
            'username' => 'inventory_officer',
            'password' => Hash::make('password'),
            'fullname' => 'John Inventory',
            'email' => 'inventory@clinic.com',
            'role' => 'inventory_officer',
        ]);

        // Additional Doctor
        User::create([
            'username' => 'dr.ahmad',
            'password' => Hash::make('password'),
            'fullname' => 'Dr. Ahmad Rahman',
            'email' => 'ahmad@clinic.com',
            'role' => 'doctor',
        ]);

        // Additional Inventory Officer
        User::create([
            'username' => 'inventory_officer2',
            'password' => Hash::make('password'),
            'fullname' => 'Sarah Inventory',
            'email' => 'sarah@clinic.com',
            'role' => 'inventory_officer',
        ]);
    }
}
