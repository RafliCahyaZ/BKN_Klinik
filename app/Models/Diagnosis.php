<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Diagnosis extends Model
{
    protected $table = 'master_diagnoses';

    protected $fillable = [
        'icd_code',
        'disease_name',
    ];

    public function registrations(): BelongsToMany
    {
        return $this->belongsToMany(Registration::class);
    }
}
