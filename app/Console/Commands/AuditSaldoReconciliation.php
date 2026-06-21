<?php

namespace App\Console\Commands;

use App\Models\PointLedger;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Command: php artisan saldo:audit
 *
 * Melakukan rekonsiliasi integritas saldo untuk semua warga.
 * Rumus kebenaran: users.saldo_poin == SUM(credit) - SUM(debit) dari point_ledgers
 *
 * Jalankan secara berkala via scheduler (harian) untuk deteksi dini.
 */
class AuditSaldoReconciliation extends Command
{
    protected $signature = 'saldo:audit
        {--fix : Auto-fix saldo yang mismatch dengan nilai dari ledger (HATI-HATI: gunakan hanya jika yakin ledger adalah sumber kebenaran)}
        {--user= : Audit saldo user tertentu berdasarkan ID}';

    protected $description = 'Audit & rekonsiliasi integritas saldo semua warga. Verifikasi: users.saldo_poin == SUM(credit) - SUM(debit) dari point_ledgers.';

    public function handle(): int
    {
        $this->info('=== AUDIT INTEGRITAS SALDO SMARTSORT ===');
        $this->info('Rumus: saldo_poin = SUM(credit) - SUM(debit) di point_ledgers');
        $this->newLine();

        $shouldFix  = $this->option('fix');
        $targetUser = $this->option('user');

        // --- 1. Ambil semua saldo dari DB (JOIN dengan ledger) ---
        $query = DB::table('users')
            ->leftJoin('point_ledgers', 'users.id', '=', 'point_ledgers.user_id')
            ->where('users.role', 'warga')
            ->select([
                'users.id',
                'users.name',
                'users.nik',
                'users.saldo_poin as saldo_cached',
                DB::raw("COALESCE(SUM(CASE WHEN point_ledgers.type = 'credit' THEN point_ledgers.amount ELSE 0 END), 0) as total_credit"),
                DB::raw("COALESCE(SUM(CASE WHEN point_ledgers.type = 'debit' THEN point_ledgers.amount ELSE 0 END), 0) as total_debit"),
            ])
            ->groupBy('users.id', 'users.name', 'users.nik', 'users.saldo_poin');

        if ($targetUser) {
            $query->where('users.id', $targetUser);
        }

        $results = $query->get();

        if ($results->isEmpty()) {
            $this->warn('Tidak ada warga yang ditemukan untuk diaudit.');
            return self::SUCCESS;
        }

        // --- 2. Analisis mismatch ---
        $mismatchCount  = 0;
        $negativeCount  = 0;
        $orphanLedgers  = 0;
        $mismatchRows   = [];

        foreach ($results as $row) {
            $ledgerBalance = $row->total_credit - $row->total_debit;
            $diff          = $row->saldo_cached - $ledgerBalance;

            if ($row->saldo_cached < 0) {
                $negativeCount++;
            }

            if ($diff !== 0) {
                $mismatchCount++;
                $mismatchRows[] = [
                    'ID'         => $row->id,
                    'Nama'       => $row->name,
                    'NIK'        => $row->nik,
                    'Saldo DB'   => number_format($row->saldo_cached, 0, ',', '.'),
                    'Ledger'     => number_format($ledgerBalance, 0, ',', '.'),
                    'Selisih'    => number_format($diff, 0, ',', '.'),
                ];

                // Auto-fix jika diminta
                if ($shouldFix) {
                    DB::table('users')
                        ->where('id', $row->id)
                        ->update(['saldo_poin' => $ledgerBalance]);

                    Log::warning("[AuditSaldo] AUTO-FIX saldo user #{$row->id} ({$row->name}): {$row->saldo_cached} → {$ledgerBalance}");
                }
            }
        }

        // --- 3. Cek ledger orphan (reference null: transaksi/redemption dihapus) ---
        $orphanLedgers = PointLedger::whereNull('transaction_id')
            ->whereNull('redemption_id')
            ->count();

        // --- 4. Cek ledger dengan amount <= 0 (data rusak) ---
        $invalidAmount = PointLedger::where('amount', '<=', 0)->count();

        // --- 5. Tampilkan hasil ---
        $totalUsers = $results->count();
        $cleanCount = $totalUsers - $mismatchCount;

        $this->info("Total warga diaudit : {$totalUsers}");
        $this->info("✅ Saldo konsisten   : {$cleanCount}");

        if ($mismatchCount > 0) {
            $this->error("❌ Saldo mismatch    : {$mismatchCount}");
            $this->newLine();
            $this->table(
                ['ID', 'Nama', 'NIK', 'Saldo DB', 'Ledger', 'Selisih'],
                $mismatchRows
            );
        } else {
            $this->info('✅ Semua saldo konsisten dengan ledger!');
        }

        if ($negativeCount > 0) {
            $this->error("🚨 Saldo NEGATIF     : {$negativeCount} user!");
        }

        if ($orphanLedgers > 0) {
            $this->warn("⚠️  Ledger orphan     : {$orphanLedgers} entry (reference null)");
        }

        if ($invalidAmount > 0) {
            $this->error("🚨 Ledger amount ≤ 0 : {$invalidAmount} entry!");
        }

        // --- 6. Log hasil audit ---
        $logData = [
            'total_users'    => $totalUsers,
            'mismatch'       => $mismatchCount,
            'negative_saldo' => $negativeCount,
            'orphan_ledgers' => $orphanLedgers,
            'invalid_amount' => $invalidAmount,
            'auto_fix'       => $shouldFix && $mismatchCount > 0,
        ];

        if ($mismatchCount > 0 || $negativeCount > 0 || $orphanLedgers > 0 || $invalidAmount > 0) {
            Log::warning('[AuditSaldo] Mismatch ditemukan!', $logData);
        } else {
            Log::info('[AuditSaldo] Semua saldo OK.', $logData);
        }

        $this->newLine();

        if ($shouldFix && $mismatchCount > 0) {
            $this->warn("⚠️  Auto-fix telah diterapkan pada {$mismatchCount} user. Periksa log untuk detail.");
        }

        if ($mismatchCount > 0 || $negativeCount > 0) {
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
