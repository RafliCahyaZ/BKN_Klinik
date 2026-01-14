<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicineStockTransaction extends Model
{
    protected $fillable = [
        'inventory_officer_id',
        'medicine_id',
        'registration_id',
        'type',
        'quantity',
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
    
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
