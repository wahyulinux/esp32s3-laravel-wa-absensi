<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Kelas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebKelasController extends Controller
{
    public function index(Request $request): View
    {
        $query = Kelas::with('jurusan')->withCount('siswa')->orderBy('nama');

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where('nama', 'like', "%{$cari}%");
        }

        $kelas = $query->paginate(20);

        return view('kelas.index', compact('kelas'));
    }

    public function create(): View
    {
        $jurusanList = Jurusan::where('aktif', true)->orderBy('nama')->get();

        return view('kelas.create', compact('jurusanList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:30|unique:kelas,nama',
            'jurusan_id' => 'nullable|exists:jurusan,id',
        ]);

        Kelas::create($validated);

        return redirect()->route('kelas.index')
            ->with('success', "Kelas {$validated['nama']} berhasil ditambahkan.");
    }

    public function edit(Kelas $kelas): View
    {
        $jurusanList = Jurusan::where('aktif', true)->orderBy('nama')->get();

        return view('kelas.edit', compact('kelas', 'jurusanList'));
    }

    public function update(Request $request, Kelas $kelas): RedirectResponse
    {
        $validated = $request->validate([
            'nama'       => "required|string|max:30|unique:kelas,nama,{$kelas->id}",
            'jurusan_id' => 'nullable|exists:jurusan,id',
            'aktif'      => 'sometimes|boolean',
        ]);

        $validated['aktif'] = $request->boolean('aktif');

        $namaLama = $kelas->nama;
        $kelas->update($validated);

        if ($namaLama !== $kelas->nama) {
            $kelas->siswa()->update(['kelas' => $kelas->nama]);
        }

        return redirect()->route('kelas.index')
            ->with('success', "Kelas {$kelas->nama} berhasil diperbarui.");
    }

    public function destroy(Kelas $kelas): RedirectResponse
    {
        $jumlahSiswa = $kelas->siswa()->count();

        if ($jumlahSiswa > 0) {
            return redirect()->route('kelas.index')
                ->with('error', "Tidak bisa menghapus {$kelas->nama}, masih dipakai {$jumlahSiswa} siswa.");
        }

        $kelas->delete();

        return redirect()->route('kelas.index')
            ->with('success', "Kelas {$kelas->nama} berhasil dihapus.");
    }
}
