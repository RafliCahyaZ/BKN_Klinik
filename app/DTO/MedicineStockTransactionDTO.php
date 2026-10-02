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

     // Frekuensi konsumsi obat per hari
    public ?int $frequency = null;

    // Dosis setiap kali konsumsi
    public ?string $dose = null;

    public int $beginningStock;

    public int $lastStock;

    public ?string $notes = null;

    public CarbonInterface $createdAt;

    public CarbonInterface $updatedAt;
}
