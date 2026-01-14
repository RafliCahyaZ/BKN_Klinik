<?php

namespace App\DTO;

use Carbon\CarbonInterface;

class RegistrationDTO
{
    public string $id;
    public PatientDTO $patient;
    public string $complaint;
    public ?string $receipt;
    public array $diagnoses = [];
    public array $treatments = [];
    public ?CarbonInterface $checkDate;
    public CarbonInterface $updatedAt;
    public CarbonInterface $createdAt;
}
