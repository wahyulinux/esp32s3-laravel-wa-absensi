<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Device;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalSiswa     = Siswa::where('aktif', true)->count();
        $hadirHariIni   = Absensi::whereDate('tanggal', today())->count();
        $sudahPulang    = Absensi::whereDate('tanggal', today())->whereNotNull('waktu_keluar')->count();
        $belumPulang    = $hadirHariIni - $sudahPulang;
        $absensiTerbaru = Absensi::with('siswa')->whereDate('tanggal', today())->latest('waktu_masuk')->limit(10)->get();

        $semuaKelas = Siswa::where('aktif', true)->whereNotNull('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
        $perKelas   = [];
        foreach ($semuaKelas as $kelas) {
            $total  = Siswa::where('aktif', true)->where('kelas', $kelas)->count();
            $hadir  = Absensi::whereDate('tanggal', today())
                ->whereHas('siswa', fn ($q) => $q->where('kelas', $kelas))->count();
            $perKelas[$kelas] = compact('total', 'hadir');
        }

        $devices = Device::orderBy('device_id')->get();

        return view('dashboard.index', compact(
            'totalSiswa', 'hadirHariIni', 'sudahPulang', 'belumPulang', 'absensiTerbaru', 'perKelas', 'devices'
        ));
    }

    public function stats(): JsonResponse
    {
        $total  = Siswa::where('aktif', true)->count();
        $hadir  = Absensi::whereDate('tanggal', today())->count();
        $pulang = Absensi::whereDate('tanggal', today())->whereNotNull('waktu_keluar')->count();
        $belum  = $hadir - $pulang;

        return response()->json([
            'total'      => $total,
            'hadir'      => $hadir,
            'pulang'     => $pulang,
            'belum'      => $belum,
            'pct_hadir'  => $total  > 0 ? round($hadir  / $total  * 100) : 0,
            'pct_pulang' => $hadir  > 0 ? round($pulang / $hadir  * 100) : 0,
            'pct_belum'  => $hadir  > 0 ? round($belum  / $hadir  * 100) : 0,
        ]);
    }

    public function devices(): JsonResponse
    {
        $devices = Device::orderBy('device_id')->get()->map(fn (Device $d) => [
            'device_id' => $d->device_id,
            'nama'      => $d->nama,
            'ip'        => $d->ip_address,
            'last_seen' => $d->last_seen?->format('H:i:s'),
            'status'    => $d->status,
        ]);

        return response()->json($devices);
    }

    public function feed(): JsonResponse
    {
        $items = Absensi::with('siswa')->whereDate('tanggal', today())->latest('waktu_masuk')->limit(10)->get();

        return response()->json($items->map(fn (Absensi $a) => [
            'nama'         => $a->siswa->nama,
            'nis'          => $a->siswa->nis,
            'kelas'        => $a->siswa->kelas,
            'device_id'    => $a->device_id,
            'waktu_masuk'  => $a->waktu_masuk?->format('H:i'),
            'waktu_keluar' => $a->waktu_keluar?->format('H:i'),
            'foto_url'     => $a->foto_masuk ? Storage::disk('public')->url($a->foto_masuk) : null,
            'avatar_color' => avatarColor($a->siswa->nama),
            'initials'     => avatarInitials($a->siswa->nama),
        ]));
    }
}
