<?php

namespace App\Repository;

use App\DTO\TreatmentDTO;
use Carbon\Carbon;

class TreatmentRepositoryImpl implements TreatmentRepository
{
    public function getAll(): array
    {
        // 1
        $treatment1 = new TreatmentDTO();
        $treatment1->id = '1';
        $treatment1->treatmentCode = 'TR-001';
        $treatment1->treatmentName = 'Konsultasi Umum';
        $treatment1->createdAt = Carbon::now()->subDays(10);
        $treatment1->updatedAt = Carbon::now()->subDays(2);

        // 2
        $treatment2 = new TreatmentDTO();
        $treatment2->id = '2';
        $treatment2->treatmentCode = 'TR-002';
        $treatment2->treatmentName = 'Pemeriksaan Laboratorium';
        $treatment2->createdAt = Carbon::now()->subDays(8);
        $treatment2->updatedAt = Carbon::now()->subDays(1);

        // 3
        $treatment3 = new TreatmentDTO();
        $treatment3->id = '3';
        $treatment3->treatmentCode = 'TR-003';
        $treatment3->treatmentName = 'Penanganan Diabetes';
        $treatment3->createdAt = Carbon::now()->subMonth();
        $treatment3->updatedAt = Carbon::now()->subDays(4);

        // 4
        $treatment4 = new TreatmentDTO();
        $treatment4->id = '4';
        $treatment4->treatmentCode = 'TR-004';
        $treatment4->treatmentName = 'Manajemen Asma';
        $treatment4->createdAt = Carbon::now()->subMonths(2);
        $treatment4->updatedAt = Carbon::now()->subDays(6);

        // 5
        $treatment5 = new TreatmentDTO();
        $treatment5->id = '5';
        $treatment5->treatmentCode = 'TR-005';
        $treatment5->treatmentName = 'Vaksinasi';
        $treatment5->createdAt = Carbon::now()->subDays(1);
        $treatment5->updatedAt = Carbon::now();

        return [$treatment1, $treatment2, $treatment3, $treatment4, $treatment5];
    }

    public function getById(string $id): ?TreatmentDTO
    {
        $treatment = new TreatmentDTO();
        $treatment->id = $id;
        $treatment->treatmentCode = 'TR-001';
        $treatment->treatmentName = 'Konsultasi Umum';
        $treatment->createdAt = Carbon::now()->subDays(10);
        $treatment->updatedAt = Carbon::now()->subDays(2);

        return $treatment;
    }

    public function insert(TreatmentDTO $treatmentDTO): ?TreatmentDTO
    {
        return $treatmentDTO;
    }

    public function edit(string $id, TreatmentDTO $treatmentDTO): ?TreatmentDTO
    {
        return $treatmentDTO;
    }

    public function getLatestId(): int
    {
        return 5;
    }

    public function delete(string $id): bool
    {
        return true;
    }
}
