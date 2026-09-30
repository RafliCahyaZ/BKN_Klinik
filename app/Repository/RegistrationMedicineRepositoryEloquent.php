<?php

namespace App\Repository;

use App\DTO\RegistrationMedicineDTO;
use App\Models\RegistrationMedicine;

class RegistrationMedicineRepositoryEloquent
    implements RegistrationMedicineRepository
{
    public function insert(
        RegistrationMedicineDTO $registrationMedicineDTO
    ): ?RegistrationMedicineDTO {
        try {
            $registrationMedicine = RegistrationMedicine::create([
                'registration_id' => $registrationMedicineDTO->registrationId,
                'medicine_id' => $registrationMedicineDTO->medicineId,
                'quantity' => $registrationMedicineDTO->quantity,
                'perhari' => $registrationMedicineDTO->perhari,
            ]);

            $registrationMedicineDTO->id = $registrationMedicine->id;
            $registrationMedicineDTO->createdAt = $registrationMedicine->created_at;
            $registrationMedicineDTO->updatedAt = $registrationMedicine->updated_at;

            return $registrationMedicineDTO;

        } catch (\Throwable $th) {
            return null;
        }
    }
}