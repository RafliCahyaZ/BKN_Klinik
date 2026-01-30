<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'username',
        'password',
        'fullname',
        'email',
        'last_login_at',
    ];

    protected $guarded = [
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
 Treatment    */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'doctor_id');
    }

    public function medicineStockTransactions(): HasMany
    {
        return $this->hasMany(MedicineStockTransaction::class, 'inventory_officer_id');
    }
}
