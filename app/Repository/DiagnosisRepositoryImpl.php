<?php

namespace App\Repository;

use App\DTO\DiagnosisDTO;
use Carbon\Carbon;

class DiagnosisRepositoryImpl implements DiagnosisRepository
{
    public function getAll(): array
    {
        // 1
        $diagnosis1 = new DiagnosisDTO();
        $diagnosis1->id = '1';
        $diagnosis1->icdCode = 'I10';
        $diagnosis1->diseaseName = 'Hipertensi Esensial';
        $diagnosis1->createdAt = Carbon::now()->subDays(30);
        $diagnosis1->updatedAt = Carbon::now()->subDays(10);

        // 2
        $diagnosis2 = new DiagnosisDTO();
        $diagnosis2->id = '2';
        $diagnosis2->icdCode = 'E11';
        $diagnosis2->diseaseName = 'Diabetes Melitus Tipe 2';
        $diagnosis2->createdAt = Carbon::now()->subDays(25);
        $diagnosis2->updatedAt = Carbon::now()->subDays(8);

        // 3
        $diagnosis3 = new DiagnosisDTO();
        $diagnosis3->id = '3';
        $diagnosis3->icdCode = 'J45';
        $diagnosis3->diseaseName = 'Asma';
        $diagnosis3->createdAt = Carbon::now()->subMonth();
        $diagnosis3->updatedAt = Carbon::now()->subDays(5);

        // 4
        $diagnosis4 = new DiagnosisDTO();
        $diagnosis4->id = '4';
        $diagnosis4->icdCode = 'K21';
        $diagnosis4->diseaseName = 'Penyakit Refluks Gastroesofageal';
        $diagnosis4->createdAt = Carbon::now()->subMonths(2);
        $diagnosis4->updatedAt = Carbon::now()->subDays(3);

        // 5
        $diagnosis5 = new DiagnosisDTO();
        $diagnosis5->id = '5';
        $diagnosis5->icdCode = 'M79.3';
        $diagnosis5->diseaseName = 'Myalgia';
        $diagnosis5->createdAt = Carbon::now()->subDays(15);
        $diagnosis5->updatedAt = Carbon::now();

        return [$diagnosis1, $diagnosis2, $diagnosis3, $diagnosis4, $diagnosis5];
    }

    public function getById(string $id): ?DiagnosisDTO
    {
        $diagnosis = new DiagnosisDTO();
        $diagnosis->id = $id;
        $diagnosis->icdCode = 'I10';
        $diagnosis->diseaseName = 'Hipertensi Esensial';
        $diagnosis->createdAt = Carbon::now()->subDays(30);
        $diagnosis->updatedAt = Carbon::now()->subDays(10);

        return $diagnosis;
    }

    public function insert(DiagnosisDTO $diagnosisDTO): ?DiagnosisDTO
    {
        return $diagnosisDTO;
    }

    public function edit(string $id, DiagnosisDTO $diagnosisDTO): ?DiagnosisDTO
    {
        return $diagnosisDTO;
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
