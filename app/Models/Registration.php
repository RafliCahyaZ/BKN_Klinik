<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Registration extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'patient_id',
        'complaint',
        'is_finished',
    ];

    public function patients(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function treatments(): BelongsToMany
    {
        return $this->belongsToMany(Treatment::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function medications(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
