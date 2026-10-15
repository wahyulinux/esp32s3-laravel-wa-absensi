@extends('layouts.app')
@section('title', 'Log UID Kartu')

@section('breadcrumb')
    <span class="text-gray-700 text-sm font-semibold">Log UID Kartu</span>
@endsection

@section('content')

<div class="flex flex-wrap items-start justify-between gap-3 mb-5">
    <div>
        <h1 class="text-xl lg:text-2xl font-extrabold text-[#1E1E1E] tracking-tight">Log UID Kartu RFID</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-0.5 flex items-center gap-1.5">
            <i class="fa-solid fa-id-card text-[#FF8533]"></i>
            <span>Setiap scan kartu RFID yang diterima server &middot; Disimpan 7 hari &middot; Real-time</span>
        </p>
    </div>
    <div class="flex items-center gap-2.5">
        <span class="flex items-center gap-1.5 text-xs text-gray-500 bg-white px-2.5 py-1 rounded-full border border-gray-200" id="log-last-update">
            <span class="w-1.5 h-1.5 rounded-full bg-[#2E5A27] animate-pulse"></span>
            <span>Live</span>
        </span>
        {{-- Filter status --}}
        <div class="flex items-center gap-1 bg-white p-1 rounded-xl border border-gray-200" id="filter-bar">
            <button onclick="setFilter('')"
                    class="filter-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-[#5C2828] text-white" data-filter="">
                Semua
            </button>
            <button onclick="setFilter('masuk')"
                    class="filter-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-gray-600 hover:text-[#2E5A27] hover:bg-[#2E5A27]/10" data-filter="masuk">
                Masuk
            </button>
            <button onclick="setFilter('keluar')"
                    class="filter-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-gray-600 hover:text-[#FF8533] hover:bg-[#FF8533]/10" data-filter="keluar">
                Keluar
            </button>
            <button onclick="setFilter('tidak_dikenal')"
                    class="filter-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-gray-600 hover:text-red-600 hover:bg-red-50" data-filter="tidak_dikenal">
                Tidak Dikenal
            </button>
        </div>
    </div>
</div>

{{-- Summary chips --}}
<div class="flex flex-wrap gap-2 mb-4" id="summary-chips">
    <span class="text-xs px-3 py-1.5 rounded-xl bg-white border border-gray-200 text-gray-600 font-semibold shadow-2xs">
        Total: <span id="cnt-all" class="font-black text-[#1E1E1E]">{{ count($entries) }}</span>
    </span>
    <span class="text-xs px-3 py-1.5 rounded-xl bg-[#2E5A27]/10 border border-[#2E5A27]/25 text-[#2E5A27] font-semibold">
        Masuk: <span id="cnt-masuk" class="font-black">{{ collect($entries)->where('status','masuk')->count() }}</span>
    </span>
    <span class="text-xs px-3 py-1.5 rounded-xl bg-[#FF8533]/10 border border-[#FF8533]/25 text-[#FF8533] font-semibold">
        Keluar: <span id="cnt-keluar" class="font-black">{{ collect($entries)->where('status','keluar')->count() }}</span>
    </span>
    <span class="text-xs px-3 py-1.5 rounded-xl bg-red-50 border border-red-200 text-red-600 font-semibold">
        Tidak Dikenal: <span id="cnt-unknown" class="font-black">{{ collect($entries)->where('status','tidak_dikenal')->count() }}</span>
    </span>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50/50">
                <th class="text-left px-5 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider w-36">Waktu</th>
                <th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">UID Kartu</th>
                <th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">Siswa</th>
                <th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">Kelas</th>
                <th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">Ruangan</th>
                <th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">IP Address</th>
                <th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">Status</th>
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
                    'masuk'         => 'bg-[#2E5A27]/10 text-[#2E5A27] border-[#2E5A27]/25',
                    'keluar'        => 'bg-[#FF8533]/10 text-[#FF8533] border-[#FF8533]/25',
                    'tidak_dikenal' => 'bg-red-50 text-red-600 border-red-200',
                    'sudah_lengkap' => 'bg-gray-100 text-gray-600 border-gray-200',
                    default         => 'bg-gray-100 text-gray-500 border-gray-200',
                };
                $badgeIcon = match($st) {
                    'masuk'         => 'fa-arrow-right-to-bracket',
                    'keluar'        => 'fa-arrow-right-from-bracket',
                    'tidak_dikenal' => 'fa-circle-xmark',
                    'sudah_lengkap' => 'fa-circle-check',
                    default         => 'fa-circle-info',
                };
                $badgeLabel = match($st) {
                    'masuk'         => 'Masuk',
                    'keluar'        => 'Keluar',
                    'tidak_dikenal' => 'Tidak Dikenal',
                    'sudah_lengkap' => 'Sudah Lengkap',
                    default         => $st,
                };
            @endphp
            <tr class="hover:bg-[#F4F6F8]/60 transition-colors {{ $rowClass }}">
                <td class="px-5 py-3 font-mono text-xs text-gray-500 tabular-nums whitespace-nowrap">
                    {{ \Carbon\Carbon::createFromTimestamp($e['time'])->format('d/m H:i:s') }}
                </td>
                <td class="px-4 py-3">
                    <span class="font-mono text-xs font-bold tracking-widest px-2.5 py-1 rounded-lg border
                        {{ $e['status'] === 'tidak_dikenal' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-[#F4F6F8] text-[#5C2828] border-gray-200' }}">
                        {{ $e['rfid_uid'] }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm">
                    @if($e['siswa_nama'])
                        <span class="font-bold text-[#1E1E1E]">{{ $e['siswa_nama'] }}</span>
                    @else
                        <span class="text-gray-400 italic text-xs">—</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    @if($e['siswa_kelas'])
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded-lg text-xs font-bold">{{ $e['siswa_kelas'] }}</span>
                    @else
                        <span class="text-gray-300 text-xs">—</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    @if($e['device_id'])
                        <span class="font-mono text-xs font-bold px-2 py-0.5 bg-[#5C2828]/10 text-[#5C2828] border border-[#5C2828]/20 rounded-lg flex items-center gap-1 w-fit">
                            <i class="fa-solid fa-satellite-dish text-[9px]"></i>
                            {{ $e['device_id'] }}
                        </span>
                    @else
                        <span class="text-gray-300 text-xs">—</span>
                    @endif
                </td>
                <td class="px-4 py-3 font-mono text-xs text-gray-400">{{ $e['ip'] ?? '—' }}</td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $badgeCls }}">
                        <i class="fa-solid {{ $badgeIcon }} text-[10px]"></i>
                        <span>{{ $badgeLabel }}</span>
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-20 text-center">
                    <div class="w-16 h-16 rounded-full bg-[#F4F6F8] flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <i class="fa-solid fa-credit-card text-3xl"></i>
                    </div>
                    <p class="font-bold text-gray-600">Belum ada log scan</p>
                    <p class="text-xs text-gray-400 mt-1">Log akan muncul setiap kali ESP32 mengirim data scan RFID</p>
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
    masuk:         { badge: 'bg-[#2E5A27]/10 text-[#2E5A27] border-[#2E5A27]/25', icon: 'fa-arrow-right-to-bracket', label: 'Masuk' },
    keluar:        { badge: 'bg-[#FF8533]/10 text-[#FF8533] border-[#FF8533]/25', icon: 'fa-arrow-right-from-bracket', label: 'Keluar' },
    tidak_dikenal: { badge: 'bg-red-50 text-red-600 border-red-200',             icon: 'fa-circle-xmark', label: 'Tidak Dikenal' },
    sudah_lengkap: { badge: 'bg-gray-100 text-gray-600 border-gray-200',         icon: 'fa-circle-check', label: 'Sudah Lengkap' },
};

function setFilter(f) {
    activeFilter = f;
    document.querySelectorAll('.filter-btn').forEach(btn => {
        const active = btn.dataset.filter === f;
        btn.className = 'filter-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all ' +
            (active ? 'bg-[#5C2828] text-white' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100');
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

    const esc = v => String(v).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

    tbody.innerHTML = filtered.slice(0, 500).map(e => {
        const cfg      = STATUS_CFG[e.status] || STATUS_CFG.sudah_lengkap;
        const rowCls   = e.status === 'tidak_dikenal' ? 'bg-red-50/40' : e.status === 'sudah_lengkap' ? 'bg-gray-50/60' : '';
        const uidCls   = e.status === 'tidak_dikenal'
            ? 'bg-red-50 text-red-700 border-red-200'
            : 'bg-[#F4F6F8] text-[#5C2828] border-gray-200';
        return `<tr class="hover:bg-[#F4F6F8]/60 transition-colors ${rowCls}">
            <td class="px-5 py-3 font-mono text-xs text-gray-500 tabular-nums whitespace-nowrap">${fmtTime(e.time)}</td>
            <td class="px-4 py-3">
                <span class="font-mono text-xs font-bold tracking-widest px-2.5 py-1 rounded-lg border ${uidCls}">${esc(e.rfid_uid)}</span>
            </td>
            <td class="px-4 py-3 text-sm">
                ${e.siswa_nama ? `<span class="font-bold text-[#1E1E1E]">${esc(e.siswa_nama)}</span>` : '<span class="text-gray-400 italic text-xs">—</span>'}
            </td>
            <td class="px-4 py-3">
                ${e.siswa_kelas ? `<span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded-lg text-xs font-bold">${esc(e.siswa_kelas)}</span>` : '<span class="text-gray-300 text-xs">—</span>'}
            </td>
            <td class="px-4 py-3">
                ${e.device_id ? `<span class="font-mono text-xs font-bold px-2 py-0.5 bg-[#5C2828]/10 text-[#5C2828] border border-[#5C2828]/20 rounded-lg flex items-center gap-1 w-fit"><i class="fa-solid fa-satellite-dish text-[9px]"></i> ${esc(e.device_id)}</span>` : '<span class="text-gray-300 text-xs">—</span>'}
            </td>
            <td class="px-4 py-3 font-mono text-xs text-gray-400">${esc(e.ip || '—')}</td>
            <td class="px-4 py-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold border ${cfg.badge}">
                    <i class="fa-solid ${cfg.icon} text-[10px]"></i>
                    <span>${cfg.label}</span>
                </span>
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
        const pad = n => String(n).padStart(2,'0');
        document.getElementById('log-last-update').innerHTML =
            `<span class="w-1.5 h-1.5 rounded-full bg-[#2E5A27] animate-pulse"></span> Update ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
        if (data.length > prev) showToast('info', `${data.length - prev} scan kartu baru`);
    } catch(e) {}
}

setInterval(pollLog, 5000);
</script>
@endpush
