<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebSiswaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Siswa::query()->orderBy('kelas')->orderBy('nama');

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

        $siswa = $query->paginate(20);
        $kelasList = Siswa::whereNotNull('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        return view('siswa.index', compact('siswa', 'kelasList'));
    }

    public function create(): View
    {
        return view('siswa.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'rfid_uid'   => 'required|string|max:20|unique:siswa,rfid_uid',
            'nis'        => 'nullable|string|max:20|unique:siswa,nis',
            'nama'       => 'required|string|max:100',
            'kelas'      => 'nullable|string|max:20',
            'no_hp_ortu' => 'nullable|string|max:20',
        ]);

        $validated['rfid_uid'] = strtoupper($validated['rfid_uid']);
        Siswa::create($validated);

        return redirect()->route('siswa.index')
            ->with('success', "Siswa {$validated['nama']} berhasil didaftarkan.");
    }

    public function edit(Siswa $siswa): View
    {
        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        $validated = $request->validate([
            'rfid_uid'   => "required|string|max:20|unique:siswa,rfid_uid,{$siswa->id}",
            'nis'        => "nullable|string|max:20|unique:siswa,nis,{$siswa->id}",
            'nama'       => 'required|string|max:100',
            'kelas'      => 'nullable|string|max:20',
            'no_hp_ortu' => 'nullable|string|max:20',
            'aktif'      => 'sometimes|boolean',
        ]);

        $validated['rfid_uid'] = strtoupper($validated['rfid_uid']);
        $validated['aktif']    = $request->boolean('aktif');

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
}
