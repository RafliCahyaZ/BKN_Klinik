<?php

namespace App\DTO;

use Carbon\CarbonInterface;

class UnitDTO
{
    public string $id;

    public string $name;

    public CarbonInterface $createdAt;

    public CarbonInterface $updatedAt;
}
