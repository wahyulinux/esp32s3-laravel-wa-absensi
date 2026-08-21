<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use App\Services\RfidLogService;
use App\Services\WaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AbsensiController extends Controller
{
    public function __construct(private WaService $wa, private RfidLogService $rfidLog) {}

    /**
     * Menerima scan RFID + foto dari ESP32-CAM.
     * POST /api/v1/absensi
     * Body: { "rfid_uid": "AABBCCDD", "image_data": "<base64>" }
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rfid_uid'   => 'required|string|max:20',
            'image_data' => 'required|string',
            'device_id'  => 'nullable|string|max:10',
        ]);

        $siswa = Siswa::where('rfid_uid', strtoupper($validated['rfid_uid']))
            ->where('aktif', true)
            ->first();

        if (! $siswa) {
            $this->rfidLog->append(
                rfidUid:  $validated['rfid_uid'],
                deviceId: $validated['device_id'] ?? null,
                ip:       $request->ip(),
                status:   'tidak_dikenal',
            );
            return response()->json([
                'success'  => false,
                'message'  => 'Kartu RFID tidak terdaftar atau siswa tidak aktif',
                'rfid_uid' => strtoupper($validated['rfid_uid']),
            ], 404);
        }

        $binaryImage = base64_decode($validated['image_data'], strict: true);
        if ($binaryImage === false) {
            return response()->json([
                'success' => false,
                'message' => 'Format base64 foto tidak valid',
            ], 422);
        }

        $fotoPath = 'absensi/' . now()->format('Y/m/d') . '/' . Str::uuid() . '.jpg';
        Storage::disk('public')->put($fotoPath, $binaryImage);

        $absensiHariIni = Absensi::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', today())
            ->first();

        $deviceId = isset($validated['device_id']) ? strtoupper($validated['device_id']) : null;

        // Belum ada record hari ini → catat MASUK
        if (! $absensiHariIni) {
            $absensi = Absensi::create([
                'siswa_id'    => $siswa->id,
                'device_id'   => $deviceId,
                'tanggal'     => today(),
                'waktu_masuk' => now(),
                'foto_masuk'  => $fotoPath,
            ]);

            $this->rfidLog->append(
                rfidUid:    $validated['rfid_uid'],
                deviceId:   $deviceId,
                ip:         $request->ip(),
                status:     'masuk',
                siswaId:    $siswa->id,
                siswaNama:  $siswa->nama,
                siswaKelas: $siswa->kelas,
            );

            if ($siswa->no_hp_ortu) {
                $waktu   = now()->format('H:i');
                $caption = "✅ *Absensi Masuk*\n" .
                           "Siswa: *{$siswa->nama}*\n" .
                           "Kelas: {$siswa->kelas}\n" .
                           "Waktu masuk: {$waktu}\n" .
                           "Tanggal: " . today()->translatedFormat('l, d F Y');
                $this->wa->sendImage($siswa->no_hp_ortu, $caption, $fotoPath);
            }

            return response()->json([
                'success' => true,
                'status'  => 'masuk',
                'message' => "Selamat datang, {$siswa->nama}! ({$siswa->kelas})",
                'data'    => $this->formatAbsensi($absensi, $siswa),
            ], 201);
        }

        // Sudah masuk, belum keluar → cek durasi minimal 1 jam
        if (is_null($absensiHariIni->waktu_keluar)) {
            $menitSejakMasuk = $absensiHariIni->waktu_masuk->diffInMinutes(now());
            if ($menitSejakMasuk < 60) {
                Storage::disk('public')->delete($fotoPath);
                $sisaMenit = 60 - $menitSejakMasuk;
                return response()->json([
                    'success' => false,
                    'status'  => 'terlalu_cepat',
                    'message' => "Belum bisa pulang, {$siswa->nama}. Tunggu {$sisaMenit} menit lagi.",
                ], 422);
            }

            $absensiHariIni->update([
                'waktu_keluar' => now(),
                'foto_keluar'  => $fotoPath,
            ]);

            $durasi = $absensiHariIni->waktu_masuk->diffForHumans(now(), true);

            $this->rfidLog->append(
                rfidUid:    $validated['rfid_uid'],
                deviceId:   $deviceId,
                ip:         $request->ip(),
                status:     'keluar',
                siswaId:    $siswa->id,
                siswaNama:  $siswa->nama,
                siswaKelas: $siswa->kelas,
            );

            if ($siswa->no_hp_ortu) {
                $waktuKeluar = now()->format('H:i');
                $caption     = "🏠 *Absensi Pulang*\n" .
                               "Siswa: *{$siswa->nama}*\n" .
                               "Kelas: {$siswa->kelas}\n" .
                               "Waktu pulang: {$waktuKeluar}\n" .
                               "Durasi belajar: {$durasi}";
                $this->wa->sendImage($siswa->no_hp_ortu, $caption, $fotoPath);
            }

            return response()->json([
                'success' => true,
                'status'  => 'keluar',
                'message' => "Sampai jumpa, {$siswa->nama}! Durasi: {$durasi}",
                'data'    => $this->formatAbsensi($absensiHariIni->fresh(), $siswa),
            ]);
        }

        // Sudah masuk & keluar → tolak scan ketiga
        Storage::disk('public')->delete($fotoPath);
        $this->rfidLog->append(
            rfidUid:    $validated['rfid_uid'],
            deviceId:   $deviceId,
            ip:         $request->ip(),
            status:     'sudah_lengkap',
            siswaId:    $siswa->id,
            siswaNama:  $siswa->nama,
            siswaKelas: $siswa->kelas,
        );

        return response()->json([
            'success' => false,
            'status'  => 'sudah_lengkap',
            'message' => "{$siswa->nama} sudah absen masuk dan pulang hari ini",
            'data'    => $this->formatAbsensi($absensiHariIni, $siswa),
        ], 409);
    }

    /**
     * Rekap absensi.
     * GET /api/v1/absensi?tanggal=2024-06-14&siswa_id=1&kelas=10A
     */
    public function index(Request $request): JsonResponse
    {
        $query = Absensi::with('siswa')->latest('tanggal')->latest('waktu_masuk');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }

        if ($request->filled('kelas')) {
            $query->whereHas('siswa', fn ($q) => $q->where('kelas', $request->kelas));
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan)
                  ->whereYear('tanggal', $request->get('tahun', now()->year));
        }

        $records = $query->paginate($request->integer('per_page', 20));

        return response()->json([
            'success' => true,
            'data'    => $records->map(fn (Absensi $a) => $this->formatAbsensi($a, $a->siswa)),
            'meta'    => [
                'current_page' => $records->currentPage(),
                'last_page'    => $records->lastPage(),
                'per_page'     => $records->perPage(),
                'total'        => $records->total(),
            ],
        ]);
    }

    /**
     * Detail satu record absensi.
     * GET /api/v1/absensi/{id}
     */
    public function show(Absensi $absensi): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $this->formatAbsensi($absensi->load('siswa'), $absensi->siswa),
        ]);
    }

    private function formatAbsensi(Absensi $absensi, ?Siswa $siswa): array
    {
        $durasi = null;
        if ($absensi->waktu_masuk && $absensi->waktu_keluar) {
            $durasi = gmdate('H:i', $absensi->waktu_masuk->diffInSeconds($absensi->waktu_keluar));
        }

        return [
            'id'              => $absensi->id,
            'device_id'       => $absensi->device_id,
            'siswa'           => $siswa ? [
                'id'    => $siswa->id,
                'nis'   => $siswa->nis,
                'nama'  => $siswa->nama,
                'kelas' => $siswa->kelas,
            ] : null,
            'tanggal'         => $absensi->tanggal?->toDateString(),
            'waktu_masuk'     => $absensi->waktu_masuk?->toTimeString(),
            'foto_masuk_url'  => $absensi->foto_masuk_url,
            'waktu_keluar'    => $absensi->waktu_keluar?->toTimeString(),
            'foto_keluar_url' => $absensi->foto_keluar_url,
            'durasi'          => $durasi,
            'status'          => $absensi->waktu_keluar ? 'lengkap' : 'belum_pulang',
        ];
    }
}
