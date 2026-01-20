<?php

namespace App\DTO;

use Carbon\CarbonInterface;

class MedicineStockTransactionDTO
{
    public string $id;
    public string $inventoryOfficerId;
    public string $medicineId;
    public ?string $registrationId;
    public string $type;
    public int $quantity;
    public int $beginningStock;
    public int $lastStock;
    public ?CarbonInterface $expiredDate;
    public ?string $notes;
    public CarbonInterface $createdAt;
    public CarbonInterface $updatedAt;
}
