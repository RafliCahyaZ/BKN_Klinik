<?php

namespace App\DTO;

use Carbon\CarbonInterface;

class PatientDTO
{
    public string $id;
    public string $medicalRecordNumber;
    public string $medicalRecordFileUrl;
    public string $name;
    public string $gender;
    public CarbonInterface $dateOfBirth;
    public int $age;
    public ?string $history = null;
    public string $address;
    public string $telpNumber;
    public CarbonInterface $createdAt;
    public CarbonInterface $updatedAt;
}
