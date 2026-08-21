@extends('layouts.app')
@section('title', 'Data Siswa')

@section('breadcrumb')
    <span class="text-gray-700 text-sm font-medium">Data Siswa</span>
@endsection

@section('content')

{{-- Page Header --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Data Siswa</h1>
        <p class="text-sm text-gray-500 mt-0.5">Manajemen siswa dan kartu RFID</p>
    </div>
    <a href="{{ route('siswa.create') }}"
       class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Daftarkan Siswa
    </a>
</div>

{{-- Ringkasan per kelas --}}
@if($kelasList->count() > 0)
<div class="flex flex-wrap gap-2 mb-5">
    @foreach($kelasList as $kelas)
    @php $jumlah = \App\Models\Siswa::where('kelas', $kelas)->where('aktif', true)->count(); @endphp
    <a href="{{ route('siswa.index', ['kelas' => $kelas]) }}"
       class="flex items-center gap-2 px-3.5 py-2 bg-white border border-gray-200 rounded-xl text-xs hover:border-blue-300 hover:bg-blue-50 transition-colors {{ request('kelas') === $kelas ? 'border-blue-400 bg-blue-50' : '' }}">
        <span class="font-bold text-gray-700">{{ $kelas }}</span>
        <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center">{{ $jumlah }}</span>
    </a>
    @endforeach
</div>
@endif

{{-- Toolbar --}}
<div class="flex flex-wrap items-center gap-2.5 mb-4">
    <form method="GET" action="{{ route('siswa.index') }}" class="flex gap-2 flex-wrap flex-1 min-w-0">
        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-gray-400 absolute left-3 top-2.5">
                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"/>
            </svg>
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama / NIS / RFID..."
                   class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-56">
        </div>
        <select name="kelas" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
            <option value="">Semua Kelas</option>
            @foreach($kelasList as $kelas)
            <option value="{{ $kelas }}" {{ request('kelas') === $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors">Cari</button>
        @if(request('cari') || request('kelas'))
        <a href="{{ route('siswa.index') }}" class="px-4 py-2 border border-gray-200 text-gray-500 rounded-xl text-sm hover:bg-gray-50 transition-colors">Reset</a>
        @endif
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/70 flex items-center justify-between">
        <p class="text-xs text-gray-500 font-medium">{{ $siswa->total() }} siswa ditemukan</p>
    </div>
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100">
                <th class="text-left px-5 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide w-10">#</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide" colspan="2">Siswa</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">NIS</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Kelas</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">UID RFID</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Status</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($siswa as $i => $s)
            <tr class="hover:bg-slate-50/60 transition-colors {{ !$s->aktif ? 'opacity-50' : '' }}">
                <td class="px-5 py-3.5 text-gray-300 text-xs tabular-nums">{{ $siswa->firstItem() + $i }}</td>

                <td class="pl-4 pr-2 py-3.5 w-12">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-sm"
                         style="background: {{ avatarColor($s->nama) }}">
                        {{ avatarInitials($s->nama) }}
                    </div>
                </td>

                <td class="pr-4 py-3.5">
                    <p class="font-semibold text-gray-800">{{ $s->nama }}</p>
                </td>
                <td class="px-4 py-3.5 font-mono text-gray-500 text-xs tabular-nums">{{ $s->nis ?? '—' }}</td>
                <td class="px-4 py-3.5">
                    @if($s->kelas)
                    <span class="inline-block px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-semibold border border-blue-100">{{ $s->kelas }}</span>
                    @else <span class="text-gray-300 text-xs">—</span> @endif
                </td>
                <td class="px-4 py-3.5">
                    <code class="text-xs bg-slate-100 text-slate-600 px-2.5 py-1.5 rounded-lg tracking-widest font-mono border border-slate-200">{{ $s->rfid_uid }}</code>
                </td>
                <td class="px-4 py-3.5">
                    @if($s->aktif)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Aktif
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-400 border border-gray-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Nonaktif
                    </span>
                    @endif
                </td>
                <td class="px-4 py-3.5">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('siswa.edit', $s) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-gray-100 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                                <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z"/>
                                <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z"/>
                            </svg>
                            Edit
                        </a>
                        @if($s->aktif)
                        <button onclick="confirmDeactivate({{ $s->id }}, '{{ addslashes($s->nama) }}')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors border border-red-100">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
                            </svg>
                            Nonaktifkan
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="py-20 text-center">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-gray-300">
                            <path d="M4.5 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM14.25 8.625a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0zM1.5 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM17.25 19.128l-.001.144a2.25 2.25 0 01-.233.96 10.088 10.088 0 005.06-1.01.75.75 0 00.42-.643 4.875 4.875 0 00-6.957-4.611 8.586 8.586 0 011.71 5.157v.003z"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-500">Belum ada siswa terdaftar</p>
                    <a href="{{ route('siswa.create') }}" class="inline-flex items-center gap-1.5 text-blue-600 text-sm hover:underline mt-2 font-medium">
                        Daftarkan siswa pertama →
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    @if($siswa->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $siswa->appends(request()->query())->links() }}
    </div>
    @endif
</div>

{{-- Modal konfirmasi nonaktifkan --}}
<div id="deactivateModal" class="fixed inset-0 bg-black/60 z-50 hidden items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl p-6">
        <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-red-600">
                <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 1.995-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.505-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"/>
            </svg>
        </div>
        <h3 class="text-center font-bold text-gray-900 text-base mb-1">Nonaktifkan Siswa?</h3>
        <p class="text-center text-gray-500 text-sm mb-6">
            <span id="deactivateName" class="font-semibold text-gray-700"></span> tidak akan bisa absen setelah dinonaktifkan.
        </p>
        <div class="flex gap-3">
            <button onclick="closeDeactivate()" class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <form id="deactivateForm" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition-colors">
                    Nonaktifkan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDeactivate(id, nama) {
        document.getElementById('deactivateName').textContent = nama;
        document.getElementById('deactivateForm').action = `/siswa/${id}`;
        document.getElementById('deactivateModal').classList.replace('hidden','flex');
        document.body.style.overflow = 'hidden';
    }
    function closeDeactivate() {
        document.getElementById('deactivateModal').classList.replace('flex','hidden');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', e => { if(e.key==='Escape') closeDeactivate(); });
</script>
@endpush
