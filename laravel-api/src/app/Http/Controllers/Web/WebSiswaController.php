<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Setting;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class WebSiswaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Siswa::query()->with('jurusan')->orderBy('kelas')->orderBy('nama');

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(fn ($q) => $q
                ->where('nama', 'like', "%{$cari}%")
                ->orWhere('nis', 'like', "%{$cari}%")
                ->orWhere('rfid_uid', 'like', "%{$cari}%")
            );
        }

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('jurusan_id')) {
            $query->where('jurusan_id', $request->jurusan_id);
        }

        $siswa = $query->paginate(20);
        $kelasList = Siswa::whereNotNull('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
        $jurusanList = Jurusan::where('aktif', true)->orderBy('nama')->get();

        return view('siswa.index', compact('siswa', 'kelasList', 'jurusanList'));
    }

    public function create(): View
    {
        $jurusanList = Jurusan::where('aktif', true)->orderBy('nama')->get();
        $kelasMasterList = Kelas::where('aktif', true)->orderBy('nama')->get();

        return view('siswa.create', compact('jurusanList', 'kelasMasterList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'rfid_uid'   => 'required|string|max:20|unique:siswa,rfid_uid',
            'nis'        => 'nullable|string|max:20|unique:siswa,nis',
            'nama'       => 'required|string|max:100',
            'kelas_id'   => 'nullable|exists:kelas,id',
            'jurusan_id' => 'nullable|exists:jurusan,id',
            'no_hp_ortu' => 'nullable|string|max:20',
            'foto'       => 'nullable|image|max:2048',
        ]);

        $validated['rfid_uid'] = strtoupper($validated['rfid_uid']);
        $validated['kelas']    = $validated['kelas_id'] ? Kelas::find($validated['kelas_id'])->nama : null;

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('siswa', 'public');
        }

        Siswa::create($validated);

        return redirect()->route('siswa.index')
            ->with('success', "Siswa {$validated['nama']} berhasil didaftarkan.");
    }

    public function edit(Siswa $siswa): View
    {
        $jurusanList = Jurusan::where('aktif', true)->orderBy('nama')->get();
        $kelasMasterList = Kelas::where('aktif', true)->orderBy('nama')->get();

        return view('siswa.edit', compact('siswa', 'jurusanList', 'kelasMasterList'));
    }

    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        $validated = $request->validate([
            'rfid_uid'    => "required|string|max:20|unique:siswa,rfid_uid,{$siswa->id}",
            'nis'         => "nullable|string|max:20|unique:siswa,nis,{$siswa->id}",
            'nama'        => 'required|string|max:100',
            'kelas_id'    => 'nullable|exists:kelas,id',
            'jurusan_id'  => 'nullable|exists:jurusan,id',
            'no_hp_ortu'  => 'nullable|string|max:20',
            'aktif'       => 'sometimes|boolean',
            'foto'        => 'nullable|image|max:2048',
            'hapus_foto'  => 'sometimes|boolean',
        ]);

        $validated['rfid_uid'] = strtoupper($validated['rfid_uid']);
        $validated['aktif']    = $request->boolean('aktif');
        $validated['kelas']    = $validated['kelas_id'] ? Kelas::find($validated['kelas_id'])->nama : null;

        if ($request->hasFile('foto')) {
            if ($siswa->foto) {
                Storage::disk('public')->delete($siswa->foto);
            }
            $validated['foto'] = $request->file('foto')->store('siswa', 'public');
        } elseif ($request->boolean('hapus_foto') && $siswa->foto) {
            Storage::disk('public')->delete($siswa->foto);
            $validated['foto'] = null;
        }
        unset($validated['hapus_foto']);

        $siswa->update($validated);

        return redirect()->route('siswa.index')
            ->with('success', "Data {$siswa->nama} berhasil diperbarui.");
    }

    public function destroy(Siswa $siswa): RedirectResponse
    {
        $siswa->update(['aktif' => false]);

        return redirect()->route('siswa.index')
            ->with('success', "{$siswa->nama} telah dinonaktifkan.");
    }

    public function kartu(Siswa $siswa): View
    {
        $namaSekolah = Setting::get('nama_sekolah', 'Sistem Absensi');

        return view('siswa.kartu', compact('siswa', 'namaSekolah'));
    }

    public function kartuMassal(Request $request): View
    {
        $query = Siswa::query()->with('jurusan')->where('aktif', true)->orderBy('kelas')->orderBy('nama');

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(fn ($q) => $q
                ->where('nama', 'like', "%{$cari}%")
                ->orWhere('nis', 'like', "%{$cari}%")
                ->orWhere('rfid_uid', 'like', "%{$cari}%")
            );
        }

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('jurusan_id')) {
            $query->where('jurusan_id', $request->jurusan_id);
        }

        $siswaList   = $query->get();
        $kelas       = $request->get('kelas');
        $namaSekolah = Setting::get('nama_sekolah', 'Sistem Absensi');

        return view('siswa.kartu-massal', compact('siswaList', 'kelas', 'namaSekolah'));
    }
}
