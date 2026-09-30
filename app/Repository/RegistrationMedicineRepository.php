<?php

namespace App\Repository;

use App\DTO\RegistrationMedicineDTO;

interface RegistrationMedicineRepository
{
    public function insert(
        RegistrationMedicineDTO $registrationMedicineDTO
    ): ?RegistrationMedicineDTO;
}