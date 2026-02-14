<?php

namespace App\DTO;

use Carbon\CarbonInterface;

class RegistrationDTO
{
    public string $id;

    public PatientDTO $patient;

    public ?UserDTO $doctor = null;

    public string $complaint;

    public array $diagnoses = [];

    public array $treatments = [];

    public array $medicineStockTransactions = [];

    public ?string $receipt = null;

    public ?CarbonInterface $checkDate = null;

    public ?CarbonInterface $cancelledAt = null;

    public CarbonInterface $updatedAt;

    public CarbonInterface $createdAt;
}
