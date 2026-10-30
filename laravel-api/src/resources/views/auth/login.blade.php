@extends('layouts.auth')
@section('title', 'Masuk')

@section('content')
<div class="mb-7 flex flex-col items-center text-center">
    <div class="mb-4 flex h-16 w-16 items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-white p-2 shadow-sm">
        <img src="{{ asset('images/logo-sekolah.png') }}" alt="{{ $namaSekolah }}" class="h-full w-full object-contain">
    </div>
    <h1 class="text-lg font-semibold tracking-tight text-slate-900">{{ $namaSekolah }}</h1>
    <p class="mt-1 text-sm text-slate-500">Sistem Absensi SMKS Nurul Amalia</p>
</div>

<x-card class="p-6 sm:p-8">
    <div class="mb-6">
        <h2 class="text-xl font-semibold tracking-tight text-slate-900">Masuk ke Sistem</h2>
        <p class="mt-1 text-sm text-slate-500">Masukkan kredensial akun Anda</p>
    </div>

    <form method="POST" action="{{ route('login.attempt') }}" class="space-y-5">
        @csrf

        @if($errors->has('username') || session('status'))
        <div class="flex items-start gap-2.5 rounded-lg border border-red-200 bg-red-50 px-3.5 py-3 text-sm text-red-700" role="alert">
            <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke-width="1.8"/><path d="m9 9 6 6m0-6-6 6" stroke-width="1.8" stroke-linecap="round"/></svg>
            <span>{{ $errors->first('username') ?: session('status') }}</span>
        </div>
        @endif

        <div>
            <x-label for="username">Username</x-label>
            <x-input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus autocomplete="username"
                     class="@error('username') border-red-400 bg-red-50 @enderror" />
        </div>

        <div>
            <x-label for="password">Password</x-label>
            <x-input type="password" id="password" name="password" required autocomplete="current-password"
                     class="@error('password') border-red-400 bg-red-50 @enderror" />
            @error('password')
            <p class="mt-1.5 flex items-center gap-1.5 text-sm text-red-600">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke-width="1.8"/><path d="m9 9 6 6m0-6-6 6" stroke-width="1.8" stroke-linecap="round"/></svg>
                {{ $message }}
            </p>
            @enderror
        </div>

        <label class="flex cursor-pointer items-center gap-2.5 text-sm text-slate-600">
            <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
            <span>Ingat saya di perangkat ini</span>
        </label>

        <x-button.primary type="submit" class="w-full py-2.5">Masuk Sekarang</x-button.primary>
    </form>
</x-card>

<a href="{{ route('dashboard') }}" class="mt-6 inline-flex w-full items-center justify-center gap-2 text-sm font-medium text-slate-500 transition-colors hover:text-blue-700">
    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path d="m12 19-7-7 7-7m-7 7h14" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
    <span>Kembali ke dashboard utama</span>
</a>
@endsection
