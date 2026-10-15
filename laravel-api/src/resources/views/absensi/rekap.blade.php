@extends('layouts.app')
@section('title', 'Rekap Absensi')

@section('breadcrumb')
    <span class="text-gray-700 text-sm font-semibold">Rekap Absensi</span>
@endsection

@section('content')

{{-- Page Header --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-xl lg:text-2xl font-extrabold text-[#1E1E1E] tracking-tight">Rekap Absensi Siswa</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-0.5 flex items-center gap-1.5">
            <i class="fa-regular fa-calendar-days text-[#FF8533]"></i>
            <span>Filter dan ekspor data kehadiran siswa</span>
        </p>
    </div>
    @auth
    <a href="{{ route('absensi.export', array_merge(request()->query(), ['mode' => 'rekap'])) }}"
       class="flex items-center gap-2 px-4 py-2 bg-[#2E5A27] text-white rounded-xl text-sm font-bold hover:bg-[#254A20] transition-all shadow-sm shadow-[#2E5A27]/20 active:scale-95">
        <i class="fa-solid fa-file-arrow-down text-xs"></i>
        <span>Ekspor CSV</span>
    </a>
    @endauth
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('absensi.rekap') }}"
      class="bg-white rounded-2xl shadow-xs border border-gray-100 p-5 mb-5">
    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-1.5">
        <i class="fa-solid fa-filter text-[#FF8533]"></i>
        <span>Filter Data Kehadiran</span>
    </p>
    <div class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-36">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                   class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#FF8533] bg-white font-medium">
        </div>
        <div class="flex-1 min-w-36">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kelas</label>
            <select name="kelas" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#FF8533] bg-white font-medium">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $kelas)
                <option value="{{ $kelas }}" {{ request('kelas') === $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-36">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Bulan</label>
            <select name="bulan_val" id="bulan_val" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#FF8533] bg-white font-medium">
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
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tahun</label>
            <select name="tahun" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#FF8533] bg-white font-medium">
                @foreach(range(now()->year, now()->year - 3) as $y)
                <option value="{{ $y }}" {{ request('tahun', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2 shrink-0">
            <button type="submit" class="px-5 py-2 bg-[#FF8533] text-white rounded-xl text-xs font-bold hover:bg-[#EA6D1A] transition-all shadow-sm shadow-[#FF8533]/25 active:scale-95 flex items-center gap-1.5">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                <span>Terapkan</span>
            </button>
            <a href="{{ route('absensi.rekap') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-xs font-bold hover:bg-gray-200 transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-rotate-left text-xs"></i>
                <span>Reset</span>
            </a>
        </div>
    </div>
</form>

{{-- Ringkasan Statistik --}}
@if($absensi->total() > 0)
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-5">
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs">
        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Total Record</p>
        <p class="text-2xl font-black text-[#1E1E1E]">{{ number_format($absensi->total()) }}</p>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs">
        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Absensi Lengkap</p>
        <p class="text-2xl font-black text-[#2E5A27]">{{ $stats['lengkap'] }}</p>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs">
        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Belum Pulang</p>
        <p class="text-2xl font-black text-amber-600">{{ $stats['belum_pulang'] }}</p>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs">
        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Rata-rata Durasi</p>
        <p class="text-2xl font-black text-[#FF8533]">{{ $stats['rata_durasi'] }}</p>
    </div>
</div>
@endif

{{-- Tabel --}}
<div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 bg-[#F4F6F8]/70">
        <p class="text-xs text-gray-600 font-bold flex items-center gap-1.5">
            <i class="fa-solid fa-list-check text-[#FF8533]"></i>
            <span>{{ number_format($absensi->total()) }} data kehadiran ditemukan</span>
        </p>
    </div>
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50/50">
                <th class="text-left px-5 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">#</th>
                <th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">Tanggal</th>
                <th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider" colspan="2">Siswa</th>
                <th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">Kelas</th>
                <th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">Masuk</th>
                <th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">Pulang</th>
                <th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">Durasi</th>
                <th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">Foto</th>
                <th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($absensi as $i => $row)
            @php $lengkap = !is_null($row->waktu_keluar); @endphp
            <tr class="hover:bg-[#F4F6F8]/60 transition-colors">
                <td class="px-5 py-3 text-gray-400 text-xs tabular-nums">{{ $absensi->firstItem() + $i }}</td>
                <td class="px-4 py-3 text-xs whitespace-nowrap">
                    <span class="font-bold text-[#1E1E1E]">{{ $row->tanggal->translatedFormat('d M Y') }}</span>
                </td>
                <td class="pl-4 pr-2 py-3 w-10">
                    <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0">
                        <div class="w-full h-full flex items-center justify-center text-white text-xs font-bold"
                             style="background:{{ avatarColor($row->siswa->nama) }}">{{ avatarInitials($row->siswa->nama) }}</div>
                    </div>
                </td>
                <td class="pr-4 py-3">
                    <p class="font-bold text-[#1E1E1E] text-sm">{{ $row->siswa->nama }}</p>
                    <p class="text-gray-400 text-xs font-mono">{{ $row->siswa->nis ?? '—' }}</p>
                </td>
                <td class="px-4 py-3">
                    <span class="inline-block px-2.5 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-bold">{{ $row->siswa->kelas ?? '—' }}</span>
                </td>
                <td class="px-4 py-3 font-mono text-xs tabular-nums text-gray-700 font-semibold">{{ $row->waktu_masuk?->format('H:i') ?? '—' }}</td>
                <td class="px-4 py-3 font-mono text-xs tabular-nums {{ $lengkap ? 'text-gray-700 font-semibold' : 'text-gray-300' }}">{{ $row->waktu_keluar?->format('H:i') ?? '—' }}</td>
                <td class="px-4 py-3 font-mono text-xs tabular-nums text-gray-600">
                    @if($row->waktu_masuk && $row->waktu_keluar)
                        {{ gmdate('H:i', $row->waktu_masuk->diffInSeconds($row->waktu_keluar)) }}
                    @else <span class="text-gray-200">—</span> @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-1.5">
                        @if($row->foto_masuk)
                        <button onclick="openPhoto('{{ Storage::disk('public')->url($row->foto_masuk) }}','Masuk — {{ addslashes($row->siswa->nama) }}')"
                                class="w-7 h-7 rounded-lg overflow-hidden hover:ring-2 hover:ring-[#FF8533] transition-all" title="Foto Masuk">
                            <img src="{{ Storage::disk('public')->url($row->foto_masuk) }}" class="w-full h-full object-cover"
                                 onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-gray-100 text-gray-400 text-xs\'>M</div>'">
                        </button>
                        @endif
                        @if($row->foto_keluar)
                        <button onclick="openPhoto('{{ Storage::disk('public')->url($row->foto_keluar) }}','Pulang — {{ addslashes($row->siswa->nama) }}')"
                                class="w-7 h-7 rounded-lg overflow-hidden hover:ring-2 hover:ring-[#2E5A27] transition-all" title="Foto Pulang">
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
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-[#2E5A27]/10 text-[#2E5A27] border border-[#2E5A27]/25">
                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                        <span>Lengkap</span>
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-[#FF8533]/10 text-[#FF8533] border border-[#FF8533]/25">
                        <i class="fa-solid fa-arrow-right-to-bracket text-[10px]"></i>
                        <span>Hadir</span>
                    </span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="10" class="py-20 text-center">
                <div class="w-16 h-16 rounded-full bg-[#F4F6F8] flex items-center justify-center mx-auto mb-4 text-gray-400">
                    <i class="fa-solid fa-folder-open text-3xl"></i>
                </div>
                <p class="font-bold text-gray-600">Tidak ada data untuk filter ini</p>
                <p class="text-xs text-gray-400 mt-1">Coba sesuaikan tanggal, kelas, atau reset filter</p>
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
<div id="photoModal" class="fixed inset-0 bg-black/70 z-50 hidden items-center justify-center p-4 backdrop-blur-xs" onclick="closePhoto()">
    <div class="bg-white rounded-2xl overflow-hidden w-full max-w-sm shadow-2xl" onclick="event.stopPropagation()">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
            <p id="photoName" class="font-bold text-[#1E1E1E] text-sm"></p>
            <button onclick="closePhoto()" class="w-7 h-7 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
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
