<?php

namespace App\DTO;

use Carbon\CarbonInterface;

class RegistrationDTO
{
    public string $id;
    public PatientDTO $patient;
    public UserDTO $doctor;
    public string $complaint;
    public ?string $receipt = null;
    public ?CarbonInterface $checkDate = null;
    public CarbonInterface $updatedAt;
    public CarbonInterface $createdAt;
}
