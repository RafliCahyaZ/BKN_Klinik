<?php

namespace App\DTO;

use Carbon\CarbonInterface;

class TreatmentDTO
{
    public string $id;
    public string $treatmentCode;
    public string $treatmentName;
    public CarbonInterface $createdAt;
    public CarbonInterface $updatedAt;
}
