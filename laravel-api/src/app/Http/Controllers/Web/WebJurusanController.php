<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebJurusanController extends Controller
{
    public function index(Request $request): View
    {
        $query = Jurusan::withCount('siswa')->orderBy('nama');

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(fn ($q) => $q
                ->where('nama', 'like', "%{$cari}%")
                ->orWhere('kode', 'like', "%{$cari}%")
            );
        }

        $jurusan = $query->paginate(20);

        return view('jurusan.index', compact('jurusan'));
    }

    public function create(): View
    {
        return view('jurusan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:10|unique:jurusan,kode',
            'nama' => 'required|string|max:100',
        ]);

        $validated['kode'] = strtoupper($validated['kode']);
        Jurusan::create($validated);

        return redirect()->route('jurusan.index')
            ->with('success', "Jurusan {$validated['nama']} berhasil ditambahkan.");
    }

    public function edit(Jurusan $jurusan): View
    {
        return view('jurusan.edit', compact('jurusan'));
    }

    public function update(Request $request, Jurusan $jurusan): RedirectResponse
    {
        $validated = $request->validate([
            'kode'  => "required|string|max:10|unique:jurusan,kode,{$jurusan->id}",
            'nama'  => 'required|string|max:100',
            'aktif' => 'sometimes|boolean',
        ]);

        $validated['kode']  = strtoupper($validated['kode']);
        $validated['aktif'] = $request->boolean('aktif');

        $jurusan->update($validated);

        return redirect()->route('jurusan.index')
            ->with('success', "Jurusan {$jurusan->nama} berhasil diperbarui.");
    }

    public function destroy(Jurusan $jurusan): RedirectResponse
    {
        $jumlahSiswa = $jurusan->siswa()->count();

        if ($jumlahSiswa > 0) {
            return redirect()->route('jurusan.index')
                ->with('error', "Tidak bisa menghapus {$jurusan->nama}, masih dipakai {$jumlahSiswa} siswa.");
        }

        $jurusan->delete();

        return redirect()->route('jurusan.index')
            ->with('success', "Jurusan {$jurusan->nama} berhasil dihapus.");
    }
}
