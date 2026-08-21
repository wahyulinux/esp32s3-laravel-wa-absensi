<?php

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ExportController;
use App\Http\Controllers\Web\RfidLogController;
use App\Http\Controllers\Web\WebAbsensiController;
use App\Http\Controllers\Web\WebSiswaController;
use App\Services\WaService;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/stats',   [DashboardController::class, 'stats'])->name('dashboard.stats');
Route::get('/dashboard/feed',    [DashboardController::class, 'feed'])->name('dashboard.feed');
Route::get('/dashboard/devices', [DashboardController::class, 'devices'])->name('dashboard.devices');

Route::prefix('absensi')->name('absensi.')->group(function () {
    Route::get('/hari-ini', [WebAbsensiController::class, 'hariIni'])->name('hari-ini');
    Route::get('/rekap',    [WebAbsensiController::class, 'rekap'])->name('rekap');
    Route::get('/export',   [ExportController::class, 'absensi'])->name('export');
});

Route::resource('siswa', WebSiswaController::class)->except(['show']);

Route::get('/rfid-log',      [RfidLogController::class, 'index'])->name('rfid-log.index');
Route::get('/rfid-log/feed', [RfidLogController::class, 'feed'])->name('rfid-log.feed');

Route::get('/wa-gateway/status', function (WaService $wa) {
    return response()->json($wa->status());
})->name('wa-gateway.status');

Route::post('/wa-gateway/disconnect', function (WaService $wa) {
    return response()->json($wa->disconnect());
})->name('wa-gateway.disconnect');
