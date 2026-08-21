<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Device;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebAbsensiController extends Controller
{
    public function hariIni(Request $request): View
    {
        $query = Absensi::with('siswa')->whereDate('tanggal', today())->latest('waktu_masuk');

        if ($request->filled('kelas')) {
            $query->whereHas('siswa', fn ($q) => $q->where('kelas', $request->kelas));
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->whereHas('siswa', fn ($q) => $q->where('nama', 'like', "%{$cari}%"));
        }

        $absensi     = $query->paginate(25);
        $sudahPulang = Absensi::whereDate('tanggal', today())
            ->when($request->filled('kelas'), fn ($q) => $q->whereHas('siswa', fn ($s) => $s->where('kelas', $request->kelas)))
            ->whereNotNull('waktu_keluar')->count();
        $belumPulang = $absensi->total() - $sudahPulang;

        $kelasList   = Siswa::where('aktif', true)->whereNotNull('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
        $deviceNames = Device::pluck('nama', 'device_id');

        return view('absensi.hari-ini', compact('absensi', 'sudahPulang', 'belumPulang', 'kelasList', 'deviceNames'));
    }

    public function rekap(Request $request): View
    {
        $query = Absensi::with('siswa')->latest('tanggal')->latest('waktu_masuk');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('kelas')) {
            $query->whereHas('siswa', fn ($q) => $q->where('kelas', $request->kelas));
        }

        if ($request->filled('bulan')) {
            [$tahun, $bulan] = array_pad(explode('-', $request->bulan), 2, null);
            if ($bulan) {
                $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            }
        }

        $absensi = $query->paginate(25);

        // Statistik dari semua record (bukan hanya halaman ini)
        $allQuery  = clone $query->getQuery();
        $lengkap   = (clone $allQuery)->whereNotNull('waktu_keluar')->count();
        $totalRec  = $absensi->total();

        // Rata-rata durasi (hanya yang sudah pulang)
        $durations = Absensi::with('siswa')
            ->when($request->filled('tanggal'), fn ($q) => $q->whereDate('tanggal', $request->tanggal))
            ->when($request->filled('kelas'),   fn ($q) => $q->whereHas('siswa', fn ($s) => $s->where('kelas', $request->kelas)))
            ->when($request->filled('bulan'), function ($q) use ($request) {
                [$tahun, $bulan] = array_pad(explode('-', $request->bulan), 2, null);
                if ($bulan) $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            })
            ->whereNotNull('waktu_keluar')
            ->get(['waktu_masuk', 'waktu_keluar'])
            ->map(fn ($a) => $a->waktu_masuk->diffInSeconds($a->waktu_keluar));

        $rataDetik  = $durations->count() > 0 ? $durations->average() : 0;
        $rataDurasi = $rataDetik > 0 ? gmdate('H:i', $rataDetik) : '—';

        $stats = [
            'lengkap'      => $lengkap,
            'belum_pulang' => $totalRec - $lengkap,
            'rata_durasi'  => $rataDurasi,
        ];

        $kelasList   = Siswa::where('aktif', true)->whereNotNull('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
        $deviceNames = Device::pluck('nama', 'device_id');

        return view('absensi.rekap', compact('absensi', 'kelasList', 'stats', 'deviceNames'));
    }
}
