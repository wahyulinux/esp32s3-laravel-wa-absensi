<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // ── Absensi (dari ESP32-CAM) ──────────────────────────────────────────
    // POST   /api/v1/absensi        → Terima scan RFID + foto dari ESP32
    // GET    /api/v1/absensi        → Rekap (filter: tanggal, siswa_id, kelas, bulan)
    // GET    /api/v1/absensi/{id}   → Detail satu record absensi
    Route::apiResource('absensi', AbsensiController::class)->only(['store', 'index', 'show']);

    // ── Siswa (manajemen data siswa & kartu RFID) ─────────────────────────
    // POST   /api/v1/siswa              → Daftarkan siswa baru + UID RFID
    // GET    /api/v1/siswa              → Daftar siswa (filter: kelas, aktif)
    // GET    /api/v1/siswa/{id}         → Detail siswa
    // PUT    /api/v1/siswa/{id}         → Update data siswa
    // DELETE /api/v1/siswa/{id}         → Nonaktifkan siswa
    // GET    /api/v1/siswa/{id}/rekap   → Rekap absensi bulanan per siswa
    Route::apiResource('siswa', SiswaController::class);
    Route::get('siswa/{siswa}/rekap', [SiswaController::class, 'rekap']);

});

// Health check
Route::get('/health', fn () => response()->json([
    'status'    => 'ok',
    'service'   => 'Sistem Absensi Siswa ESP32-CAM',
    'timestamp' => now()->toIso8601String(),
]));
