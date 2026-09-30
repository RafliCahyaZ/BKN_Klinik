<?php

namespace App\DTO;

use Carbon\CarbonInterface;

class RegistrationMedicineDTO
{
    public string $id;

    public string $registrationId;

    public string $medicineId;

    public int $quantity;

    public int $perhari;

    public ?CarbonInterface $createdAt = null;

    public ?CarbonInterface $updatedAt = null;
}