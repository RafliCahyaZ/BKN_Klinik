<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Treatment extends Model
{
    protected $fillable = [
        'treatment_code',
        'treatment_name',
    ];

    public function registrations(): BelongsToMany
    {
        return $this->belongsToMany(Registration::class);
    }
}
