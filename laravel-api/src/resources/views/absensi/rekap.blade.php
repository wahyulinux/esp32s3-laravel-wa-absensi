@extends('layouts.app')
@section('title', 'Rekap Absensi')

@section('breadcrumb')
    <span class="text-gray-700 text-sm font-medium">Rekap Absensi</span>
@endsection

@section('content')

{{-- Page Header --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Rekap Absensi</h1>
        <p class="text-sm text-gray-500 mt-0.5">Filter dan ekspor data kehadiran</p>
    </div>
    <a href="{{ route('absensi.export', array_merge(request()->query(), ['mode' => 'rekap'])) }}"
       class="flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
        Export CSV
    </a>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('absensi.rekap') }}"
      class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5">
    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Filter Data</p>
    <div class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-36">
            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                   class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
        </div>
        <div class="flex-1 min-w-36">
            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Kelas</label>
            <select name="kelas" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $kelas)
                <option value="{{ $kelas }}" {{ request('kelas') === $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-36">
            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Bulan</label>
            <select name="bulan_val" id="bulan_val" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                <option value="">— Semua —</option>
                @foreach(range(1,12) as $m)
                <option value="{{ $m }}" {{ request('bulan_val') == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                </option>
                @endforeach
            </select>
            <input type="hidden" name="bulan" id="bulan_hidden" value="{{ request('bulan') }}">
        </div>
        <div class="flex-1 min-w-28">
            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Tahun</label>
            <select name="tahun" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                @foreach(range(now()->year, now()->year - 3) as $y)
                <option value="{{ $y }}" {{ request('tahun', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2 shrink-0">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                Terapkan
            </button>
            <a href="{{ route('absensi.rekap') }}" class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors">
                Reset
            </a>
        </div>
    </div>
</form>

{{-- Ringkasan Statistik --}}
@if($absensi->total() > 0)
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-5">
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Total Record</p>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($absensi->total()) }}</p>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Absensi Lengkap</p>
        <p class="text-2xl font-bold text-emerald-600">{{ $stats['lengkap'] }}</p>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Belum Pulang</p>
        <p class="text-2xl font-bold text-amber-500">{{ $stats['belum_pulang'] }}</p>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Rata-rata Durasi</p>
        <p class="text-2xl font-bold text-blue-600">{{ $stats['rata_durasi'] }}</p>
    </div>
</div>
@endif

{{-- Tabel --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/70">
        <p class="text-xs text-gray-500 font-medium">{{ number_format($absensi->total()) }} record ditemukan</p>
    </div>
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100">
                <th class="text-left px-5 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">#</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Tanggal</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide" colspan="2">Siswa</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Kelas</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Masuk</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Pulang</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Durasi</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Foto</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($absensi as $i => $row)
            @php $lengkap = !is_null($row->waktu_keluar); @endphp
            <tr class="hover:bg-slate-50/60 transition-colors">
                <td class="px-5 py-3 text-gray-300 text-xs tabular-nums">{{ $absensi->firstItem() + $i }}</td>
                <td class="px-4 py-3 text-xs whitespace-nowrap">
                    <span class="font-semibold text-gray-700">{{ $row->tanggal->translatedFormat('d M Y') }}</span>
                </td>
                <td class="pl-4 pr-2 py-3 w-10">
                    <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0">
                        <div class="w-full h-full flex items-center justify-center text-white text-xs font-bold"
                             style="background:{{ avatarColor($row->siswa->nama) }}">{{ avatarInitials($row->siswa->nama) }}</div>
                    </div>
                </td>
                <td class="pr-4 py-3">
                    <p class="font-semibold text-gray-800">{{ $row->siswa->nama }}</p>
                    <p class="text-gray-400 text-xs">{{ $row->siswa->nis ?? '—' }}</p>
                </td>
                <td class="px-4 py-3">
                    <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-semibold">{{ $row->siswa->kelas ?? '—' }}</span>
                </td>
                <td class="px-4 py-3 font-mono text-xs tabular-nums text-gray-700">{{ $row->waktu_masuk?->format('H:i') ?? '—' }}</td>
                <td class="px-4 py-3 font-mono text-xs tabular-nums {{ $lengkap ? 'text-gray-700' : 'text-gray-300' }}">{{ $row->waktu_keluar?->format('H:i') ?? '—' }}</td>
                <td class="px-4 py-3 font-mono text-xs tabular-nums text-gray-600">
                    @if($row->waktu_masuk && $row->waktu_keluar)
                        {{ gmdate('H:i', $row->waktu_masuk->diffInSeconds($row->waktu_keluar)) }}
                    @else <span class="text-gray-200">—</span> @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-1.5">
                        @if($row->foto_masuk)
                        <button onclick="openPhoto('{{ Storage::disk('public')->url($row->foto_masuk) }}','Masuk — {{ addslashes($row->siswa->nama) }}')"
                                class="w-7 h-7 rounded-lg overflow-hidden hover:ring-2 hover:ring-blue-400 transition-all" title="Foto Masuk">
                            <img src="{{ Storage::disk('public')->url($row->foto_masuk) }}" class="w-full h-full object-cover"
                                 onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-gray-100 text-gray-400 text-xs\'>M</div>'">
                        </button>
                        @endif
                        @if($row->foto_keluar)
                        <button onclick="openPhoto('{{ Storage::disk('public')->url($row->foto_keluar) }}','Pulang — {{ addslashes($row->siswa->nama) }}')"
                                class="w-7 h-7 rounded-lg overflow-hidden hover:ring-2 hover:ring-emerald-400 transition-all" title="Foto Pulang">
                            <img src="{{ Storage::disk('public')->url($row->foto_keluar) }}" class="w-full h-full object-cover"
                                 onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-gray-100 text-gray-400 text-xs\'>P</div>'">
                        </button>
                        @endif
                        @if(!$row->foto_masuk && !$row->foto_keluar)
                        <span class="text-gray-200 text-xs">—</span>
                        @endif
                    </div>
                </td>
                <td class="px-4 py-3">
                    @if($lengkap)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Lengkap
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                        Hadir
                    </span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="10" class="py-20 text-center">
                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-gray-300">
                        <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM2.25 10.5a8.25 8.25 0 1114.59 5.28l4.69 4.69a.75.75 0 11-1.06 1.06l-4.69-4.69A8.25 8.25 0 012.25 10.5z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <p class="font-semibold text-gray-500">Tidak ada data untuk filter ini</p>
                <p class="text-sm text-gray-400 mt-1">Coba ubah atau reset filter</p>
            </td></tr>
            @endforelse
        </tbody>
    </table>
    </div>

    @if($absensi->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $absensi->appends(request()->query())->links() }}
    </div>
    @endif
</div>

{{-- Modal Foto --}}
<div id="photoModal" class="fixed inset-0 bg-black/70 z-50 hidden items-center justify-center p-4 backdrop-blur-sm" onclick="closePhoto()">
    <div class="bg-white rounded-2xl overflow-hidden w-full max-w-sm shadow-2xl" onclick="event.stopPropagation()">
        <div class="px-5 py-3.5 border-b flex items-center justify-between">
            <p id="photoName" class="font-semibold text-gray-800 text-sm"></p>
            <button onclick="closePhoto()" class="w-7 h-7 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
            </button>
        </div>
        <img id="photoImg" src="" class="w-full object-cover" style="max-height:420px">
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelector('form').addEventListener('submit', function() {
        const b = document.getElementById('bulan_val').value;
        const y = document.querySelector('[name=tahun]').value;
        document.getElementById('bulan_hidden').value = b ? `${y}-${String(b).padStart(2,'0')}` : '';
    });
    function openPhoto(url, name) {
        document.getElementById('photoImg').src = url;
        document.getElementById('photoName').textContent = name;
        document.getElementById('photoModal').classList.replace('hidden','flex');
        document.body.style.overflow = 'hidden';
    }
    function closePhoto() {
        document.getElementById('photoModal').classList.replace('flex','hidden');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', e => { if(e.key==='Escape') closePhoto(); });
</script>
@endpush
