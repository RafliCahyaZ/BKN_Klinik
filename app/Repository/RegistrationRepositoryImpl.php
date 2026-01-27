<?php

namespace App\Repository;

use App\DTO\PatientDTO;
use App\DTO\RegistrationDTO;
use App\DTO\MedicineStockTransactionDTO;
use App\DTO\UserDTO;
use App\DTO\MedicineDTO;
use App\DTO\UnitDTO;
use App\Type\TransactionType;
use App\Type\UserRole;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class RegistrationRepositoryImpl implements RegistrationRepository
{
    public function getByPatientId(string $patientId): array
    {
        $all = $this->getAll();

        $results = [];

        foreach ($all as $registration) {
            if ($registration->patient->id === $patientId) {
                $results[] = $registration;
            }
        }

        return $results ?? null;
    }
    public function getAll(): array
    {
        // ================= USER (Inventory Officer) =================
        $officer = new UserDTO();
        $officer->id = '1';
        $officer->username = 'petugas1';
        $officer->password = 'hashed-password';
        $officer->fullname = 'Petugas Apotek';
        $officer->email = 'apoteker@example.com';
        $officer->role = UserRole::ADMIN;
        $officer->createdAt = Carbon::now()->subMonths(6);
        $officer->updatedAt = Carbon::now();

        // ================= MEDICINE =================
        $medicine = new MedicineDTO();
        $medicine->id = '1';
        $medicine->name = 'Paracetamol';

        // ================= PATIENT 1 =================
        $patient1 = new PatientDTO();
        $patient1->id = '1';
        $patient1->medicalRecordNumber = 'MR-001';
        $patient1->medicalRecordFileUrl = 'MR-001';
        $patient1->name = 'Budi Santoso';
        $patient1->gender = 'L';
        $patient1->dateOfBirth = Carbon::now()->subYears(34);
        $patient1->age = 34;
        $patient1->history = 'Riwayat hipertensi';
        $patient1->address = 'Jl. Malioboro No. 10, Yogyakarta';
        $patient1->telpNumber = '081234567890';
        $patient1->createdAt = Carbon::now()->subDays(10);
        $patient1->updatedAt = Carbon::now()->subDays(2);

        // ================= REGISTRATION 1 =================
        $inventoryOfficer = new UserDTO();
        $inventoryOfficer->id = '1';
        $inventoryOfficer->username = 'inventory_officer1';
        $inventoryOfficer->email = 'inventory@example.com';
        $inventoryOfficer->fullname = 'Inventory Officer';
        $inventoryOfficer->role = UserRole::INVENTORY_OFFICER;
        $inventoryOfficer->createdAt = Carbon::now()->subDays(30);
        $inventoryOfficer->updatedAt = Carbon::now()->subDays(30);

        $unit = new UnitDTO();
        $unit->id = '1';
        $unit->name = 'Tablet';

        $medicine = new MedicineDTO();
        $medicine->id = '1';
        $medicine->name = 'Paracetamol';
        $medicine->stock = 100;
        $medicine->unit = $unit;
        $medicine->createdAt = Carbon::now()->subDays(60);
        $medicine->updatedAt = Carbon::now()->subDays(60);

        $medicineTransaction1 = new MedicineStockTransactionDTO();
        $medicineTransaction1->id = '1';
        $medicineTransaction1->inventoryOfficer = $inventoryOfficer;
        $medicineTransaction1->medicine = $medicine;
        $medicineTransaction1->registration = null; // Changed from string to null
        $medicineTransaction1->type = TransactionType::OUT;
        $medicineTransaction1->quantity = 2;
        $medicineTransaction1->beginningStock = 100;
        $medicineTransaction1->lastStock = 98;
        $medicineTransaction1->expiredDate = Carbon::now()->addYears(2);
        $medicineTransaction1->notes = 'Obat untuk pasien Budi Santoso';
        $medicineTransaction1->createdAt = Carbon::now()->subDays(5);
        $medicineTransaction1->updatedAt = Carbon::now()->subDays(5);

        $registration1 = new RegistrationDTO();
        $registration1->id = '1';
        $registration1->patient = $patient1;
        $registration1->complaint = 'Pusing dan mual';
        $registration1->receipt = 'RCPT-001';
        $registration1->checkDate = Carbon::parse('2024-01-15 09:00:00');
        $registration1->createdAt = Carbon::now()->subDays(5);
        $registration1->updatedAt = Carbon::now()->subDays(1);

        // ================= MEDICINE TRANSACTION =================
        $transaction1 = new MedicineStockTransactionDTO();
        $transaction1->id = '1';
        $transaction1->inventoryOfficer = $officer;
        $transaction1->medicine = $medicine;
        $transaction1->registration = $registration1;
        $transaction1->type = TransactionType::OUT;
        $transaction1->quantity = 2;
        $transaction1->beginningStock = 100;
        $transaction1->lastStock = 98;
        $transaction1->expiredDate = Carbon::now()->addYears(2);
        $transaction1->notes = 'Obat untuk pasien Budi Santoso';
        $transaction1->createdAt = Carbon::now()->subDays(5);
        $transaction1->updatedAt = Carbon::now()->subDays(5);

        $registration1->medicineStockTransactions = [$transaction1];

        // ================= PATIENT 2 =================
        $patient2 = new PatientDTO();
        $patient2->id = '2';
        $patient2->medicalRecordNumber = 'MR-002';
        $patient2->medicalRecordFileUrl = 'MR-002';
        $patient2->name = 'Siti Aminah';
        $patient2->gender = 'P';
        $patient2->dateOfBirth = Carbon::now()->subYears(28);
        $patient2->age = 28;
        $patient2->history = null;
        $patient2->address = 'Jl. Kaliurang Km 7, Sleman';
        $patient2->telpNumber = '082198765432';
        $patient2->createdAt = Carbon::now()->subDays(20);
        $patient2->updatedAt = Carbon::now()->subDays(5);

        // ================= REGISTRATION 2 =================
        $registration2 = new RegistrationDTO();
        $registration2->id = '2';
        $registration2->patient = $patient2;
        $registration2->complaint = 'Batuk dan demam';
        $registration2->receipt = null;
        $registration2->medicineStockTransactions = [];
        $registration2->checkDate = null;
        $registration2->createdAt = Carbon::now()->subDays(3);
        $registration2->updatedAt = Carbon::now()->subDays(1);

        // ================= RETURN =================
        return [$registration1, $registration2];
    }

    public function getById(string $id): ?RegistrationDTO
    {
        $all = $this->getAll();

        return $all[0] ?? null;
    }

    public function edit(RegistrationDTO $registrationDTO): ?RegistrationDTO
    {
        return $registrationDTO;
    }

    public function insert(RegistrationDTO $registrationDTO): ?RegistrationDTO
    {

        if (!isset($registrationDTO->id)) {

            $nextId = $this->getLatestId() + 1;
            $registrationDTO->id = (string) $nextId;
        }

        if (!isset($registrationDTO->createdAt)) {
            $registrationDTO->createdAt = Carbon::now();
        }

        $registrationDTO->updatedAt = Carbon::now();

        return $registrationDTO;
    }

    public function getLatestId(): int
    {

        $all = $this->getAll();
        if (empty($all)) {
            return 0;
        }


        $ids = array_map(fn(RegistrationDTO $r) => (int) $r->id, $all);

        return max($ids);
    }

    public function delete(string $id): bool
    {

        return true;
    }

    public function getWhereCheckDateIsNull(): array
    {
        $patient1 = new PatientDTO();
        $patient1->id = '1';
        $patient1->medicalRecordNumber = 'MR-001';
        $patient1->medicalRecordFileUrl = 'MR-001';
        $patient1->name = 'Budi Santoso';
        $patient1->gender = 'L';
        $patient1->dateOfBirth = Carbon::now()->subYears(34);
        $patient1->age = 34;
        $patient1->history = 'Riwayat hipertensi';
        $patient1->address = 'Jl. Malioboro No. 10, Yogyakarta';
        $patient1->telpNumber = '081234567890';
        $patient1->createdAt = Carbon::now()->subDays(10);
        $patient1->updatedAt = Carbon::now()->subDays(2);

        $inventoryOfficer = new UserDTO();
        $inventoryOfficer->id = '1';
        $inventoryOfficer->username = 'inventory_officer1';
        $inventoryOfficer->email = 'inventory@example.com';
        $inventoryOfficer->fullname = 'Inventory Officer';
        $inventoryOfficer->role = UserRole::INVENTORY_OFFICER;
        $inventoryOfficer->createdAt = Carbon::now()->subDays(30);
        $inventoryOfficer->updatedAt = Carbon::now()->subDays(30);

        $unit = new UnitDTO();
        $unit->id = '1';
        $unit->name = 'Tablet';

        $medicine = new MedicineDTO();
        $medicine->id = '1';
        $medicine->name = 'Paracetamol';
        $medicine->stock = 100;
        $medicine->unit = $unit;
        $medicine->createdAt = Carbon::now()->subDays(60);
        $medicine->updatedAt = Carbon::now()->subDays(60);

        $medicineTransaction1 = new MedicineStockTransactionDTO();
        $medicineTransaction1->id = '1';
        $medicineTransaction1->inventoryOfficer = $inventoryOfficer;
        $medicineTransaction1->medicine = $medicine;
        $medicineTransaction1->registration = null; // Changed from string to null
        $medicineTransaction1->type = TransactionType::OUT;
        $medicineTransaction1->quantity = 2;
        $medicineTransaction1->beginningStock = 100;
        $medicineTransaction1->lastStock = 98;
        $medicineTransaction1->expiredDate = Carbon::now()->addYears(2);
        $medicineTransaction1->notes = 'Obat untuk pasien Budi Santoso';
        $medicineTransaction1->createdAt = Carbon::now()->subDays(5);
        $medicineTransaction1->updatedAt = Carbon::now()->subDays(5);

        $registration1 = new RegistrationDTO();
        $registration1->id = '1';
        $registration1->patient = $patient1;
        $registration1->complaint = 'Pusing dan mual';
        $registration1->receipt = 'RCPT-001';
        $registration1->medicineStockTransactions = [$medicineTransaction1];
        $registration1->checkDate = null;
        $registration1->createdAt = Carbon::now()->subDays(5);
        $registration1->updatedAt = Carbon::now()->subDays(1);

        $patient2 = new PatientDTO();
        $patient2->id = '2';
        $patient2->medicalRecordNumber = 'MR-002';
        $patient2->medicalRecordFileUrl = 'MR-002';
        $patient2->name = 'Siti Aminah';
        $patient2->gender = 'P';
        $patient2->dateOfBirth = Carbon::now()->subYears(28);
        $patient2->age = 28;
        $patient2->history = null;
        $patient2->address = 'Jl. Kaliurang Km 7, Sleman';
        $patient2->telpNumber = '082198765432';
        $patient2->createdAt = Carbon::now()->subDays(20);
        $patient2->updatedAt = Carbon::now()->subDays(5);

        $registration2 = new RegistrationDTO();
        $registration2->id = '2';
        $registration2->patient = $patient2;
        $registration2->complaint = 'Batuk dan demam';
        $registration2->receipt = null;
        $registration2->medicineStockTransactions = [];
        $registration2->checkDate = null;
        $registration2->createdAt = Carbon::now()->subDays(3);
        $registration2->updatedAt = Carbon::now()->subDays(1);

        return [$registration1, $registration2];
    }

    public function getWhereCheckDateIsNotNull(): array
    {
        $patient1 = new PatientDTO();
        $patient1->id = '1';
        $patient1->medicalRecordNumber = 'MR-001';
        $patient1->medicalRecordFileUrl = 'MR-001';
        $patient1->name = 'Budi Santoso';
        $patient1->gender = 'L';
        $patient1->dateOfBirth = Carbon::now()->subYears(34);
        $patient1->age = 34;
        $patient1->history = 'Riwayat hipertensi';
        $patient1->address = 'Jl. Malioboro No. 10, Yogyakarta';
        $patient1->telpNumber = '081234567890';
        $patient1->createdAt = Carbon::now()->subDays(10);
        $patient1->updatedAt = Carbon::now()->subDays(2);

        $inventoryOfficer = new UserDTO();
        $inventoryOfficer->id = '1';
        $inventoryOfficer->username = 'inventory_officer1';
        $inventoryOfficer->email = 'inventory@example.com';
        $inventoryOfficer->fullname = 'Inventory Officer';
        $inventoryOfficer->role = UserRole::INVENTORY_OFFICER;
        $inventoryOfficer->createdAt = Carbon::now()->subDays(30);
        $inventoryOfficer->updatedAt = Carbon::now()->subDays(30);

        $unit1 = new UnitDTO();
        $unit1->id = '1';
        $unit1->name = 'Tablet';

        $medicine1 = new MedicineDTO();
        $medicine1->id = '1';
        $medicine1->name = 'Paracetamol';
        $medicine1->stock = 100;
        $medicine1->unit = $unit1;
        $medicine1->createdAt = Carbon::now()->subDays(60);
        $medicine1->updatedAt = Carbon::now()->subDays(60);

        $medicineTransaction1 = new MedicineStockTransactionDTO();
        $medicineTransaction1->id = '1';
        $medicineTransaction1->inventoryOfficer = $inventoryOfficer;
        $medicineTransaction1->medicine = $medicine1;
        $medicineTransaction1->registration = null; // Changed from string to null
        $medicineTransaction1->type = TransactionType::OUT;
        $medicineTransaction1->quantity = 2;
        $medicineTransaction1->beginningStock = 100;
        $medicineTransaction1->lastStock = 98;
        $medicineTransaction1->expiredDate = Carbon::now()->addYears(2);
        $medicineTransaction1->notes = 'Obat untuk pasien Budi Santoso';
        $medicineTransaction1->createdAt = Carbon::now()->subDays(5);
        $medicineTransaction1->updatedAt = Carbon::now()->subDays(5);

        $registration1 = new RegistrationDTO();
        $registration1->id = '1';
        $registration1->patient = $patient1;
        $registration1->complaint = 'Pusing dan mual';
        $registration1->receipt = 'RCPT-001';
        $registration1->medicineStockTransactions = [$medicineTransaction1];
        $registration1->checkDate = Carbon::now();
        $registration1->createdAt = Carbon::now()->subDays(5);
        $registration1->updatedAt = Carbon::now()->subDays(1);

        $patient2 = new PatientDTO();
        $patient2->id = '2';
        $patient2->medicalRecordNumber = 'MR-002';
        $patient2->medicalRecordFileUrl = 'MR-002';
        $patient2->name = 'Siti Aminah';
        $patient2->gender = 'P';
        $patient2->dateOfBirth = Carbon::now()->subYears(28);
        $patient2->age = 28;
        $patient2->history = null;
        $patient2->address = 'Jl. Kaliurang Km 7, Sleman';
        $patient2->telpNumber = '082198765432';
        $patient2->createdAt = Carbon::now()->subDays(20);
        $patient2->updatedAt = Carbon::now()->subDays(5);

        $unit2 = new UnitDTO();
        $unit2->id = '2';
        $unit2->name = 'Kapsul';

        $medicine2 = new MedicineDTO();
        $medicine2->id = '2';
        $medicine2->name = 'Amoxicillin';
        $medicine2->stock = 50;
        $medicine2->unit = $unit2;
        $medicine2->createdAt = Carbon::now()->subDays(60);
        $medicine2->updatedAt = Carbon::now()->subDays(60);

        $medicineTransaction2 = new MedicineStockTransactionDTO();
        $medicineTransaction2->id = '2';
        $medicineTransaction2->inventoryOfficer = $inventoryOfficer;
        $medicineTransaction2->medicine = $medicine2;
        $medicineTransaction2->registration = null; // Changed from string to null
        $medicineTransaction2->type = TransactionType::OUT;
        $medicineTransaction2->quantity = 1;
        $medicineTransaction2->beginningStock = 50;
        $medicineTransaction2->lastStock = 49;
        $medicineTransaction2->expiredDate = Carbon::now()->addYears(1);
        $medicineTransaction2->notes = 'Obat untuk pasien Siti Aminah';
        $medicineTransaction2->createdAt = Carbon::now()->subDays(3);
        $medicineTransaction2->updatedAt = Carbon::now()->subDays(3);

        $registration2 = new RegistrationDTO();
        $registration2->id = '2';
        $registration2->patient = $patient2;
        $registration2->complaint = 'Batuk dan demam';
        $registration2->receipt = null;
        $registration2->medicineStockTransactions = [$medicineTransaction2];
        $registration2->checkDate = Carbon::now();
        $registration2->createdAt = Carbon::now()->subDays(3);
        $registration2->updatedAt = Carbon::now()->subDays(1);

        return [$registration1, $registration2];
    }

    public function getByRangeDate(CarbonInterface $startDate, CarbonInterface $endDate): array
    {
        $all = $this->getAll();

        return array_filter($all, function ($registration) use ($startDate, $endDate) {
            return $registration->createdAt->between($startDate, $endDate);
        });
    }
}
