<?php

namespace App\DTO;

use Carbon\CarbonInterface;

class MedicineDTO
{
    public string $id;
    public string $name;
    public int $stock;
    public UnitDTO $unit;
    public CarbonInterface $createdAt;
    public CarbonInterface $updatedAt;
}
