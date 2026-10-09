@php
    $user        = $pengguna ?? null;
    $diriSendiri = $user && $user->is(auth()->user());
    $inputClass  = 'w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow';
@endphp

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
        Nama Lengkap <span class="text-red-500">*</span>
    </label>
    <input type="text" name="name" value="{{ old('name', $user?->name) }}" required
           class="{{ $inputClass }} @error('name') border-red-400 bg-red-50 @enderror">
    @error('name')
    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
        Username <span class="text-red-500">*</span>
    </label>
    <input type="text" name="username" value="{{ old('username', $user?->username) }}" required autocomplete="off"
           class="{{ $inputClass }} font-mono lowercase @error('username') border-red-400 bg-red-50 @enderror">
    @error('username')
    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
    @enderror
    <p class="text-gray-400 text-xs mt-1.5">Huruf, angka, tanda - dan _ saja. Dipakai untuk login.</p>
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
    <input type="email" name="email" value="{{ old('email', $user?->email) }}"
           class="{{ $inputClass }} @error('email') border-red-400 bg-red-50 @enderror">
    @error('email')
    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
    @enderror
    <p class="text-gray-400 text-xs mt-1.5">Opsional</p>
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
        Role <span class="text-red-500">*</span>
    </label>
    <select name="role" {{ $diriSendiri ? 'disabled' : '' }}
            class="{{ $inputClass }} bg-white disabled:bg-gray-50 disabled:text-gray-400 @error('role') border-red-400 bg-red-50 @enderror">
        @foreach(\App\Models\User::ROLES as $value => $label)
        <option value="{{ $value }}" {{ old('role', $user?->role ?? 'operator') === $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    @if($diriSendiri)
    <input type="hidden" name="role" value="{{ $user->role }}">
    <p class="text-gray-400 text-xs mt-1.5">Anda tidak bisa mengubah role akun sendiri.</p>
    @else
    <p class="text-gray-400 text-xs mt-1.5">Operator: kelola data siswa/kelas/jurusan tanpa hapus. Admin: akses penuh.</p>
    @endif
    @error('role')
    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
    @enderror
</div>

<div class="grid sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
            Password @if(!$user)<span class="text-red-500">*</span>@endif
        </label>
        <input type="password" name="password" autocomplete="new-password" {{ $user ? '' : 'required' }}
               class="{{ $inputClass }} @error('password') border-red-400 bg-red-50 @enderror">
        @error('password')
        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ulangi Password</label>
        <input type="password" name="password_confirmation" autocomplete="new-password" {{ $user ? '' : 'required' }}
               class="{{ $inputClass }}">
    </div>
</div>
<p class="text-gray-400 text-xs -mt-3">
    Minimal 8 karakter.@if($user) Kosongkan jika tidak ingin mengganti password.@endif
</p>

@if($user && !$diriSendiri)
<div class="flex items-center gap-3 py-3 px-4 bg-gray-50 rounded-xl border border-gray-200">
    <label class="flex items-center gap-3 cursor-pointer">
        <div class="relative">
            <input type="checkbox" name="aktif" value="1" {{ old('aktif', $user->aktif) ? 'checked' : '' }}
                   class="sr-only peer" id="aktif-toggle">
            <div class="w-10 h-5 bg-gray-300 peer-checked:bg-blue-500 rounded-full transition-colors peer-focus:ring-2 peer-focus:ring-blue-300"></div>
            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
        </div>
        <span class="text-sm font-medium text-gray-700">Akun aktif</span>
    </label>
</div>
<p class="text-gray-400 text-xs -mt-3">Akun nonaktif tidak bisa login.</p>
@endif
