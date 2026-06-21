<?php

use App\Console\Commands\AuditSaldoReconciliation;
use App\Console\Commands\CleanupExpiredRedemptions;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        then: function () {
            Route::middleware('web')->group(realpath(__DIR__.'/../routes/admin.php'));
            Route::middleware('web')->group(realpath(__DIR__.'/../routes/warga.php'));
        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule): void {
        // Audit rekonsiliasi saldo harian — pukul 01:00 dini hari
        // Jalankan: php artisan saldo:audit
        $schedule->command(AuditSaldoReconciliation::class)
            ->dailyAt('01:00')
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/saldo-audit.log'));

        // Bersihkan redemption expired setiap 15 menit
        // Jalankan: php artisan redemption:cleanup
        $schedule->command(CleanupExpiredRedemptions::class)
            ->everyFifteenMinutes()
            ->withoutOverlapping()
            ->runInBackground();
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\PreventBackHistory::class,
            \App\Http\Middleware\LogUserActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();

