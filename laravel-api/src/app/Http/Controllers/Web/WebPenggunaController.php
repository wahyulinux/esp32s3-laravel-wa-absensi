<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class WebPenggunaController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::orderBy('role')->orderBy('name');

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$cari}%")
                ->orWhere('username', 'like', "%{$cari}%")
            );
        }

        $pengguna = $query->paginate(20);

        return view('pengguna.index', compact('pengguna'));
    }

    public function create(): View
    {
        return view('pengguna.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username',
            'email'    => 'nullable|email|max:255|unique:users,email',
            'role'     => ['required', Rule::in(array_keys(User::ROLES))],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $validated['username'] = strtolower($validated['username']);
        User::create($validated);

        return redirect()->route('pengguna.index')
            ->with('success', "Pengguna {$validated['name']} berhasil ditambahkan.");
    }

    public function edit(User $pengguna): View
    {
        return view('pengguna.edit', compact('pengguna'));
    }

    public function update(Request $request, User $pengguna): RedirectResponse
    {
        $diriSendiri = $pengguna->is($request->user());

        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users', 'username')->ignore($pengguna->id)],
            'email'    => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($pengguna->id)],
            'role'     => ['required', Rule::in(array_keys(User::ROLES))],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $validated['username'] = strtolower($validated['username']);
        $validated['aktif']    = $request->boolean('aktif');

        // Admin tidak boleh menurunkan role / menonaktifkan akunnya sendiri
        if ($diriSendiri) {
            $validated['role']  = User::ROLE_ADMIN;
            $validated['aktif'] = true;
        }

        if (blank($validated['password'])) {
            unset($validated['password']);
        }

        $pengguna->update($validated);

        return redirect()->route('pengguna.index')
            ->with('success', "Pengguna {$pengguna->name} berhasil diperbarui.");
    }

    public function destroy(Request $request, User $pengguna): RedirectResponse
    {
        if ($pengguna->is($request->user())) {
            return redirect()->route('pengguna.index')
                ->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $pengguna->delete();

        return redirect()->route('pengguna.index')
            ->with('success', "Pengguna {$pengguna->name} berhasil dihapus.");
    }
}
