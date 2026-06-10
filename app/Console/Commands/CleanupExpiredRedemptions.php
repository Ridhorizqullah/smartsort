<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Redemption;
use App\Services\RedemptionService;

class CleanupExpiredRedemptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redemption:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membatalkan (reject) secara otomatis request redemption yang berstatus pending dan sudah melewati batas waktu kadaluwarsa.';

    /**
     * Execute the console command.
     */
    public function handle(RedemptionService $service)
    {
        $expiredRedemptions = Redemption::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->get();

        if ($expiredRedemptions->isEmpty()) {
            $this->info("Tidak ada request redemption yang kadaluwarsa saat ini.");
            return;
        }

        $count = 0;
        foreach ($expiredRedemptions as $redemption) {
            try {
                // Gunakan adminId = null atau ID sistem jika ada, dengan catatan khusus
                $service->rejectRedemption($redemption->id, null, "Sistem: Expired (Dibatalkan Otomatis)");
                $count++;
            } catch (\Exception $e) {
                $this->error("Gagal membatalkan redemption ID {$redemption->id}: " . $e->getMessage());
            }
        }

        $this->info("Berhasil membersihkan {$count} request redemption yang kedaluwarsa.");
    }
}
