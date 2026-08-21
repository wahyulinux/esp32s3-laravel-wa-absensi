<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class RfidLogService
{
    private const KEY     = 'rfid_scan_logs';
    private const MAX_AGE = 7;       // hari
    private const MAX_LEN = 10000;   // batas entri supaya cache tidak membengkak

    public function append(
        string  $rfidUid,
        ?string $deviceId,
        ?string $ip,
        string  $status,         // masuk | keluar | tidak_dikenal | sudah_lengkap
        ?int    $siswaId   = null,
        ?string $siswaNama = null,
        ?string $siswaKelas = null,
    ): void {
        $entries   = $this->read();
        $entries[] = [
            'time'        => now()->timestamp,
            'rfid_uid'    => strtoupper($rfidUid),
            'device_id'   => $deviceId,
            'ip'          => $ip,
            'status'      => $status,
            'siswa_id'    => $siswaId,
            'siswa_nama'  => $siswaNama,
            'siswa_kelas' => $siswaKelas,
        ];

        // Urutkan terbaru di atas, buang yang > 7 hari, batasi jumlah
        $cutoff  = now()->subDays(self::MAX_AGE)->timestamp;
        $entries = array_filter($entries, fn ($e) => $e['time'] >= $cutoff);
        $entries = array_reverse(array_values($entries)); // terbaru duluan
        $entries = array_slice($entries, 0, self::MAX_LEN);

        Cache::put(self::KEY, $entries, now()->addDays(self::MAX_AGE));
    }

    public function all(): array
    {
        return $this->read();
    }

    private function read(): array
    {
        $entries = Cache::get(self::KEY, []);
        $cutoff  = now()->subDays(self::MAX_AGE)->timestamp;
        return array_values(array_filter($entries, fn ($e) => ($e['time'] ?? 0) >= $cutoff));
    }
}
