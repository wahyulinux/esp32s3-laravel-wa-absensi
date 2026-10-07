@extends('layouts.app')
@section('title', 'Pengaturan')

@section('breadcrumb')
    <span class="text-gray-700 text-sm font-medium">Pengaturan</span>
@endsection

@section('content')

<div class="max-w-xl">

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">Pengaturan</h1>
        <p class="text-sm text-gray-500 mt-0.5">Konfigurasi umum sistem absensi</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/60">
            <p class="text-sm font-semibold text-gray-700">Informasi Sekolah</p>
        </div>
        <div class="p-6">
        <form method="POST" action="{{ route('pengaturan.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Nama Sekolah <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah', $namaSekolah) }}"
                       placeholder="Contoh: SDN 01 Sukamaju"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow
                              @error('nama_sekolah') border-red-400 bg-red-50 @enderror">
                @error('nama_sekolah')
                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
                @enderror
                <p class="text-gray-400 text-xs mt-1.5">Nama ini akan tampil di sidebar dan judul halaman</p>
            </div>

            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection
