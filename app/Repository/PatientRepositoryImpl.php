<?php

namespace App\Repository;

use App\DTO\PatientDTO;
use Carbon\Carbon;

class PatientRepositoryImpl implements PatientRepository
{
    public function getById(string $id): ?PatientDTO
    {
        $patient1 = new PatientDTO();
        $patient1->id = '1';
        $patient1->medicalRecordNumber = 'MR-001';
        $patient1->medicalRecordFileUrl = 'MR-001';
        $patient1->dateOfBirth = Carbon::now()->subDays(10);
        $patient1->name = 'Budi Santoso';
        $patient1->gender = 'L';
        $patient1->age = 34;
        $patient1->history = 'Riwayat hipertensi';
        $patient1->address = 'Jl. Malioboro No. 10, Yogyakarta';
        $patient1->telpNumber = '081234567890';
        $patient1->createdAt = Carbon::now()->subDays(10);
        $patient1->updatedAt = Carbon::now()->subDays(2);

        return $patient1;
    }

    public function delete(string $id): bool
    {
        return false;
    }

    public function insert(PatientDTO $patientDTO): ?PatientDTO
    {
        return $patientDTO;
    }

    public function edit(string $id, PatientDTO $patientDTO): ?PatientDTO
    {
        return $patientDTO;
    }

    public function getLatestId(): int
    {
        return 0;
    }

    public function getAll(): array
    {
        // 1
        $patient1 = new PatientDTO();
        $patient1->id = '1';
        $patient1->medicalRecordNumber = 'MR-001';
        $patient1->medicalRecordFileUrl = 'MR-001';
        $patient1->dateOfBirth = Carbon::now()->subDays(10);
        $patient1->name = 'Budi Santoso';
        $patient1->gender = 'L';
        $patient1->age = 34;
        $patient1->history = 'Riwayat hipertensi';
        $patient1->address = 'Jl. Malioboro No. 10, Yogyakarta';
        $patient1->telpNumber = '081234567890';
        $patient1->createdAt = Carbon::now()->subDays(10);
        $patient1->updatedAt = Carbon::now()->subDays(2);

        // 2
        $patient2 = new PatientDTO();
        $patient2->id = '2';
        $patient2->medicalRecordNumber = 'MR-002';
        $patient2->medicalRecordFileUrl = 'MR-001';
        $patient2->dateOfBirth = Carbon::now()->subDays(10);
        $patient2->name = 'Siti Aminah';
        $patient2->gender = 'P';
        $patient2->age = 28;
        $patient2->history = null;
        $patient2->address = 'Jl. Kaliurang Km 7, Sleman';
        $patient2->telpNumber = '082198765432';
        $patient2->createdAt = Carbon::now()->subDays(20);
        $patient2->updatedAt = Carbon::now()->subDays(5);

        // 3
        $patient3 = new PatientDTO();
        $patient3->medicalRecordFileUrl = 'MR-001';
        $patient3->id = '3';
        $patient3->medicalRecordNumber = 'MR-003';
        $patient3->dateOfBirth = Carbon::now()->subDays(10);
        $patient3->name = 'Ahmad Fauzi';
        $patient3->gender = 'L';
        $patient3->age = 45;
        $patient3->history = 'Diabetes melitus';
        $patient3->address = 'Jl. Bantul No. 25, Bantul';
        $patient3->telpNumber = '085712345678';
        $patient3->createdAt = Carbon::now()->subMonth();
        $patient3->updatedAt = Carbon::now()->subDays(1);

        // 4
        $patient4 = new PatientDTO();
        $patient4->id = '4';
        $patient4->medicalRecordNumber = 'MR-004';
        $patient4->medicalRecordFileUrl = 'MR-001';
        $patient4->dateOfBirth = Carbon::now()->subDays(10);
        $patient4->name = 'Dewi Lestari';
        $patient4->gender = 'P';
        $patient4->age = 52;
        $patient4->history = 'Asma sejak kecil';
        $patient4->address = 'Jl. Magelang No. 88, Sleman';
        $patient4->telpNumber = '081355566677';
        $patient4->createdAt = Carbon::now()->subMonths(2);
        $patient4->updatedAt = Carbon::now()->subDays(7);

        // 5
        $patient5 = new PatientDTO();
        $patient5->id = '5';
        $patient5->medicalRecordNumber = 'MR-005';
        $patient5->medicalRecordFileUrl = 'MR-001';
        $patient5->dateOfBirth = Carbon::now()->subDays(10);
        $patient5->name = 'Rizky Pratama';
        $patient5->gender = 'L';
        $patient5->age = 19;
        $patient5->history = 'Alergi makanan laut';
        $patient5->address = 'Jl. Wonosari No. 12, Gunungkidul';
        $patient5->telpNumber = '089912345678';
        $patient5->createdAt = Carbon::now()->subDays(3);
        $patient5->updatedAt = Carbon::now();

        return [$patient1, $patient2, $patient3, $patient4, $patient5];
    }
}
