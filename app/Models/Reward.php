<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reward extends Model
{
    protected $fillable = ['name', 'point_cost', 'stock'];

    /**
     * Relasi ke detail penukaran sembako.
     */
    public function redemptionDetails(): HasMany
    {
        return $this->hasMany(RedemptionDetail::class);
    }
}
