@extends('layouts.app')
@section('title', 'Edit Siswa')

@section('breadcrumb')
    <a href="{{ route('siswa.index') }}" class="text-gray-400 hover:text-blue-600 transition-colors text-sm">Data Siswa</a>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-gray-300"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg>
    <span class="text-gray-700 text-sm font-medium">Edit Siswa</span>
@endsection

@section('content')

<div class="max-w-xl">

    {{-- Page Header --}}
    <div class="mb-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white text-lg font-bold shadow-md"
             style="background: {{ avatarColor($siswa->nama) }}">
            {{ avatarInitials($siswa->nama) }}
        </div>
        <div>
            <h1 class="text-xl font-bold text-gray-900">{{ $siswa->nama }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $siswa->kelas ?? 'Kelas belum diisi' }} &middot; {{ $siswa->rfid_uid }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/60">
            <p class="text-sm font-semibold text-gray-700">Edit Informasi Siswa</p>
        </div>
        <div class="p-6">
        <form method="POST" action="{{ route('siswa.update', $siswa) }}" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">UID Kartu RFID</label>
                <input type="text" name="rfid_uid" value="{{ old('rfid_uid', $siswa->rfid_uid) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono tracking-widest uppercase
                              focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow
                              @error('rfid_uid') border-red-400 bg-red-50 @enderror">
                @error('rfid_uid') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">NIS</label>
                <input type="text" name="nis" value="{{ old('nis', $siswa->nis) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow
                              @error('nis') border-red-400 bg-red-50 @enderror">
                @error('nis') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama', $siswa->nama) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow
                              @error('nama') border-red-400 bg-red-50 @enderror">
                @error('nama') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kelas</label>
                <input type="text" name="kelas" value="{{ old('kelas', $siswa->kelas) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow
                              @error('kelas') border-red-400 bg-red-50 @enderror">
                @error('kelas') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
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
                    <input type="text" name="no_hp_ortu" value="{{ old('no_hp_ortu', $siswa->no_hp_ortu) }}"
                           placeholder="08xxxxxxxxxx"
                           class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow
                                  @error('no_hp_ortu') border-red-400 bg-red-50 @enderror">
                </div>
                @error('no_hp_ortu') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                <p class="text-gray-400 text-xs mt-1.5">Notifikasi WhatsApp dikirim ke nomor ini saat absensi masuk/pulang</p>
            </div>

            <div class="flex items-center gap-3 py-3 px-4 bg-gray-50 rounded-xl border border-gray-200">
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" name="aktif" value="1" {{ old('aktif', $siswa->aktif) ? 'checked' : '' }}
                               class="sr-only peer" id="aktif-toggle">
                        <div class="w-10 h-5 bg-gray-300 peer-checked:bg-blue-500 rounded-full transition-colors peer-focus:ring-2 peer-focus:ring-blue-300"></div>
                        <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Siswa aktif</span>
                </label>
            </div>

            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                    Simpan Perubahan
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
