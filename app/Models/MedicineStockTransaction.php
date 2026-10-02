<?php

namespace App\Models;

use App\Type\TransactionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicineStockTransaction extends Model
{
    protected $fillable = [
        'inventory_officer_id',
        'medicine_id',
        'registration_id',
        'type',
        'quantity',     //jumlah obat yang keluar atau masuk
        'frequency',    //jumlah konsumsi obat per hari
        'dose',         //jumlah obat setiap konsumsi
        'beginning_stock',
        'last_stock',
        'expired_date',
        'notes',
    ];

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function inventoryOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'type' => TransactionType::class,
        ];
    }
}
