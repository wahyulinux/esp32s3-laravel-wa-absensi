<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function absensi(Request $request): StreamedResponse
    {
        $query = Absensi::with('siswa')->latest('tanggal')->latest('waktu_masuk');

        if ($request->get('mode') === 'hari-ini') {
            $query->whereDate('tanggal', today());
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('kelas')) {
            $query->whereHas('siswa', fn ($q) => $q->where('kelas', $request->kelas));
        }

        if ($request->filled('bulan')) {
            [$tahun, $bulan] = explode('-', $request->bulan);
            $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        }

        $records  = $query->get();
        $filename = 'absensi-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($records) {
            $out = fopen('php://output', 'w');

            // BOM agar Excel buka UTF-8 dengan benar
            fputs($out, "\xEF\xBB\xBF");

            fputcsv($out, ['No', 'Tanggal', 'NIS', 'Nama', 'Kelas', 'Jam Masuk', 'Jam Pulang', 'Durasi (H:i)', 'Status']);

            foreach ($records as $i => $row) {
                $durasi = null;
                if ($row->waktu_masuk && $row->waktu_keluar) {
                    $durasi = gmdate('H:i', $row->waktu_masuk->diffInSeconds($row->waktu_keluar));
                }

                fputcsv($out, [
                    $i + 1,
                    $row->tanggal->format('d/m/Y'),
                    $row->siswa->nis ?? '',
                    $row->siswa->nama,
                    $row->siswa->kelas ?? '',
                    $row->waktu_masuk?->format('H:i:s') ?? '',
                    $row->waktu_keluar?->format('H:i:s') ?? '',
                    $durasi ?? '',
                    $row->waktu_keluar ? 'Lengkap' : 'Belum Pulang',
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
