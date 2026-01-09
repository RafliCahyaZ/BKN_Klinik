<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{

    protected $fillable = [
        'fullname',
        'gender',
        'address',
        'date_of_birth',
        'telp_number',
        'history',
        'medical_record_file_url',
    ];

    public function getAgeAttribute() {
        return Carbon::parse($this->date_of_birth)->age;
    }



    public function registration() {
        return $this->hasMany(Registration::class);
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
