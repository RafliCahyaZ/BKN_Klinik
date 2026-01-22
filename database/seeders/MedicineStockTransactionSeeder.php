<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MedicineStockTransaction;
use App\Models\Medicine;
use App\Models\User;
use App\Models\Registration;
use App\Type\TransactionType;
use Carbon\Carbon;

class MedicineStockTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get inventory officer
        $inventoryOfficer = User::where('role', 'inventory_officer')->first();
        
        // Get medicines
        $paracetamol = Medicine::where('name', 'Paracetamol')->first();
        $amoxicillin = Medicine::where('name', 'Amoxicillin')->first();
        $obhCombi = Medicine::where('name', 'OBH Combi')->first();
        $vitaminB = Medicine::where('name', 'Vitamin B Complex')->first();
        $insulin = Medicine::where('name', 'Insulin')->first();
        
        // Get registrations
        $registration1 = Registration::where('complaint', 'Pusing dan mual')->first();
        $registration3 = Registration::where('complaint', 'Kontrol gula darah')->first();
        $registration4 = Registration::where('complaint', 'Sesak nafas')->first();

        // Transaction 1 - Stock IN (Pembelian Paracetamol)
        MedicineStockTransaction::create([
            'inventory_officer_id' => $inventoryOfficer->id,
            'medicine_id' => $paracetamol->id,
            'registration_id' => null,
            'type' => TransactionType::IN,
            'quantity' => 100,
            'beginning_stock' => 0,
            'last_stock' => 100,
            'expired_date' => Carbon::now()->addYears(2)->toDateString(),
            'notes' => 'Pembelian stok awal',
            'created_at' => Carbon::now()->subDays(30),
        ]);

        // Transaction 2 - Stock OUT (Paracetamol untuk Registration 1)
        MedicineStockTransaction::create([
            'inventory_officer_id' => $inventoryOfficer->id,
            'medicine_id' => $paracetamol->id,
            'registration_id' => $registration1->id,
            'type' => TransactionType::OUT,
            'quantity' => 10,
            'beginning_stock' => 100,
            'last_stock' => 90,
            'expired_date' => null,
            'notes' => 'Obat untuk pasien Budi Santoso',
            'created_at' => Carbon::now()->subDays(5),
        ]);

        // Transaction 3 - Stock IN (Pembelian Amoxicillin)
        MedicineStockTransaction::create([
            'inventory_officer_id' => $inventoryOfficer->id,
            'medicine_id' => $amoxicillin->id,
            'registration_id' => null,
            'type' => TransactionType::IN,
            'quantity' => 50,
            'beginning_stock' => 0,
            'last_stock' => 50,
            'expired_date' => Carbon::now()->addYears(1)->toDateString(),
            'notes' => 'Pembelian stok awal',
            'created_at' => Carbon::now()->subDays(30),
        ]);

        // Transaction 4 - Stock OUT (Amoxicillin untuk Registration 4)
        MedicineStockTransaction::create([
            'inventory_officer_id' => $inventoryOfficer->id,
            'medicine_id' => $amoxicillin->id,
            'registration_id' => $registration4->id,
            'type' => TransactionType::OUT,
            'quantity' => 15,
            'beginning_stock' => 50,
            'last_stock' => 35,
            'expired_date' => null,
            'notes' => 'Obat untuk pasien Dewi Lestari',
            'created_at' => Carbon::now()->subDays(3),
        ]);

        // Transaction 5 - Stock IN (Pembelian OBH Combi)
        MedicineStockTransaction::create([
            'inventory_officer_id' => $inventoryOfficer->id,
            'medicine_id' => $obhCombi->id,
            'registration_id' => null,
            'type' => TransactionType::IN,
            'quantity' => 30,
            'beginning_stock' => 0,
            'last_stock' => 30,
            'expired_date' => Carbon::now()->addMonths(18)->toDateString(),
            'notes' => 'Pembelian stok awal',
            'created_at' => Carbon::now()->subDays(30),
        ]);

        // Transaction 6 - Stock IN (Pembelian Vitamin B Complex)
        MedicineStockTransaction::create([
            'inventory_officer_id' => $inventoryOfficer->id,
            'medicine_id' => $vitaminB->id,
            'registration_id' => null,
            'type' => TransactionType::IN,
            'quantity' => 75,
            'beginning_stock' => 0,
            'last_stock' => 75,
            'expired_date' => Carbon::now()->addYears(3)->toDateString(),
            'notes' => 'Pembelian stok awal',
            'created_at' => Carbon::now()->subDays(30),
        ]);

        // Transaction 7 - Stock OUT (Vitamin B untuk Registration 1)
        MedicineStockTransaction::create([
            'inventory_officer_id' => $inventoryOfficer->id,
            'medicine_id' => $vitaminB->id,
            'registration_id' => $registration1->id,
            'type' => TransactionType::OUT,
            'quantity' => 20,
            'beginning_stock' => 75,
            'last_stock' => 55,
            'expired_date' => null,
            'notes' => 'Vitamin untuk pasien Budi Santoso',
            'created_at' => Carbon::now()->subDays(5),
        ]);

        // Transaction 8 - Stock IN (Pembelian Insulin)
        MedicineStockTransaction::create([
            'inventory_officer_id' => $inventoryOfficer->id,
            'medicine_id' => $insulin->id,
            'registration_id' => null,
            'type' => TransactionType::IN,
            'quantity' => 20,
            'beginning_stock' => 0,
            'last_stock' => 20,
            'expired_date' => Carbon::now()->addMonths(6)->toDateString(),
            'notes' => 'Pembelian stok awal',
            'created_at' => Carbon::now()->subDays(30),
        ]);

        // Transaction 9 - Stock OUT (Insulin untuk Registration 3)
        MedicineStockTransaction::create([
            'inventory_officer_id' => $inventoryOfficer->id,
            'medicine_id' => $insulin->id,
            'registration_id' => $registration3->id,
            'type' => TransactionType::OUT,
            'quantity' => 5,
            'beginning_stock' => 20,
            'last_stock' => 15,
            'expired_date' => null,
            'notes' => 'Insulin untuk pasien Ahmad Hidayat',
            'created_at' => Carbon::now()->subDays(10),
        ]);
    }
}
