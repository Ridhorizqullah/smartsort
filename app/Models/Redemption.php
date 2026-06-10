<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Redemption extends Model
{
    protected $fillable = ['user_id', 'admin_id', 'total_point', 'status', 'idempotency_key', 'tanggal_ambil', 'catatan_admin', 'approved_at', 'ready_at', 'completed_at', 'rejected_at', 'expires_at'];

    /**
     * Relasi ke warga penukar (user).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke admin/petugas yang melayani (admin).
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Relasi ke detail penukaran.
     */
    public function details(): HasMany
    {
        return $this->hasMany(RedemptionDetail::class);
    }

    /**
     * Relasi ke mutasi poin ledger terkait penukaran ini.
     */
    public function pointLedgers(): HasMany
    {
        return $this->hasMany(PointLedger::class);
    }
}
