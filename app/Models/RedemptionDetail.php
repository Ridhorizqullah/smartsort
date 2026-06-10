<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RedemptionDetail extends Model
{
    protected $fillable = [
        'redemption_id',
        'reward_id',
        'qty',
        'point_snapshot',
        'subtotal_point'
    ];

    /**
     * Relasi ke penukaran induk.
     */
    public function redemption(): BelongsTo
    {
        return $this->belongsTo(Redemption::class);
    }

    /**
     * Relasi ke item reward (sembako).
     */
    public function reward(): BelongsTo
    {
        return $this->belongsTo(Reward::class);
    }
}
