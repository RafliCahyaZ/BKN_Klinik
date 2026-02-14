<?php

namespace App\DTO;

use App\Type\TransactionType;
use Carbon\CarbonInterface;

class MedicineStockTransactionDTO
{
    public string $id;

    public UserDTO $inventoryOfficer;

    public MedicineDTO $medicine;

    public ?RegistrationDTO $registration = null;

    public TransactionType $type;

    public int $quantity;

    public int $beginningStock;

    public int $lastStock;

    public ?string $notes = null;

    public CarbonInterface $createdAt;

    public CarbonInterface $updatedAt;
}
