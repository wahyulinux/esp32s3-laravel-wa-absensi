@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

@php
    $pctHadir  = $totalSiswa > 0 ? round(($hadirHariIni / $totalSiswa) * 100) : 0;
    $pctPulang = $hadirHariIni > 0 ? round(($sudahPulang / $hadirHariIni) * 100) : 0;
    $pctBelum  = $hadirHariIni > 0 ? round(($belumPulang / $hadirHariIni) * 100) : 0;
@endphp

{{-- Page Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>
    <div class="flex items-center gap-2">
        <span class="flex items-center gap-1.5 text-xs text-emerald-600 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-full font-medium">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
            Live
        </span>
    </div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-violet-600">
                    <path d="M4.5 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM14.25 8.625a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0zM1.5 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM17.25 19.128l-.001.144a2.25 2.25 0 01-.233.96 10.088 10.088 0 005.06-1.01.75.75 0 00.42-.643 4.875 4.875 0 00-6.957-4.611 8.586 8.586 0 011.71 5.157v.003z"/>
                </svg>
            </div>
            <span class="text-xs text-gray-400 font-medium">Total</span>
        </div>
        <p class="text-3xl font-bold text-gray-900" id="stat-total">{{ $totalSiswa }}</p>
        <p class="text-xs text-gray-400 mt-1.5">siswa aktif terdaftar</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-blue-600">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-blue-500 bg-blue-50 px-2 py-0.5 rounded-full" id="stat-hadir-pct">{{ $pctHadir }}%</span>
        </div>
        <p class="text-3xl font-bold text-blue-600" id="stat-hadir">{{ $hadirHariIni }}</p>
        <p class="text-xs text-gray-400 mt-1.5">hadir hari ini</p>
        <div class="mt-3 w-full bg-blue-50 rounded-full h-1.5">
            <div id="bar-hadir" class="h-1.5 rounded-full bg-blue-500 transition-all duration-500" style="width:{{ $pctHadir }}%"></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-emerald-600">
                    <path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z"/>
                    <path d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.432z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full" id="stat-pulang-pct">{{ $pctPulang }}%</span>
        </div>
        <p class="text-3xl font-bold text-emerald-600" id="stat-pulang">{{ $sudahPulang }}</p>
        <p class="text-xs text-gray-400 mt-1.5">sudah pulang</p>
        <div class="mt-3 w-full bg-emerald-50 rounded-full h-1.5">
            <div id="bar-pulang" class="h-1.5 rounded-full bg-emerald-500 transition-all duration-500" style="width:{{ $pctPulang }}%"></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-amber-600">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full" id="stat-belum-pct">{{ $pctBelum }}%</span>
        </div>
        <p class="text-3xl font-bold text-amber-500" id="stat-belum">{{ $belumPulang }}</p>
        <p class="text-xs text-gray-400 mt-1.5">belum pulang</p>
        <div class="mt-3 w-full bg-amber-50 rounded-full h-1.5">
            <div id="bar-belum" class="h-1.5 rounded-full bg-amber-400 transition-all duration-500" style="width:{{ $pctBelum }}%"></div>
        </div>
    </div>
</div>

{{-- Device Monitoring --}}
<div class="mb-5">
    <div class="flex items-center justify-between mb-3">
        <div>
            <h2 class="text-sm font-semibold text-gray-800">Status Perangkat ESP32</h2>
            <p class="text-xs text-gray-400 mt-0.5">Heartbeat setiap 60 detik · offline jika &gt; 10 menit tidak merespons</p>
        </div>
        <span class="text-xs text-gray-400 tabular-nums" id="device-last-update"></span>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3" id="device-grid">
        @forelse($devices as $device)
        @php
            $st = $device->status;
            $dot   = $st === 'online' ? 'bg-emerald-400' : ($st === 'warning' ? 'bg-amber-400' : 'bg-red-400');
            $badge = $st === 'online' ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                   : ($st === 'warning' ? 'bg-amber-50 text-amber-700 border-amber-200'
                   : 'bg-red-50 text-red-600 border-red-200');
            $label = $st === 'online' ? 'Online' : ($st === 'warning' ? 'Lambat' : 'Offline');
        @endphp
        <div class="bg-white rounded-xl p-3.5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2.5">
                <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md font-mono tracking-wide">{{ $device->device_id }}</span>
                <div class="relative shrink-0">
                    <span class="w-2 h-2 rounded-full {{ $dot }} block"></span>
                    @if($st !== 'offline')
                    <span class="w-2 h-2 rounded-full {{ $dot }} block absolute inset-0 animate-ping opacity-60"></span>
                    @endif
                </div>
            </div>
            <p class="text-xs font-semibold text-gray-700 truncate">{{ $device->nama ?? ('Ruang ' . ltrim($device->device_id, 'R')) }}</p>
            <p class="text-[10px] text-gray-400 mt-0.5 font-mono truncate">{{ $device->ip_address ?? '—.—.—.—' }}</p>
            <div class="mt-2.5 flex items-center justify-between gap-1">
                <span class="text-[10px] px-1.5 py-0.5 rounded border font-semibold {{ $badge }}">{{ $label }}</span>
                <span class="text-[10px] text-gray-400 font-mono shrink-0">{{ $device->last_seen ? $device->last_seen->format('H:i') : '—' }}</span>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-xl border border-dashed border-gray-200 py-8 text-center">
            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-300">
                    <path fill-rule="evenodd" d="M1.371 8.143c5.858-5.857 15.356-5.857 21.213 0a.75.75 0 010 1.061l-.53.53a.75.75 0 01-1.06 0c-4.98-4.979-13.053-4.979-18.032 0a.75.75 0 01-1.061 0l-.53-.53a.75.75 0 010-1.061zm3.182 3.182c4.1-4.1 10.749-4.1 14.85 0a.75.75 0 010 1.061l-.53.53a.75.75 0 01-1.06 0 8.25 8.25 0 00-11.67 0 .75.75 0 01-1.06 0l-.53-.53a.75.75 0 010-1.061zm3.204 3.204a6 6 0 018.486 0 .75.75 0 010 1.061l-.53.53a.75.75 0 01-1.061 0 3.75 3.75 0 00-5.303 0 .75.75 0 01-1.061 0l-.53-.53a.75.75 0 010-1.061zm3.182 3.182a1.5 1.5 0 012.122 0 .75.75 0 010 1.061l-.53.53a.75.75 0 01-1.061 0l-.53-.53a.75.75 0 010-1.061z" clip-rule="evenodd"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-400">Belum ada perangkat terdaftar</p>
            <p class="text-xs text-gray-300 mt-1">Perangkat akan muncul otomatis saat ESP32 pertama kali online</p>
        </div>
        @endforelse
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Feed Terbaru --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-gray-900 text-sm">Aktivitas Hari Ini</h2>
                <p class="text-xs text-gray-400 mt-0.5">Refresh otomatis setiap 15 detik</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="flex items-center gap-1.5 text-xs text-gray-400" id="last-update-wrap">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span id="last-update">Live</span>
                </span>
                <a href="{{ route('absensi.hari-ini') }}" class="text-xs text-blue-600 hover:text-blue-700 font-semibold bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                    Lihat semua →
                </a>
            </div>
        </div>
        <div id="feed-list" class="divide-y divide-gray-50 flex-1">
            @forelse($absensiTerbaru as $absensi)
            <div class="px-5 py-3.5 flex items-center gap-4 hover:bg-gray-50/60 transition-colors">
                <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 ring-2 {{ $absensi->waktu_keluar ? 'ring-emerald-200' : 'ring-blue-100' }}">
                    @if($absensi->foto_masuk)
                    <img src="{{ Storage::disk('public')->url($absensi->foto_masuk) }}" class="w-full h-full object-cover"
                         onerror="this.outerHTML='<div class=\'w-full h-full flex items-center justify-center text-white text-sm font-bold\' style=\'background:{{ avatarColor($absensi->siswa->nama) }}\'>{{ avatarInitials($absensi->siswa->nama) }}</div>'">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-white text-sm font-bold"
                         style="background:{{ avatarColor($absensi->siswa->nama) }}">{{ avatarInitials($absensi->siswa->nama) }}</div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm truncate">{{ $absensi->siswa->nama }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $absensi->siswa->kelas }} &middot; {{ $absensi->siswa->nis ?? 'NIS —' }}</p>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-xs text-gray-600 font-mono tabular-nums">
                        {{ $absensi->waktu_masuk?->format('H:i') }}
                        @if($absensi->waktu_keluar)
                            <span class="text-gray-300 mx-0.5">→</span>{{ $absensi->waktu_keluar->format('H:i') }}
                        @endif
                    </p>
                    <span class="inline-flex items-center gap-1 mt-1 text-xs px-2 py-0.5 rounded-full font-semibold
                        {{ $absensi->waktu_keluar ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                        {{ $absensi->waktu_keluar ? '✓ Lengkap' : '● Hadir' }}
                    </span>
                </div>
            </div>
            @empty
            <div class="py-16 text-center text-gray-400">
                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-gray-300">
                        <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 017.5 3v1.5h9V3A.75.75 0 0118 3v1.5h.75a3 3 0 013 3v11.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V7.5a3 3 0 013-3H6V3a.75.75 0 01.75-.75zm13.5 9a1.5 1.5 0 00-1.5-1.5H5.25a1.5 1.5 0 00-1.5 1.5v7.5a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5v-7.5z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-500">Belum ada absensi hari ini</p>
                <p class="text-xs mt-1">Menunggu scan kartu RFID dari ESP32...</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Kehadiran Per Kelas --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-900 text-sm">Kehadiran Per Kelas</h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ now()->translatedFormat('d F Y') }}</p>
        </div>
        <div class="p-5 space-y-4" id="kelas-list">
            @forelse($perKelas as $kelas => $data)
            @php $pct = $data['total'] > 0 ? round(($data['hadir'] / $data['total']) * 100) : 0; @endphp
            <div>
                <div class="flex justify-between items-center mb-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $pct >= 80 ? 'bg-emerald-400' : ($pct >= 50 ? 'bg-amber-400' : 'bg-red-400') }}"></span>
                        <span class="text-sm font-semibold text-gray-700">{{ $kelas }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs text-gray-400">{{ $data['hadir'] }}/{{ $data['total'] }}</span>
                        <span class="text-sm font-bold {{ $pct >= 80 ? 'text-emerald-600' : ($pct >= 50 ? 'text-amber-500' : 'text-red-500') }}">{{ $pct }}%</span>
                    </div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="h-2 rounded-full transition-all duration-500 {{ $pct >= 80 ? 'bg-emerald-500' : ($pct >= 50 ? 'bg-amber-400' : 'bg-red-400') }}"
                         style="width:{{ $pct }}%"></div>
                </div>
            </div>
            @empty
            <div class="py-8 text-center">
                <p class="text-sm text-gray-400">Belum ada data kelas</p>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
let lastCount = {{ $absensiTerbaru->count() }};

async function pollStats() {
    try {
        const r = await fetch('{{ route("dashboard.stats") }}', { headers: { 'Accept': 'application/json' } });
        if (!r.ok) return;
        const d = await r.json();

        document.getElementById('stat-hadir').textContent      = d.hadir;
        document.getElementById('stat-pulang').textContent     = d.pulang;
        document.getElementById('stat-belum').textContent      = d.belum;
        document.getElementById('stat-hadir-pct').textContent  = d.pct_hadir  + '%';
        document.getElementById('stat-pulang-pct').textContent = d.pct_pulang + '%';
        document.getElementById('stat-belum-pct').textContent  = d.pct_belum  + '%';
        document.getElementById('bar-hadir').style.width       = d.pct_hadir  + '%';
        document.getElementById('bar-pulang').style.width      = d.pct_pulang + '%';
        document.getElementById('bar-belum').style.width       = d.pct_belum  + '%';

        if (d.hadir !== lastCount) {
            lastCount = d.hadir;
            showToast('info', 'Scan baru terdeteksi');
            const feedResp = await fetch('{{ route("dashboard.feed") }}', { headers: { 'Accept': 'application/json' } });
            const feed = await feedResp.json();
            renderFeed(feed);
        }

        const now = new Date();
        document.getElementById('last-update').textContent = 'Update ' + now.toTimeString().slice(0,5);
    } catch(e) {}
}

function renderFeed(items) {
    const el = document.getElementById('feed-list');
    if (!items.length) {
        el.innerHTML = `<div class="py-16 text-center text-gray-400">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-gray-300"><path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 017.5 3v1.5h9V3A.75.75 0 0118 3v1.5h.75a3 3 0 013 3v11.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V7.5a3 3 0 013-3H6V3a.75.75 0 01.75-.75zm13.5 9a1.5 1.5 0 00-1.5-1.5H5.25a1.5 1.5 0 00-1.5 1.5v7.5a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5v-7.5z" clip-rule="evenodd"/></svg>
            </div>
            <p class="text-sm font-semibold text-gray-500">Belum ada absensi hari ini</p>
        </div>`;
        return;
    }
    el.innerHTML = items.map(a => `
        <div class="px-5 py-3.5 flex items-center gap-4 hover:bg-gray-50/60 transition-colors">
            <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 ring-2 ${a.waktu_keluar ? 'ring-emerald-200' : 'ring-blue-100'}">
                ${a.foto_url
                    ? `<img src="${a.foto_url}" class="w-full h-full object-cover">`
                    : `<div class="w-full h-full flex items-center justify-center text-white text-sm font-bold" style="background:${a.avatar_color}">${a.initials}</div>`
                }
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-800 text-sm truncate">${a.nama}</p>
                <p class="text-xs text-gray-400 mt-0.5">${a.kelas} &middot; ${a.nis||'NIS —'}${a.device_id ? ` &middot; <span class="text-violet-500 font-semibold">${a.device_id}</span>` : ''}</p>
            </div>
            <div class="text-right shrink-0">
                <p class="text-xs text-gray-600 font-mono tabular-nums">${a.waktu_masuk||'—'}${a.waktu_keluar ? ' → '+a.waktu_keluar : ''}</p>
                <span class="inline-flex items-center gap-1 mt-1 text-xs px-2 py-0.5 rounded-full font-semibold ${a.waktu_keluar ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200'}">
                    ${a.waktu_keluar ? '✓ Lengkap' : '● Hadir'}
                </span>
            </div>
        </div>
    `).join('');
}

setInterval(pollStats, 15000);

// Device monitoring
async function pollDevices() {
    try {
        const r = await fetch('{{ route("dashboard.devices") }}');
        if (!r.ok) return;
        const devices = await r.json();
        renderDevices(devices);
        const now = new Date();
        const el = document.getElementById('device-last-update');
        if (el) el.textContent = 'Update ' + now.toTimeString().slice(0,5);
    } catch(e) {}
}

function renderDevices(devices) {
    const el = document.getElementById('device-grid');
    if (!el) return;
    if (!devices.length) {
        el.innerHTML = `<div class="col-span-full bg-white rounded-xl border border-dashed border-gray-200 py-8 text-center">
            <p class="text-sm font-medium text-gray-400">Belum ada perangkat terdaftar</p>
        </div>`;
        return;
    }
    const cfg = {
        online:  { dot: 'bg-emerald-400', badge: 'bg-emerald-50 text-emerald-700 border-emerald-200', label: 'Online',  ping: true  },
        warning: { dot: 'bg-amber-400',   badge: 'bg-amber-50 text-amber-700 border-amber-200',       label: 'Lambat',  ping: true  },
        offline: { dot: 'bg-red-400',     badge: 'bg-red-50 text-red-600 border-red-200',             label: 'Offline', ping: false },
    };
    el.innerHTML = devices.map(d => {
        const c    = cfg[d.status] || cfg.offline;
        const nama = d.nama || ('Ruang ' + d.device_id.replace(/^R/i, ''));
        return `<div class="bg-white rounded-xl p-3.5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2.5">
                <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md font-mono tracking-wide">${d.device_id}</span>
                <div class="relative shrink-0">
                    <span class="w-2 h-2 rounded-full ${c.dot} block"></span>
                    ${c.ping ? `<span class="w-2 h-2 rounded-full ${c.dot} block absolute inset-0 animate-ping opacity-60"></span>` : ''}
                </div>
            </div>
            <p class="text-xs font-semibold text-gray-700 truncate">${nama}</p>
            <p class="text-[10px] text-gray-400 mt-0.5 font-mono truncate">${d.ip || '—.—.—.—'}</p>
            <div class="mt-2.5 flex items-center justify-between gap-1">
                <span class="text-[10px] px-1.5 py-0.5 rounded border font-semibold ${c.badge}">${c.label}</span>
                <span class="text-[10px] text-gray-400 font-mono shrink-0">${d.last_seen || '—'}</span>
            </div>
        </div>`;
    }).join('');
}

pollDevices();
setInterval(pollDevices, 15000);
</script>
@endpush
