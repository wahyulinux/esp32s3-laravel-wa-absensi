<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Daftar semua siswa.
     * GET /api/v1/siswa?kelas=10A&aktif=1
     */
    public function index(Request $request): JsonResponse
    {
        $siswa = Siswa::query()
            ->when($request->filled('kelas'), fn ($q) => $q->where('kelas', $request->kelas))
            ->when($request->filled('aktif'), fn ($q) => $q->where('aktif', $request->boolean('aktif')))
            ->orderBy('kelas')
            ->orderBy('nama')
            ->get()
            ->map(fn (Siswa $s) => $this->format($s));

        return response()->json(['success' => true, 'data' => $siswa]);
    }

    /**
     * Daftarkan siswa baru beserta UID kartu RFID.
     * POST /api/v1/siswa
     * Body: { "rfid_uid": "AABBCCDD", "nis": "12345", "nama": "Andi", "kelas": "10A" }
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rfid_uid' => 'required|string|max:20|unique:siswa,rfid_uid',
            'nis'      => 'nullable|string|max:20|unique:siswa,nis',
            'nama'     => 'required|string|max:100',
            'kelas'    => 'nullable|string|max:20',
        ]);

        $validated['rfid_uid'] = strtoupper($validated['rfid_uid']);
        $siswa = Siswa::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil didaftarkan',
            'data'    => $this->format($siswa),
        ], 201);
    }

    /**
     * Detail satu siswa.
     * GET /api/v1/siswa/{id}
     */
    public function show(Siswa $siswa): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $this->format($siswa)]);
    }

    /**
     * Update data siswa.
     * PUT /api/v1/siswa/{id}
     */
    public function update(Request $request, Siswa $siswa): JsonResponse
    {
        $validated = $request->validate([
            'rfid_uid' => "sometimes|string|max:20|unique:siswa,rfid_uid,{$siswa->id}",
            'nis'      => "sometimes|nullable|string|max:20|unique:siswa,nis,{$siswa->id}",
            'nama'     => 'sometimes|string|max:100',
            'kelas'    => 'sometimes|nullable|string|max:20',
            'aktif'    => 'sometimes|boolean',
        ]);

        if (isset($validated['rfid_uid'])) {
            $validated['rfid_uid'] = strtoupper($validated['rfid_uid']);
        }

        $siswa->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data siswa diperbarui',
            'data'    => $this->format($siswa->fresh()),
        ]);
    }

    /**
     * Nonaktifkan siswa.
     * DELETE /api/v1/siswa/{id}
     */
    public function destroy(Siswa $siswa): JsonResponse
    {
        $siswa->update(['aktif' => false]);

        return response()->json([
            'success' => true,
            'message' => "{$siswa->nama} dinonaktifkan",
        ]);
    }

    /**
     * Rekap absensi bulanan per siswa.
     * GET /api/v1/siswa/{id}/rekap?bulan=6&tahun=2024
     */
    public function rekap(Request $request, Siswa $siswa): JsonResponse
    {
        $bulan = $request->integer('bulan', now()->month);
        $tahun = $request->integer('tahun', now()->year);

        $records = $siswa->absensi()
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal')
            ->get();

        $totalHadir   = $records->count();
        $sudahPulang  = $records->whereNotNull('waktu_keluar')->count();

        return response()->json([
            'success'   => true,
            'siswa'     => $this->format($siswa),
            'periode'   => sprintf('%04d-%02d', $tahun, $bulan),
            'ringkasan' => [
                'total_hadir'  => $totalHadir,
                'sudah_pulang' => $sudahPulang,
                'belum_pulang' => $totalHadir - $sudahPulang,
            ],
            'data' => $records->map(fn (Absensi $a) => [
                'tanggal'      => $a->tanggal->toDateString(),
                'waktu_masuk'  => $a->waktu_masuk?->toTimeString(),
                'waktu_keluar' => $a->waktu_keluar?->toTimeString(),
                'durasi'       => ($a->waktu_masuk && $a->waktu_keluar)
                    ? gmdate('H:i', $a->waktu_masuk->diffInSeconds($a->waktu_keluar))
                    : null,
                'status'       => $a->waktu_keluar ? 'lengkap' : 'belum_pulang',
            ]),
        ]);
    }

    private function format(Siswa $s): array
    {
        return [
            'id'       => $s->id,
            'rfid_uid' => $s->rfid_uid,
            'nis'      => $s->nis,
            'nama'     => $s->nama,
            'kelas'    => $s->kelas,
            'aktif'    => $s->aktif,
        ];
    }
}
