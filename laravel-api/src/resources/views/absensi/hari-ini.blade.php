@extends('layouts.app')
@section('title', 'Absensi Hari Ini')

@section('breadcrumb')
    <span class="text-gray-700 text-sm font-semibold">Absensi Hari Ini</span>
@endsection

@section('content')

{{-- Page Header --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-xl lg:text-2xl font-extrabold text-[#1E1E1E] tracking-tight">Presensi Hari Ini</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-0.5 flex items-center gap-1.5">
            <i class="fa-regular fa-calendar-check text-[#FF8533]"></i>
            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
        </p>
    </div>
    @auth
    <a href="{{ route('absensi.export', array_merge(request()->query(), ['mode' => 'hari-ini'])) }}"
       class="flex items-center gap-2 px-4 py-2 bg-[#2E5A27] text-white rounded-xl text-sm font-bold hover:bg-[#254A20] transition-all shadow-sm shadow-[#2E5A27]/20 active:scale-95">
        <i class="fa-solid fa-file-arrow-down text-xs"></i>
        <span>Ekspor CSV</span>
    </a>
    @endauth
</div>

{{-- Stat mini --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-[#FF8533]/15 text-[#FF8533] flex items-center justify-center shrink-0 text-base">
            <i class="fa-solid fa-clipboard-user"></i>
        </div>
        <div>
            <p class="text-2xl font-black text-[#1E1E1E]">{{ $absensi->total() }}</p>
            <p class="text-xs text-gray-500 font-medium">Total Hadir Hari Ini</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-[#2E5A27]/15 text-[#2E5A27] flex items-center justify-center shrink-0 text-base">
            <i class="fa-solid fa-person-walking-arrow-right"></i>
        </div>
        <div>
            <p class="text-2xl font-black text-[#2E5A27]">{{ $sudahPulang }}</p>
            <p class="text-xs text-gray-500 font-medium">Sudah Pulang (Lengkap)</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-amber-500/15 text-amber-600 flex items-center justify-center shrink-0 text-base">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </div>
        <div>
            <p class="text-2xl font-black text-amber-600">{{ $belumPulang }}</p>
            <p class="text-xs text-gray-500 font-medium">Belum Pulang (Di Sekolah)</p>
        </div>
    </div>
</div>

{{-- Toolbar --}}
<div class="flex flex-wrap items-center gap-2.5 mb-4">
    {{-- Filter kelas --}}
    <div class="flex items-center gap-2 flex-wrap">
        <a href="{{ route('absensi.hari-ini') }}"
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ !request('kelas') ? 'bg-[#FF8533] text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:border-[#FF8533]/40 hover:text-[#FF8533]' }}">
            Semua Kelas
        </a>
        @foreach($kelasList as $kelas)
        <a href="{{ route('absensi.hari-ini', array_merge(request()->query(), ['kelas' => $kelas])) }}"
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('kelas') === $kelas ? 'bg-[#FF8533] text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:border-[#FF8533]/40 hover:text-[#FF8533]' }}">
            {{ $kelas }}
        </a>
        @endforeach
    </div>

    {{-- Search --}}
    <div class="ml-auto">
        <form method="GET" action="{{ route('absensi.hari-ini') }}" class="relative">
            @if(request('kelas')) <input type="hidden" name="kelas" value="{{ request('kelas') }}"> @endif
            <i class="fa-solid fa-magnifying-glass text-gray-400 absolute left-3.5 top-3 text-xs"></i>
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama siswa..."
                   class="pl-9 pr-4 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#FF8533] bg-white w-48 sm:w-60 transition-all font-medium">
        </form>
    </div>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 bg-[#F4F6F8]/70 flex items-center justify-between">
        <p class="text-xs text-gray-600 font-bold flex items-center gap-1.5">
            <i class="fa-solid fa-user-check text-[#FF8533]"></i>
            <span>{{ number_format($absensi->total()) }} siswa hadir hari ini</span>
        </p>
    </div>
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50/50">
                <th class="text-left px-5 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider w-10">#</th>
                <th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider" colspan="2">Siswa</th>
                <th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">Kelas</th>
                <th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">Ruangan</th>
                <th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">Jam Masuk</th>
                <th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">Jam Pulang</th>
                <th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">Durasi</th>
                <th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($absensi as $i => $row)
            @php $lengkap = !is_null($row->waktu_keluar); @endphp
            <tr class="hover:bg-[#F4F6F8]/60 transition-colors">
                <td class="px-5 py-3.5 text-gray-400 text-xs tabular-nums">{{ $absensi->firstItem() + $i }}</td>

                <td class="pl-4 pr-2 py-3.5 w-12">
                    <button onclick="openPhoto('{{ $row->foto_masuk ? Storage::disk('public')->url($row->foto_masuk) : '' }}', '{{ addslashes($row->siswa->nama) }}')"
                            class="w-9 h-9 rounded-full overflow-hidden block {{ $row->foto_masuk ? 'hover:ring-2 hover:ring-[#FF8533] cursor-pointer' : 'cursor-default' }} transition-all">
                        @if($row->foto_masuk)
                        <img src="{{ Storage::disk('public')->url($row->foto_masuk) }}" class="w-full h-full object-cover"
                             onerror="this.outerHTML='<div class=\'w-full h-full flex items-center justify-center text-white text-xs font-bold\' style=\'background:{{ avatarColor($row->siswa->nama) }}\'>{{ avatarInitials($row->siswa->nama) }}</div>'">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-white text-xs font-bold"
                             style="background:{{ avatarColor($row->siswa->nama) }}">{{ avatarInitials($row->siswa->nama) }}</div>
                        @endif
                    </button>
                </td>

                <td class="pr-4 py-3.5">
                    <p class="font-bold text-[#1E1E1E] text-sm">{{ $row->siswa->nama }}</p>
                    <p class="text-gray-400 text-xs font-mono">{{ $row->siswa->nis ?? 'NIS —' }}</p>
                </td>
                <td class="px-4 py-3.5">
                    <span class="inline-block px-2.5 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-bold">{{ $row->siswa->kelas ?? '—' }}</span>
                </td>
                <td class="px-4 py-3.5">
                    @if($row->device_id)
                    @php $dNama = $deviceNames[$row->device_id] ?? null; @endphp
                    <span title="{{ $dNama ?? $row->device_id }}"
                          class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-[#5C2828]/10 text-[#5C2828] border border-[#5C2828]/20 rounded-lg text-xs font-bold font-mono">
                        <i class="fa-solid fa-satellite-dish text-[10px]"></i>
                        {{ $row->device_id }}
                    </span>
                    @else
                    <span class="text-gray-300 text-xs">—</span>
                    @endif
                </td>
                <td class="px-4 py-3.5 font-mono text-gray-700 text-xs font-semibold tabular-nums">{{ $row->waktu_masuk?->format('H:i:s') ?? '—' }}</td>
                <td class="px-4 py-3.5 font-mono text-xs tabular-nums {{ $lengkap ? 'text-gray-700 font-semibold' : 'text-gray-300' }}">
                    @if($row->foto_keluar)
                    <button onclick="openPhoto('{{ Storage::disk('public')->url($row->foto_keluar) }}', 'Pulang — {{ addslashes($row->siswa->nama) }}')"
                            class="hover:text-[#FF8533] transition-colors underline decoration-dotted">{{ $row->waktu_keluar->format('H:i:s') }}</button>
                    @else
                    {{ $row->waktu_keluar?->format('H:i:s') ?? '—' }}
                    @endif
                </td>
                <td class="px-4 py-3.5 font-mono text-xs text-gray-500 tabular-nums">
                    @if($row->waktu_masuk && $row->waktu_keluar)
                        {{ gmdate('H:i', $row->waktu_masuk->diffInSeconds($row->waktu_keluar)) }}
                    @else <span class="text-gray-200">—</span> @endif
                </td>
                <td class="px-4 py-3.5">
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
            <tr>
                <td colspan="9" class="py-20 text-center">
                    <div class="w-16 h-16 rounded-full bg-[#F4F6F8] flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <i class="fa-solid fa-id-card-clip text-3xl"></i>
                    </div>
                    <p class="font-bold text-gray-600">Belum ada absensi hari ini</p>
                    <p class="text-xs text-gray-400 mt-1">Siswa belum melakukan scan kartu RFID pada ESP32</p>
                </td>
            </tr>
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
    setTimeout(() => location.reload(), 30000);

    function openPhoto(url, name) {
        if (!url) return;
        document.getElementById('photoImg').src = url;
        document.getElementById('photoName').textContent = name;
        document.getElementById('photoModal').classList.replace('hidden','flex');
        document.body.style.overflow = 'hidden';
    }
    function closePhoto() {
        document.getElementById('photoModal').classList.replace('flex','hidden');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', e => { if(e.key === 'Escape') closePhoto(); });
</script>
@endpush
