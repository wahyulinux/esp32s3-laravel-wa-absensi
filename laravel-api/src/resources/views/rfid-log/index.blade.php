@extends('layouts.app')
@section('title', 'Log UID Kartu')

@section('breadcrumb')
    <span class="text-gray-700 text-sm font-medium">Log UID Kartu</span>
@endsection

@section('content')

<div class="flex flex-wrap items-start justify-between gap-3 mb-5">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Log UID Kartu</h1>
        <p class="text-sm text-gray-500 mt-0.5">Setiap scan RFID yang masuk ke server · disimpan 7 hari · refresh otomatis</p>
    </div>
    <div class="flex items-center gap-2">
        <span class="flex items-center gap-1.5 text-xs text-gray-400" id="log-last-update">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
            Live
        </span>
        {{-- Filter status --}}
        <div class="flex items-center gap-1.5" id="filter-bar">
            <button onclick="setFilter('')"
                    class="filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors bg-slate-800 text-white" data-filter="">
                Semua
            </button>
            <button onclick="setFilter('masuk')"
                    class="filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors bg-white text-gray-500 border border-gray-200 hover:border-emerald-300" data-filter="masuk">
                Masuk
            </button>
            <button onclick="setFilter('keluar')"
                    class="filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors bg-white text-gray-500 border border-gray-200 hover:border-blue-300" data-filter="keluar">
                Keluar
            </button>
            <button onclick="setFilter('tidak_dikenal')"
                    class="filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors bg-white text-gray-500 border border-gray-200 hover:border-red-300" data-filter="tidak_dikenal">
                Tidak Dikenal
            </button>
        </div>
    </div>
</div>

{{-- Summary chips --}}
<div class="flex flex-wrap gap-2 mb-4" id="summary-chips">
    <span class="text-xs px-3 py-1.5 rounded-full bg-white border border-gray-200 text-gray-500 font-medium">
        Total: <span id="cnt-all" class="font-bold text-gray-800">{{ count($entries) }}</span>
    </span>
    <span class="text-xs px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 font-medium">
        Masuk: <span id="cnt-masuk" class="font-bold">{{ collect($entries)->where('status','masuk')->count() }}</span>
    </span>
    <span class="text-xs px-3 py-1.5 rounded-full bg-blue-50 border border-blue-200 text-blue-700 font-medium">
        Keluar: <span id="cnt-keluar" class="font-bold">{{ collect($entries)->where('status','keluar')->count() }}</span>
    </span>
    <span class="text-xs px-3 py-1.5 rounded-full bg-red-50 border border-red-200 text-red-600 font-medium">
        Tidak Dikenal: <span id="cnt-unknown" class="font-bold">{{ collect($entries)->where('status','tidak_dikenal')->count() }}</span>
    </span>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50/70">
                <th class="text-left px-5 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide w-36">Waktu</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">UID Kartu</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Siswa</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Kelas</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Ruangan</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">IP</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Status</th>
            </tr>
        </thead>
        <tbody id="log-body" class="divide-y divide-gray-50">
            @forelse($entries as $e)
            @php
                $st = $e['status'];
                $rowClass = match($st) {
                    'tidak_dikenal' => 'bg-red-50/40',
                    'sudah_lengkap' => 'bg-gray-50/60',
                    default         => '',
                };
                $badgeCls = match($st) {
                    'masuk'         => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'keluar'        => 'bg-blue-50 text-blue-700 border-blue-200',
                    'tidak_dikenal' => 'bg-red-50 text-red-600 border-red-200',
                    'sudah_lengkap' => 'bg-gray-100 text-gray-500 border-gray-200',
                    default         => 'bg-gray-100 text-gray-500 border-gray-200',
                };
                $badgeLabel = match($st) {
                    'masuk'         => 'Masuk',
                    'keluar'        => 'Keluar',
                    'tidak_dikenal' => 'Tidak Dikenal',
                    'sudah_lengkap' => 'Sudah Lengkap',
                    default         => $st,
                };
            @endphp
            <tr class="hover:bg-slate-50/60 transition-colors {{ $rowClass }}">
                <td class="px-5 py-3 font-mono text-xs text-gray-500 tabular-nums whitespace-nowrap">
                    {{ \Carbon\Carbon::createFromTimestamp($e['time'])->format('d/m H:i:s') }}
                </td>
                <td class="px-4 py-3">
                    <span class="font-mono text-xs font-bold tracking-widest px-2.5 py-1 rounded-lg border
                        {{ $e['status'] === 'tidak_dikenal' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-slate-100 text-slate-700 border-slate-200' }}">
                        {{ $e['rfid_uid'] }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm">
                    @if($e['siswa_nama'])
                        <span class="font-semibold text-gray-800">{{ $e['siswa_nama'] }}</span>
                    @else
                        <span class="text-gray-300 italic text-xs">—</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    @if($e['siswa_kelas'])
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-semibold">{{ $e['siswa_kelas'] }}</span>
                    @else
                        <span class="text-gray-300 text-xs">—</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    @if($e['device_id'])
                        <span class="font-mono text-xs font-bold px-2 py-0.5 bg-violet-50 text-violet-700 border border-violet-200 rounded-lg">{{ $e['device_id'] }}</span>
                    @else
                        <span class="text-gray-300 text-xs">—</span>
                    @endif
                </td>
                <td class="px-4 py-3 font-mono text-xs text-gray-400">{{ $e['ip'] ?? '—' }}</td>
                <td class="px-4 py-3">
                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badgeCls }}">
                        {{ $badgeLabel }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-20 text-center">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-gray-300">
                            <path fill-rule="evenodd" d="M2.25 6a3 3 0 013-3h13.5a3 3 0 013 3v12a3 3 0 01-3 3H5.25a3 3 0 01-3-3V6zm3.97.97a.75.75 0 011.06 0l2.25 2.25a.75.75 0 010 1.06l-2.25 2.25a.75.75 0 01-1.06-1.06l1.72-1.72-1.72-1.72a.75.75 0 010-1.06zm4.28 4.28a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-500">Belum ada log</p>
                    <p class="text-sm text-gray-400 mt-1">Log akan muncul setiap kali ESP32 mengirim scan RFID</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
let activeFilter = '';
let allEntries   = @json($entries);

const STATUS_CFG = {
    masuk:         { badge: 'bg-emerald-50 text-emerald-700 border-emerald-200', label: 'Masuk' },
    keluar:        { badge: 'bg-blue-50 text-blue-700 border-blue-200',          label: 'Keluar' },
    tidak_dikenal: { badge: 'bg-red-50 text-red-600 border-red-200',             label: 'Tidak Dikenal' },
    sudah_lengkap: { badge: 'bg-gray-100 text-gray-500 border-gray-200',         label: 'Sudah Lengkap' },
};

function setFilter(f) {
    activeFilter = f;
    document.querySelectorAll('.filter-btn').forEach(btn => {
        const active = btn.dataset.filter === f;
        btn.className = 'filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors ' +
            (active ? 'bg-slate-800 text-white' : 'bg-white text-gray-500 border border-gray-200 hover:border-blue-300');
    });
    renderTable(allEntries);
}

function fmtTime(ts) {
    const d = new Date(ts * 1000);
    const pad = n => String(n).padStart(2,'0');
    return `${pad(d.getDate())}/${pad(d.getMonth()+1)} ${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
}

function renderTable(entries) {
    const filtered = activeFilter ? entries.filter(e => e.status === activeFilter) : entries;
    const tbody    = document.getElementById('log-body');

    // Update counters
    document.getElementById('cnt-all').textContent     = entries.length;
    document.getElementById('cnt-masuk').textContent   = entries.filter(e => e.status === 'masuk').length;
    document.getElementById('cnt-keluar').textContent  = entries.filter(e => e.status === 'keluar').length;
    document.getElementById('cnt-unknown').textContent = entries.filter(e => e.status === 'tidak_dikenal').length;

    if (!filtered.length) {
        tbody.innerHTML = `<tr><td colspan="7" class="py-16 text-center text-gray-400 text-sm">Tidak ada log${activeFilter ? ' untuk filter ini' : ''}</td></tr>`;
        return;
    }

    tbody.innerHTML = filtered.slice(0, 500).map(e => {
        const cfg      = STATUS_CFG[e.status] || STATUS_CFG.sudah_lengkap;
        const rowCls   = e.status === 'tidak_dikenal' ? 'bg-red-50/40' : e.status === 'sudah_lengkap' ? 'bg-gray-50/60' : '';
        const uidCls   = e.status === 'tidak_dikenal'
            ? 'bg-red-50 text-red-700 border-red-200'
            : 'bg-slate-100 text-slate-700 border-slate-200';
        return `<tr class="hover:bg-slate-50/60 transition-colors ${rowCls}">
            <td class="px-5 py-3 font-mono text-xs text-gray-500 tabular-nums whitespace-nowrap">${fmtTime(e.time)}</td>
            <td class="px-4 py-3">
                <span class="font-mono text-xs font-bold tracking-widest px-2.5 py-1 rounded-lg border ${uidCls}">${e.rfid_uid}</span>
            </td>
            <td class="px-4 py-3 text-sm">
                ${e.siswa_nama ? `<span class="font-semibold text-gray-800">${e.siswa_nama}</span>` : '<span class="text-gray-300 italic text-xs">—</span>'}
            </td>
            <td class="px-4 py-3">
                ${e.siswa_kelas ? `<span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-semibold">${e.siswa_kelas}</span>` : '<span class="text-gray-300 text-xs">—</span>'}
            </td>
            <td class="px-4 py-3">
                ${e.device_id ? `<span class="font-mono text-xs font-bold px-2 py-0.5 bg-violet-50 text-violet-700 border border-violet-200 rounded-lg">${e.device_id}</span>` : '<span class="text-gray-300 text-xs">—</span>'}
            </td>
            <td class="px-4 py-3 font-mono text-xs text-gray-400">${e.ip || '—'}</td>
            <td class="px-4 py-3">
                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold border ${cfg.badge}">${cfg.label}</span>
            </td>
        </tr>`;
    }).join('');
}

async function pollLog() {
    try {
        const r = await fetch('{{ route("rfid-log.feed") }}');
        if (!r.ok) return;
        const data = await r.json();
        const prev = allEntries.length;
        allEntries = data;
        renderTable(allEntries);
        const now = new Date();
        document.getElementById('log-last-update').innerHTML =
            `<span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Update ${now.toTimeString().slice(0,5)}`;
        if (data.length > prev) showToast('info', `${data.length - prev} scan baru`);
    } catch(e) {}
}

setInterval(pollLog, 5000);
</script>
@endpush
