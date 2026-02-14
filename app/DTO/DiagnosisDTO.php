<?php

namespace App\DTO;

use Carbon\CarbonInterface;

class DiagnosisDTO
{
    public string $id;

    public string $icdCode;

    public string $diseaseName;

    public CarbonInterface $createdAt;

    public CarbonInterface $updatedAt;
}
