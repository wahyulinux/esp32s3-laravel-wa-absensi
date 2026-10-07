@extends('layouts.app')
@section('title', 'Daftarkan Siswa')

@section('breadcrumb')
    <a href="{{ route('siswa.index') }}" class="text-gray-400 hover:text-blue-600 transition-colors text-sm">Data Siswa</a>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-gray-300"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg>
    <span class="text-gray-700 text-sm font-medium">Daftarkan Siswa</span>
@endsection

@section('content')

<div class="max-w-xl">

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">Daftarkan Siswa Baru</h1>
        <p class="text-sm text-gray-500 mt-0.5">Isi data siswa dan UID kartu RFID</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/60">
            <p class="text-sm font-semibold text-gray-700">Informasi Siswa</p>
        </div>
        <div class="p-6">
        <form method="POST" action="{{ route('siswa.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            @include('siswa.partials.foto-input', ['allowHapus' => false])

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    UID Kartu RFID <span class="text-red-500">*</span>
                </label>
                <input type="text" name="rfid_uid" value="{{ old('rfid_uid') }}"
                       placeholder="Contoh: AABBCCDD"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono tracking-widest uppercase
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow
                              @error('rfid_uid') border-red-400 bg-red-50 @enderror">
                @error('rfid_uid')
                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
                @enderror
                <p class="text-gray-400 text-xs mt-1.5">Scan kartu RFID ke reader untuk mendapatkan UID</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">NIS <span class="text-gray-400 font-normal">(opsional)</span></label>
                <input type="text" name="nis" value="{{ old('nis') }}"
                       placeholder="Nomor Induk Siswa"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow
                              @error('nis') border-red-400 bg-red-50 @enderror">
                @error('nis')
                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                       placeholder="Nama lengkap siswa"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow
                              @error('nama') border-red-400 bg-red-50 @enderror">
                @error('nama')
                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kelas <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <select name="kelas_id"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-white
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow
                                   @error('kelas_id') border-red-400 bg-red-50 @enderror">
                        <option value="">— Pilih Kelas —</option>
                        @foreach($kelasMasterList as $k)
                        <option value="{{ $k->id }}" {{ (string) old('kelas_id') === (string) $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                    @error('kelas_id')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                    @if($kelasMasterList->isEmpty())
                    <p class="text-amber-600 text-xs mt-1.5">Belum ada kelas. <a href="{{ route('kelas.create') }}" class="underline font-medium">Tambah kelas</a> dulu.</p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jurusan <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <select name="jurusan_id"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-white
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow
                                   @error('jurusan_id') border-red-400 bg-red-50 @enderror">
                        <option value="">— Pilih Jurusan —</option>
                        @foreach($jurusanList as $j)
                        <option value="{{ $j->id }}" {{ (string) old('jurusan_id') === (string) $j->id ? 'selected' : '' }}>{{ $j->nama }} ({{ $j->kode }})</option>
                        @endforeach
                    </select>
                    @error('jurusan_id')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                    @if($jurusanList->isEmpty())
                    <p class="text-amber-600 text-xs mt-1.5">Belum ada jurusan. <a href="{{ route('jurusan.create') }}" class="underline font-medium">Tambah jurusan</a> dulu.</p>
                    @endif
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    No. HP Orang Tua
                    <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <input type="text" name="no_hp_ortu" value="{{ old('no_hp_ortu') }}"
                           placeholder="08xxxxxxxxxx"
                           class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow
                                  @error('no_hp_ortu') border-red-400 bg-red-50 @enderror">
                </div>
                @error('no_hp_ortu')
                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
                <p class="text-gray-400 text-xs mt-1.5">Notifikasi WhatsApp akan dikirim ke nomor ini saat absensi</p>
            </div>

            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                    Daftarkan Siswa
                </button>
                <a href="{{ route('siswa.index') }}"
                   class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors">
                    Batal
                </a>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection
