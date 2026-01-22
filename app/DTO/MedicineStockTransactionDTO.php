<?php

namespace App\DTO;

use App\Type\TransactionType;
use Carbon\CarbonInterface;

class MedicineStockTransactionDTO
{
    public string $id;
    public string $inventoryOfficerId;
    public string $medicineId;
    public ?string $registrationId = null;
    public TransactionType $type;
    public int $quantity;
    public int $beginningStock;
    public int $lastStock;
    public ?CarbonInterface $expiredDate;
    public ?string $notes = null;
    public CarbonInterface $createdAt;
    public CarbonInterface $updatedAt;
}
