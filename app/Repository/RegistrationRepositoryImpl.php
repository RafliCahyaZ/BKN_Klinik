<?php

namespace App\Repository;

use App\DTO\PatientDTO;
use App\DTO\RegistrationDTO;
use Carbon\Carbon;

class RegistrationRepositoryImpl implements RegistrationRepository
{
    public function getAll(): array
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

        $registration1 = new RegistrationDTO();
        $registration1->id = '1';
        $registration1->patient = $patient1;
        $registration1->complaint = 'Pusing dan mual';
        $registration1->receipt = 'RCPT-001';
        $registration1->checkDate = Carbon::parse('2024-01-15 09:00:00');
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
        $registration2->checkDate = null;
        $registration2->createdAt = Carbon::now()->subDays(3);
        $registration2->updatedAt = Carbon::now()->subDays(1);

        return [$registration1, $registration2];
    }

    public function getById(): ?RegistrationDTO
    {
        $all = $this->getAll();

        return $all[0] ?? null;
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

    public function edit(string $id, RegistrationDTO $registrationDTO): ?RegistrationDTO
    {

        $registrationDTO->id = $id;
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

        return false;
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

        $registration1 = new RegistrationDTO();
        $registration1->id = '1';
        $registration1->patient = $patient1;
        $registration1->complaint = 'Pusing dan mual';
        $registration1->receipt = 'RCPT-001';
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

        $registration1 = new RegistrationDTO();
        $registration1->id = '1';
        $registration1->patient = $patient1;
        $registration1->complaint = 'Pusing dan mual';
        $registration1->receipt = 'RCPT-001';
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

        $registration2 = new RegistrationDTO();
        $registration2->id = '2';
        $registration2->patient = $patient2;
        $registration2->complaint = 'Batuk dan demam';
        $registration2->receipt = null;
        $registration2->checkDate = Carbon::now();
        $registration2->createdAt = Carbon::now()->subDays(3);
        $registration2->updatedAt = Carbon::now()->subDays(1);

        return [$registration1, $registration2];
    }
}
