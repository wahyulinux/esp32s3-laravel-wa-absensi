@extends('layouts.app')
@section('title', 'Edit Jurusan')

@section('breadcrumb')
    <a href="{{ route('jurusan.index') }}" class="text-gray-400 hover:text-blue-600 transition-colors text-sm">Jurusan</a>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-gray-300"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg>
    <span class="text-gray-700 text-sm font-medium">Edit Jurusan</span>
@endsection

@section('content')

<div class="max-w-xl">

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">{{ $jurusan->nama }}</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ $jurusan->siswa()->count() }} siswa terdaftar di jurusan ini</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/60">
            <p class="text-sm font-semibold text-gray-700">Edit Informasi Jurusan</p>
        </div>
        <div class="p-6">
        <form method="POST" action="{{ route('jurusan.update', $jurusan) }}" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kode Jurusan</label>
                <input type="text" name="kode" value="{{ old('kode', $jurusan->kode) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono tracking-widest uppercase
                              focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow
                              @error('kode') border-red-400 bg-red-50 @enderror">
                @error('kode') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Jurusan</label>
                <input type="text" name="nama" value="{{ old('nama', $jurusan->nama) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow
                              @error('nama') border-red-400 bg-red-50 @enderror">
                @error('nama') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 py-3 px-4 bg-gray-50 rounded-xl border border-gray-200">
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" name="aktif" value="1" {{ old('aktif', $jurusan->aktif) ? 'checked' : '' }}
                               class="sr-only peer" id="aktif-toggle">
                        <div class="w-10 h-5 bg-gray-300 peer-checked:bg-blue-500 rounded-full transition-colors peer-focus:ring-2 peer-focus:ring-blue-300"></div>
                        <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Jurusan aktif</span>
                </label>
            </div>
            <p class="text-gray-400 text-xs -mt-3">Jurusan nonaktif tidak akan muncul di pilihan saat mendaftarkan siswa baru.</p>

            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                    Simpan Perubahan
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
