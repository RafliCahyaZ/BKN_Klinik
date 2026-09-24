<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Registration extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'check_date',
        'complaint',
        'receipt',
    ];

    public function diagnoses(): BelongsToMany
    {
        return $this->belongsToMany(Diagnosis::class)->withTimestamps();
    }

    public function medicineStockTransactions(): HasMany
    {
        return $this->hasMany(MedicineStockTransaction::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function treatments(): BelongsToMany
    {
        return $this->belongsToMany(Treatment::class)->withTimestamps();
    }
}
