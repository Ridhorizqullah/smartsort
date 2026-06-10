<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'rt_rw',
        'nik',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'saldo_poin' => 'integer',
        ];
    }

    /**
     * Scope untuk filter berdasarkan role.
     * Menggantikan magic whereRole() agar lebih eksplisit dan type-safe.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $role
     */
    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Relasi ke transaksi setoran sampah (warga).
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    /**
     * Relasi ke transaksi penukaran sembako (warga).
     */
    public function redemptions(): HasMany
    {
        return $this->hasMany(Redemption::class, 'user_id');
    }

    /**
     * Relasi ke mutasi poin ledger.
     */
    public function pointLedgers(): HasMany
    {
        return $this->hasMany(PointLedger::class, 'user_id');
    }
}
