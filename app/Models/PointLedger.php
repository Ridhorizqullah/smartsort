<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;

class PointLedger extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'transaction_id',
        'redemption_id',
        'description'
    ];

    /**
     * Relasi ke warga (user).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke transaksi setoran sampah (jika ada).
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Relasi ke transaksi penukaran sembako (jika ada).
     */
    public function redemption(): BelongsTo
    {
        return $this->belongsTo(Redemption::class);
    }

    /**
     * Boot model untuk validasi integritas data ledger.
     */
    protected static function boot()
    {
        parent::boot();

        // Enforce ledger immutability
        static::updating(function ($ledger) {
            throw new \Exception("Ledger point bersifat immutable dan tidak boleh diubah setelah disimpan.");
        });

        static::deleting(function ($ledger) {
            throw new \Exception("Ledger point bersifat immutable dan tidak boleh dihapus.");
        });

        static::saving(function ($ledger) {
            $hasTransaction = !empty($ledger->transaction_id);
            $hasRedemption = !empty($ledger->redemption_id);

            // Validasi: tidak boleh dua-duanya terisi, dan tidak boleh dua-duanya kosong
            if ($hasTransaction && $hasRedemption) {
                throw new InvalidArgumentException("Inkonsistensi data: Point ledger tidak boleh memiliki transaction_id dan redemption_id secara bersamaan.");
            }

            if (!$hasTransaction && !$hasRedemption) {
                throw new InvalidArgumentException("Inkonsistensi data: Point ledger harus memiliki salah satu dari transaction_id atau redemption_id.");
            }

            // Validasi berdasarkan tipe ledger
            if ($ledger->type === 'credit') {
                if (!$hasTransaction) {
                    throw new InvalidArgumentException("Point ledger bertipe 'credit' wajib memiliki transaction_id.");
                }
                $ledger->redemption_id = null; // Enforce null
            } elseif ($ledger->type === 'debit') {
                if (!$hasRedemption) {
                    throw new InvalidArgumentException("Point ledger bertipe 'debit' wajib memiliki redemption_id.");
                }
                $ledger->transaction_id = null; // Enforce null
            } else {
                throw new InvalidArgumentException("Tipe ledger tidak valid. Harus 'credit' atau 'debit'.");
            }
        });
    }
}
