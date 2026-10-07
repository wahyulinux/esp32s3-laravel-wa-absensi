<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        $namaSekolah = Setting::get('nama_sekolah', 'Sistem Absensi');

        return view('pengaturan.edit', compact('namaSekolah'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_sekolah' => 'required|string|max:150',
        ]);

        Setting::set('nama_sekolah', $validated['nama_sekolah']);

        return redirect()->route('pengaturan.edit')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
