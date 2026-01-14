<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $fillable = [
        'name',
    ];

    public function medicines(): HasMany
    {
        return $this->hasMany(Medicine::class);
    }

    
}
