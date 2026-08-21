@extends('layouts.app')
@section('title', 'Absensi Hari Ini')

@section('breadcrumb')
    <span class="text-gray-700 text-sm font-medium">Absensi Hari Ini</span>
@endsection

@section('content')

{{-- Page Header --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Absensi Hari Ini</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>
    <a href="{{ route('absensi.export', array_merge(request()->query(), ['mode' => 'hari-ini'])) }}"
       class="flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
        Export CSV
    </a>
</div>

{{-- Stat mini --}}
<div class="grid grid-cols-3 gap-4 mb-5">
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-blue-600">
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900">{{ $absensi->total() }}</p>
            <p class="text-xs text-gray-400 font-medium">Total Hadir</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-emerald-600">
                <path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z"/>
                <path d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.432z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-emerald-600">{{ $sudahPulang }}</p>
            <p class="text-xs text-gray-400 font-medium">Sudah Pulang</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-amber-600">
                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-amber-500">{{ $belumPulang }}</p>
            <p class="text-xs text-gray-400 font-medium">Belum Pulang</p>
        </div>
    </div>
</div>

{{-- Toolbar --}}
<div class="flex flex-wrap items-center gap-2.5 mb-4">
    {{-- Filter kelas --}}
    <div class="flex items-center gap-2 flex-wrap">
        <a href="{{ route('absensi.hari-ini') }}"
           class="px-3 py-1.5 rounded-lg text-sm font-semibold transition-colors {{ !request('kelas') ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:border-blue-300 hover:text-blue-600' }}">
            Semua
        </a>
        @foreach($kelasList as $kelas)
        <a href="{{ route('absensi.hari-ini', array_merge(request()->query(), ['kelas' => $kelas])) }}"
           class="px-3 py-1.5 rounded-lg text-sm font-semibold transition-colors {{ request('kelas') === $kelas ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:border-blue-300 hover:text-blue-600' }}">
            {{ $kelas }}
        </a>
        @endforeach
    </div>

    {{-- Search --}}
    <div class="ml-auto">
        <form method="GET" action="{{ route('absensi.hari-ini') }}" class="relative">
            @if(request('kelas')) <input type="hidden" name="kelas" value="{{ request('kelas') }}"> @endif
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-gray-400 absolute left-3 top-2.5">
                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"/>
            </svg>
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama siswa..."
                   class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-48 focus:w-60 transition-all">
        </form>
    </div>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/70 flex items-center justify-between">
        <p class="text-xs text-gray-500 font-medium">{{ number_format($absensi->total()) }} siswa hadir</p>
    </div>
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100">
                <th class="text-left px-5 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide w-10">#</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide" colspan="2">Siswa</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Kelas</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Ruangan</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Jam Masuk</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Jam Pulang</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Durasi</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($absensi as $i => $row)
            @php $lengkap = !is_null($row->waktu_keluar); @endphp
            <tr class="hover:bg-slate-50/60 transition-colors">
                <td class="px-5 py-3.5 text-gray-300 text-xs tabular-nums">{{ $absensi->firstItem() + $i }}</td>

                <td class="pl-4 pr-2 py-3.5 w-12">
                    <button onclick="openPhoto('{{ $row->foto_masuk ? Storage::disk('public')->url($row->foto_masuk) : '' }}', '{{ addslashes($row->siswa->nama) }}')"
                            class="w-9 h-9 rounded-full overflow-hidden block {{ $row->foto_masuk ? 'hover:ring-2 hover:ring-blue-400 cursor-pointer' : 'cursor-default' }} transition-all">
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
                    <p class="font-semibold text-gray-800">{{ $row->siswa->nama }}</p>
                    <p class="text-gray-400 text-xs">{{ $row->siswa->nis ?? 'NIS —' }}</p>
                </td>
                <td class="px-4 py-3.5">
                    <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-semibold">{{ $row->siswa->kelas ?? '—' }}</span>
                </td>
                <td class="px-4 py-3.5">
                    @if($row->device_id)
                    @php $dNama = $deviceNames[$row->device_id] ?? null; @endphp
                    <span title="{{ $dNama ?? $row->device_id }}"
                          class="inline-flex items-center gap-1 px-2 py-1 bg-violet-50 text-violet-700 border border-violet-200 rounded-lg text-xs font-bold font-mono">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 shrink-0">
                            <path fill-rule="evenodd" d="M1.371 8.143c5.858-5.857 15.356-5.857 21.213 0a.75.75 0 010 1.061l-.53.53a.75.75 0 01-1.06 0c-4.98-4.979-13.053-4.979-18.032 0a.75.75 0 01-1.061 0l-.53-.53a.75.75 0 010-1.061zm3.182 3.182c4.1-4.1 10.749-4.1 14.85 0a.75.75 0 010 1.061l-.53.53a.75.75 0 01-1.06 0 8.25 8.25 0 00-11.67 0 .75.75 0 01-1.06 0l-.53-.53a.75.75 0 010-1.061zm3.204 3.204a6 6 0 018.486 0 .75.75 0 010 1.061l-.53.53a.75.75 0 01-1.061 0 3.75 3.75 0 00-5.303 0 .75.75 0 01-1.061 0l-.53-.53a.75.75 0 010-1.061zm3.182 3.182a1.5 1.5 0 012.122 0 .75.75 0 010 1.061l-.53.53a.75.75 0 01-1.061 0l-.53-.53a.75.75 0 010-1.061z" clip-rule="evenodd"/>
                        </svg>
                        {{ $row->device_id }}
                    </span>
                    @else
                    <span class="text-gray-300 text-xs">—</span>
                    @endif
                </td>
                <td class="px-4 py-3.5 font-mono text-gray-700 text-xs tabular-nums">{{ $row->waktu_masuk?->format('H:i:s') ?? '—' }}</td>
                <td class="px-4 py-3.5 font-mono text-xs tabular-nums {{ $lengkap ? 'text-gray-700' : 'text-gray-300' }}">
                    @if($row->foto_keluar)
                    <button onclick="openPhoto('{{ Storage::disk('public')->url($row->foto_keluar) }}', 'Pulang — {{ addslashes($row->siswa->nama) }}')"
                            class="hover:text-blue-600 transition-colors">{{ $row->waktu_keluar->format('H:i:s') }}</button>
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
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Lengkap
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                        Hadir
                    </span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="py-20 text-center">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-gray-300">
                            <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 017.5 3v1.5h9V3A.75.75 0 0118 3v1.5h.75a3 3 0 013 3v11.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V7.5a3 3 0 013-3H6V3a.75.75 0 01.75-.75zm13.5 9a1.5 1.5 0 00-1.5-1.5H5.25a1.5 1.5 0 00-1.5 1.5v7.5a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5v-7.5z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-500">Belum ada absensi hari ini</p>
                    <p class="text-sm text-gray-400 mt-1">Siswa belum melakukan scan kartu RFID</p>
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
