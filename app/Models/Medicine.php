<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Medicine extends Model
{
    protected $fillable = [
        'name',
    ];

    public function registrations(): BelongsToMany
    {
        return $this->belongsToMany(Registration::class);
    }
}
