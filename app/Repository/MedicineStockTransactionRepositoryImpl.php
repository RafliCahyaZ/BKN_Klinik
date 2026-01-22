<?php

namespace App\Repository;

use App\DTO\MedicineStockTransactionDTO;
use App\Type\TransactionType;
use Carbon\Carbon;

class MedicineStockTransactionRepositoryImpl implements MedicineStockTransactionRepository
{
    public function getAll(): array
    {
        // 1 - Transaksi masuk (pembelian)
        $transaction1 = new MedicineStockTransactionDTO();
        $transaction1->id = '1';
        $transaction1->inventoryOfficerId = '1';
        $transaction1->medicineId = '1';
        $transaction1->registrationId = null;
        $transaction1->type = 'in';
        $transaction1->quantity = 100;
        $transaction1->beginningStock = 0;
        $transaction1->lastStock = 100;
        $transaction1->expiredDate = Carbon::parse('2027-12-31');
        $transaction1->notes = 'Pembelian stok awal Paracetamol 500mg';
        $transaction1->createdAt = Carbon::now()->subDays(30);
        $transaction1->updatedAt = Carbon::now()->subDays(30);

        // 2 - Transaksi keluar (penggunaan)
        $transaction2 = new MedicineStockTransactionDTO();
        $transaction2->id = '2';
        $transaction2->inventoryOfficerId = '1';
        $transaction2->medicineId = '1';
        $transaction2->registrationId = '1';
        $transaction2->type = 'out';
        $transaction2->quantity = 20;
        $transaction2->beginningStock = 100;
        $transaction2->lastStock = 80;
        $transaction2->expiredDate = null;
        $transaction2->notes = 'Penggunaan untuk pasien Budi Santoso';
        $transaction2->createdAt = Carbon::now()->subDays(25);
        $transaction2->updatedAt = Carbon::now()->subDays(25);

        // 3 - Transaksi masuk (restok)
        $transaction3 = new MedicineStockTransactionDTO();
        $transaction3->id = '3';
        $transaction3->inventoryOfficerId = '1';
        $transaction3->medicineId = '2';
        $transaction3->registrationId = null;
        $transaction3->type = 'in';
        $transaction3->quantity = 50;
        $transaction3->beginningStock = 10;
        $transaction3->lastStock = 60;
        $transaction3->expiredDate = Carbon::parse('2028-06-30');
        $transaction3->notes = 'Restok Amoxicillin 500mg';
        $transaction3->createdAt = Carbon::now()->subDays(20);
        $transaction3->updatedAt = Carbon::now()->subDays(20);

        // 4 - Transaksi keluar (penggunaan)
        $transaction4 = new MedicineStockTransactionDTO();
        $transaction4->id = '4';
        $transaction4->inventoryOfficerId = '1';
        $transaction4->medicineId = '2';
        $transaction4->registrationId = '2';
        $transaction4->type = 'out';
        $transaction4->quantity = 15;
        $transaction4->beginningStock = 60;
        $transaction4->lastStock = 45;
        $transaction4->expiredDate = null;
        $transaction4->notes = 'Penggunaan untuk pasien Siti Aminah';
        $transaction4->createdAt = Carbon::now()->subDays(15);
        $transaction4->updatedAt = Carbon::now()->subDays(15);

        // 5 - Transaksi masuk (pembelian baru)
        $transaction5 = new MedicineStockTransactionDTO();
        $transaction5->id = '5';
        $transaction5->inventoryOfficerId = '1';
        $transaction5->medicineId = '3';
        $transaction5->registrationId = null;
        $transaction5->type = 'in';
        $transaction5->quantity = 75;
        $transaction5->beginningStock = 0;
        $transaction5->lastStock = 75;
        $transaction5->expiredDate = Carbon::parse('2027-09-15');
        $transaction5->notes = 'Pembelian stok awal Ibuprofen 400mg';
        $transaction5->createdAt = Carbon::now()->subDays(10);
        $transaction5->updatedAt = Carbon::now()->subDays(10);

        // 6 - Transaksi keluar (penyesuaian stok)
        $transaction6 = new MedicineStockTransactionDTO();
        $transaction6->id = '6';
        $transaction6->inventoryOfficerId = '1';
        $transaction6->medicineId = '1';
        $transaction6->registrationId = '3';
        $transaction6->type = 'out';
        $transaction6->quantity = 10;
        $transaction6->beginningStock = 80;
        $transaction6->lastStock = 70;
        $transaction6->expiredDate = null;
        $transaction6->notes = 'Penggunaan untuk pasien Ahmad Fauzi';
        $transaction6->createdAt = Carbon::now()->subDays(5);
        $transaction6->updatedAt = Carbon::now()->subDays(5);

        // 7 - Transaksi masuk (restok besar)
        $transaction7 = new MedicineStockTransactionDTO();
        $transaction7->id = '7';
        $transaction7->inventoryOfficerId = '1';
        $transaction7->medicineId = '4';
        $transaction7->registrationId = null;
        $transaction7->type = 'in';
        $transaction7->quantity = 200;
        $transaction7->beginningStock = 50;
        $transaction7->lastStock = 250;
        $transaction7->expiredDate = Carbon::parse('2029-03-31');
        $transaction7->notes = 'Pembelian bulk Vitamin C 1000mg';
        $transaction7->createdAt = Carbon::now()->subDays(3);
        $transaction7->updatedAt = Carbon::now()->subDays(3);

        // 8 - Transaksi keluar (penggunaan)
        $transaction8 = new MedicineStockTransactionDTO();
        $transaction8->id = '8';
        $transaction8->inventoryOfficerId = '1';
        $transaction8->medicineId = '3';
        $transaction8->registrationId = '4';
        $transaction8->type = 'out';
        $transaction8->quantity = 25;
        $transaction8->beginningStock = 75;
        $transaction8->lastStock = 50;
        $transaction8->expiredDate = null;
        $transaction8->notes = 'Penggunaan untuk pasien Dewi Lestari';
        $transaction8->createdAt = Carbon::now()->subDays(2);
        $transaction8->updatedAt = Carbon::now()->subDays(2);

        return [$transaction1, $transaction2, $transaction3, $transaction4, $transaction5, $transaction6, $transaction7, $transaction8];
    }

    public function getById(string $id): ?MedicineStockTransactionDTO
    {
        $transaction = new MedicineStockTransactionDTO();
        $transaction->id = $id;
        $transaction->inventoryOfficerId = '1';
        $transaction->medicineId = '1';
        $transaction->registrationId = null;
        $transaction->type = 'in';
        $transaction->quantity = 100;
        $transaction->beginningStock = 0;
        $transaction->lastStock = 100;
        $transaction->expiredDate = Carbon::parse('2027-12-31');
        $transaction->notes = 'Pembelian stok awal Paracetamol 500mg';
        $transaction->createdAt = Carbon::now()->subDays(30);
        $transaction->updatedAt = Carbon::now()->subDays(30);

        return $transaction;
    }

    public function insert(MedicineStockTransactionDTO $medicineStockTransactionDTO): ?MedicineStockTransactionDTO
    {
        $medicineStockTransactionDTO->id = '9';
        $medicineStockTransactionDTO->createdAt = Carbon::now();
        $medicineStockTransactionDTO->updatedAt = Carbon::now();

        return $medicineStockTransactionDTO;
    }

    public function insertMany(array $medicineStockTransactionDTOs): bool
    {
        // Simulasi insert many berhasil
        return true;
    }

    public function edit(string $id, MedicineStockTransactionDTO $medicineStockTransactionDTO): ?MedicineStockTransactionDTO
    {
        $medicineStockTransactionDTO->id = $id;
        $medicineStockTransactionDTO->updatedAt = Carbon::now();

        return $medicineStockTransactionDTO;
    }

    public function getLatestId(): int
    {
        return 0;
    }

    public function delete(string $id): bool
    {
        return true;
    }

    public function getByMedicineId(string $medicineId): array
    {
        // Return transaksi berdasarkan medicine ID
        $transactions = [];

        if ($medicineId === '1') {
            // Transaksi untuk Paracetamol
            $transaction1 = new MedicineStockTransactionDTO();
            $transaction1->id = '1';
            $transaction1->inventoryOfficerId = '1';
            $transaction1->medicineId = '1';
            $transaction1->registrationId = null;
            $transaction1->type = 'in';
            $transaction1->quantity = 100;
            $transaction1->beginningStock = 0;
            $transaction1->lastStock = 100;
            $transaction1->expiredDate = Carbon::parse('2027-12-31');
            $transaction1->notes = 'Pembelian stok awal Paracetamol 500mg';
            $transaction1->createdAt = Carbon::now()->subDays(30);
            $transaction1->updatedAt = Carbon::now()->subDays(30);

            $transaction2 = new MedicineStockTransactionDTO();
            $transaction2->id = '2';
            $transaction2->inventoryOfficerId = '1';
            $transaction2->medicineId = '1';
            $transaction2->registrationId = '1';
            $transaction2->type = 'out';
            $transaction2->quantity = 20;
            $transaction2->beginningStock = 100;
            $transaction2->lastStock = 80;
            $transaction2->expiredDate = null;
            $transaction2->notes = 'Penggunaan untuk pasien Budi Santoso';
            $transaction2->createdAt = Carbon::now()->subDays(25);
            $transaction2->updatedAt = Carbon::now()->subDays(25);

            $transactions = [$transaction1, $transaction2];
        }

        return $transactions;
    }

    public function getByType(TransactionType $type): array
    {
        $transactions = [];

        if ($type === 'in') {
            // Return transaksi tipe masuk
            $transaction1 = new MedicineStockTransactionDTO();
            $transaction1->id = '1';
            $transaction1->inventoryOfficerId = '1';
            $transaction1->medicineId = '1';
            $transaction1->registrationId = null;
            $transaction1->type = 'in';
            $transaction1->quantity = 100;
            $transaction1->beginningStock = 0;
            $transaction1->lastStock = 100;
            $transaction1->expiredDate = Carbon::parse('2027-12-31');
            $transaction1->notes = 'Pembelian stok awal Paracetamol 500mg';
            $transaction1->createdAt = Carbon::now()->subDays(30);
            $transaction1->updatedAt = Carbon::now()->subDays(30);

            $transaction3 = new MedicineStockTransactionDTO();
            $transaction3->id = '3';
            $transaction3->inventoryOfficerId = '1';
            $transaction3->medicineId = '2';
            $transaction3->registrationId = null;
            $transaction3->type = 'in';
            $transaction3->quantity = 50;
            $transaction3->beginningStock = 10;
            $transaction3->lastStock = 60;
            $transaction3->expiredDate = Carbon::parse('2028-06-30');
            $transaction3->notes = 'Restok Amoxicillin 500mg';
            $transaction3->createdAt = Carbon::now()->subDays(20);
            $transaction3->updatedAt = Carbon::now()->subDays(20);

            $transactions = [$transaction1, $transaction3];
        } else {
            // Return transaksi tipe keluar
            $transaction2 = new MedicineStockTransactionDTO();
            $transaction2->id = '2';
            $transaction2->inventoryOfficerId = '1';
            $transaction2->medicineId = '1';
            $transaction2->registrationId = '1';
            $transaction2->type = 'out';
            $transaction2->quantity = 20;
            $transaction2->beginningStock = 100;
            $transaction2->lastStock = 80;
            $transaction2->expiredDate = null;
            $transaction2->notes = 'Penggunaan untuk pasien Budi Santoso';
            $transaction2->createdAt = Carbon::now()->subDays(25);
            $transaction2->updatedAt = Carbon::now()->subDays(25);

            $transaction4 = new MedicineStockTransactionDTO();
            $transaction4->id = '4';
            $transaction4->inventoryOfficerId = '1';
            $transaction4->medicineId = '2';
            $transaction4->registrationId = '2';
            $transaction4->type = 'out';
            $transaction4->quantity = 15;
            $transaction4->beginningStock = 60;
            $transaction4->lastStock = 45;
            $transaction4->expiredDate = null;
            $transaction4->notes = 'Penggunaan untuk pasien Siti Aminah';
            $transaction4->createdAt = Carbon::now()->subDays(15);
            $transaction4->updatedAt = Carbon::now()->subDays(15);

            $transactions = [$transaction2, $transaction4];
        }

        return $transactions;
    }
}
