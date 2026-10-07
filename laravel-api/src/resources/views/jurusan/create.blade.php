@extends('layouts.app')
@section('title', 'Tambah Jurusan')

@section('breadcrumb')
    <a href="{{ route('jurusan.index') }}" class="text-gray-400 hover:text-blue-600 transition-colors text-sm">Jurusan</a>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-gray-300"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg>
    <span class="text-gray-700 text-sm font-medium">Tambah Jurusan</span>
@endsection

@section('content')

<div class="max-w-xl">

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">Tambah Jurusan</h1>
        <p class="text-sm text-gray-500 mt-0.5">Daftarkan jurusan / kompetensi keahlian baru</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/60">
            <p class="text-sm font-semibold text-gray-700">Informasi Jurusan</p>
        </div>
        <div class="p-6">
        <form method="POST" action="{{ route('jurusan.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Kode Jurusan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="kode" value="{{ old('kode') }}"
                       placeholder="Contoh: RPL, TKJ, MM"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono tracking-widest uppercase
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow
                              @error('kode') border-red-400 bg-red-50 @enderror">
                @error('kode')
                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
                <p class="text-gray-400 text-xs mt-1.5">Singkatan unik, maks 10 karakter</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Nama Jurusan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                       placeholder="Contoh: Rekayasa Perangkat Lunak"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow
                              @error('nama') border-red-400 bg-red-50 @enderror">
                @error('nama')
                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                    Simpan Jurusan
                </button>
                <a href="{{ route('jurusan.index') }}"
                   class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors">
                    Batal
                </a>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection
