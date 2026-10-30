@extends('layouts.app')
@section('title', 'Dashboard Absensi')

@section('content')

@php
    $pctHadir  = $totalSiswa > 0 ? round(($hadirHariIni / $totalSiswa) * 100) : 0;
    $pctPulang = $hadirHariIni > 0 ? round(($sudahPulang / $hadirHariIni) * 100) : 0;
    $pctBelum  = $hadirHariIni > 0 ? round(($belumPulang / $hadirHariIni) * 100) : 0;
@endphp

{{-- ── Page Header ── --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
    <div class="flex items-center gap-3.5">
        <div class="w-12 h-12 rounded-2xl bg-white p-1.5 flex items-center justify-center shadow-xs border border-gray-200/80 shrink-0">
            <img src="{{ asset('images/logo-sekolah.png') }}" alt="{{ $namaSekolah ?? 'Logo Sekolah' }}" class="w-full h-full object-contain">
        </div>
        <div>
            <h1 class="text-xl lg:text-2xl font-extrabold text-[#1E1E1E] tracking-tight">Dashboard Absensi Siswa</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5 flex items-center gap-1.5 flex-wrap">
                <i class="fa-regular fa-calendar-check text-[#FF8533]"></i>
                <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                <span class="text-gray-300">&middot;</span>
                <span class="text-gray-500 font-medium">{{ $namaSekolah ?? 'Sistem Absensi SMKS Nurul Amalia' }}</span>
            </p>
        </div>
    </div>

    <div class="flex items-center gap-2.5">
        {{-- Live Status Indicator: Forest Green (#2E5A27) --}}
        <div class="flex items-center gap-2 px-3 py-1.5 bg-[#2E5A27]/10 border border-[#2E5A27]/25 rounded-xl">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#2E5A27] opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-[#2E5A27]"></span>
            </span>
            <span class="text-xs font-bold text-[#2E5A27] tracking-wide uppercase">Live Presensi</span>
        </div>

        {{-- Quick Link Button: Oranye Terang (#FF8533) --}}
        <a href="{{ route('absensi.hari-ini') }}"
           class="flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-[#FF8533] text-white hover:bg-[#EA6D1A] transition-all shadow-sm shadow-[#FF8533]/30 active:scale-95">
            <i class="fa-solid fa-list-check text-xs"></i>
            <span>Tabel Hari Ini</span>
        </a>
    </div>
</div>

{{-- ── Stat Cards (4 Metrik Utama) ── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    {{-- Card 1: Total Siswa Aktif (Secondary Maroon Accent #5C2828) --}}
    <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 hover:shadow-md hover:border-gray-200 transition-all group">
        <div class="flex items-start justify-between mb-3">
            <div class="w-11 h-11 rounded-2xl bg-[#5C2828]/10 text-[#5C2828] flex items-center justify-center text-lg group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
            <span class="text-[11px] font-bold text-gray-500 bg-gray-100/80 px-2.5 py-0.5 rounded-full uppercase tracking-wider">Total</span>
        </div>
        <p class="text-3xl font-black text-[#1E1E1E] tracking-tight" id="stat-total">{{ $totalSiswa }}</p>
        <div class="flex items-center justify-between mt-2">
            <p class="text-xs text-gray-500 font-medium">Siswa aktif terdaftar</p>
            <span class="text-[10px] text-gray-400 font-mono">100% database</span>
        </div>
        <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
            <div class="h-1.5 rounded-full bg-[#5C2828]" style="width: 100%"></div>
        </div>
    </div>

    {{-- Card 2: Hadir Hari Ini (Primary Bright Orange #FF8533) --}}
    <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 hover:shadow-md hover:border-gray-200 transition-all group">
        <div class="flex items-start justify-between mb-3">
            <div class="w-11 h-11 rounded-2xl bg-[#FF8533]/15 text-[#FF8533] flex items-center justify-center text-lg group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-clipboard-user"></i>
            </div>
            <span class="text-xs font-bold text-[#FF8533] bg-[#FF8533]/10 border border-[#FF8533]/20 px-2.5 py-0.5 rounded-full flex items-center gap-1" id="stat-hadir-pct">
                <i class="fa-solid fa-arrow-trend-up text-[10px]"></i> {{ $pctHadir }}%
            </span>
        </div>
        <p class="text-3xl font-black text-[#FF8533] tracking-tight" id="stat-hadir">{{ $hadirHariIni }}</p>
        <div class="flex items-center justify-between mt-2">
            <p class="text-xs text-gray-500 font-medium">Hadir hari ini</p>
            <span class="text-[10px] text-gray-400 font-mono">Presensi masuk</span>
        </div>
        <div class="mt-3 w-full bg-[#FF8533]/10 rounded-full h-1.5 overflow-hidden">
            <div id="bar-hadir" class="h-1.5 rounded-full bg-[#FF8533] transition-all duration-500" style="width:{{ $pctHadir }}%"></div>
        </div>
    </div>

    {{-- Card 3: Sudah Pulang (Forest Green Accent #2E5A27) --}}
    <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 hover:shadow-md hover:border-gray-200 transition-all group">
        <div class="flex items-start justify-between mb-3">
            <div class="w-11 h-11 rounded-2xl bg-[#2E5A27]/15 text-[#2E5A27] flex items-center justify-center text-lg group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-person-walking-arrow-right"></i>
            </div>
            <span class="text-xs font-bold text-[#2E5A27] bg-[#2E5A27]/10 border border-[#2E5A27]/25 px-2.5 py-0.5 rounded-full flex items-center gap-1" id="stat-pulang-pct">
                <i class="fa-solid fa-circle-check text-[10px]"></i> {{ $pctPulang }}%
            </span>
        </div>
        <p class="text-3xl font-black text-[#2E5A27] tracking-tight" id="stat-pulang">{{ $sudahPulang }}</p>
        <div class="flex items-center justify-between mt-2">
            <p class="text-xs text-gray-500 font-medium">Sudah pulang</p>
            <span class="text-[10px] text-gray-400 font-mono">Absen lengkap</span>
        </div>
        <div class="mt-3 w-full bg-[#2E5A27]/10 rounded-full h-1.5 overflow-hidden">
            <div id="bar-pulang" class="h-1.5 rounded-full bg-[#2E5A27] transition-all duration-500" style="width:{{ $pctPulang }}%"></div>
        </div>
    </div>

    {{-- Card 4: Belum Pulang (Warm Amber) --}}
    <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 hover:shadow-md hover:border-gray-200 transition-all group">
        <div class="flex items-start justify-between mb-3">
            <div class="w-11 h-11 rounded-2xl bg-amber-500/15 text-amber-600 flex items-center justify-center text-lg group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <span class="text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-full flex items-center gap-1" id="stat-belum-pct">
                <i class="fa-solid fa-hourglass-half text-[10px]"></i> {{ $pctBelum }}%
            </span>
        </div>
        <p class="text-3xl font-black text-amber-600 tracking-tight" id="stat-belum">{{ $belumPulang }}</p>
        <div class="flex items-center justify-between mt-2">
            <p class="text-xs text-gray-500 font-medium">Masih di sekolah</p>
            <span class="text-[10px] text-gray-400 font-mono">Belum checkout</span>
        </div>
        <div class="mt-3 w-full bg-amber-100 rounded-full h-1.5 overflow-hidden">
            <div id="bar-belum" class="h-1.5 rounded-full bg-amber-500 transition-all duration-500" style="width:{{ $pctBelum }}%"></div>
        </div>
    </div>
</div>

{{-- ── Device Monitoring ESP32 ── --}}
<div class="mb-6 bg-white rounded-2xl p-5 shadow-xs border border-gray-100">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
        <div>
            <h2 class="text-sm font-bold text-[#1E1E1E] flex items-center gap-2">
                <i class="fa-solid fa-microchip text-[#FF8533]"></i>
                <span>Status Perangkat ESP32-S3 WROOM CAM</span>
            </h2>
            <p class="text-xs text-gray-500 mt-0.5">Heartbeat setiap 60 detik &middot; Status offline jika &gt; 10 menit tidak merespons</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-[11px] font-mono text-gray-400 flex items-center gap-1.5" id="device-last-update">
                <i class="fa-regular fa-clock text-xs"></i>
                <span>Memuat...</span>
            </span>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3" id="device-grid">
        @forelse($devices as $device)
        @php
            $st = $device->status;
            $dot   = $st === 'online' ? 'bg-[#2E5A27]' : ($st === 'warning' ? 'bg-amber-400' : 'bg-red-400');
            $badge = $st === 'online' ? 'bg-[#2E5A27]/10 text-[#2E5A27] border-[#2E5A27]/30'
                   : ($st === 'warning' ? 'bg-amber-50 text-amber-700 border-amber-200'
                   : 'bg-red-50 text-red-600 border-red-200');
            $icon  = $st === 'online' ? 'fa-circle-check' : ($st === 'warning' ? 'fa-triangle-exclamation' : 'fa-circle-xmark');
            $label = $st === 'online' ? 'Online' : ($st === 'warning' ? 'Lambat' : 'Offline');
        @endphp
        <div class="bg-[#F4F6F8] rounded-xl p-3.5 border border-gray-200/80 hover:bg-white hover:border-[#FF8533]/40 hover:shadow-sm transition-all group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-[#5C2828] bg-white px-2 py-0.5 rounded-md font-mono border border-gray-200 tracking-wider">
                    {{ $device->device_id }}
                </span>
                <div class="relative shrink-0 flex items-center justify-center">
                    <span class="w-2 h-2 rounded-full {{ $dot }} block"></span>
                    @if($st !== 'offline')
                    <span class="w-2 h-2 rounded-full {{ $dot }} block absolute inset-0 animate-ping opacity-60"></span>
                    @endif
                </div>
            </div>
            <p class="text-xs font-bold text-[#1E1E1E] truncate">{{ $device->nama ?? ('Ruang ' . ltrim($device->device_id, 'R')) }}</p>
            <p class="text-[10px] text-gray-500 mt-0.5 font-mono truncate flex items-center gap-1">
                <i class="fa-solid fa-network-wired text-[9px] text-gray-400"></i>
                {{ $device->ip_address ?? '—.—.—.—' }}
            </p>
            <div class="mt-2.5 pt-2 border-t border-gray-200/60 flex items-center justify-between gap-1">
                <span class="text-[10px] px-2 py-0.5 rounded-full border font-bold flex items-center gap-1 {{ $badge }}">
                    <i class="fa-solid {{ $icon }} text-[9px]"></i>
                    {{ $label }}
                </span>
                <span class="text-[10px] text-gray-400 font-mono shrink-0">{{ $device->last_seen ? $device->last_seen->format('H:i') : '—' }}</span>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-[#F4F6F8] rounded-xl border border-dashed border-gray-300 py-8 text-center">
            <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center mx-auto mb-3 shadow-2xs text-gray-400">
                <i class="fa-solid fa-video-slash text-xl"></i>
            </div>
            <p class="text-sm font-bold text-gray-600">Belum ada perangkat terdaftar</p>
            <p class="text-xs text-gray-400 mt-1">Perangkat ESP32-S3 akan muncul otomatis saat pertama kali online</p>
        </div>
        @endforelse
    </div>
</div>

{{-- ── Content Grid: Aktivitas Real-time & Rekap Kelas ── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Kolom Kiri: Feed Aktivitas Scan Hari Ini (2 Kolom) --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-xs border border-gray-100 flex flex-col overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-white">
            <div>
                <h2 class="font-bold text-[#1E1E1E] text-sm flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-[#FF8533]"></i>
                    <span>Aktivitas Scan Hari Ini</span>
                </h2>
                <p class="text-xs text-gray-400 mt-0.5">Memperbarui secara otomatis setiap 5 detik</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="flex items-center gap-1.5 text-xs text-gray-500 font-medium bg-[#F4F6F8] px-2.5 py-1 rounded-full border border-gray-200/60" id="last-update-wrap">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#2E5A27] animate-pulse"></span>
                    <span id="last-update">Live</span>
                </span>
                <a href="{{ route('absensi.hari-ini') }}"
                   class="text-xs text-[#FF8533] hover:text-[#EA6D1A] font-bold bg-[#FF8533]/10 hover:bg-[#FF8533]/20 px-3 py-1.5 rounded-xl transition-all flex items-center gap-1.5">
                    <span>Semua Data</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <div id="feed-list" class="divide-y divide-gray-100/70 flex-1">
            @forelse($absensiTerbaru as $absensi)
            <div class="px-5 py-3.5 flex items-center gap-3.5 hover:bg-[#F4F6F8]/60 transition-colors">
                {{-- Foto / Avatar --}}
                <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 ring-2 {{ $absensi->waktu_keluar ? 'ring-[#2E5A27]/40' : 'ring-[#FF8533]/40' }}">
                    @if($absensi->foto_masuk)
                    <img src="{{ Storage::disk('public')->url($absensi->foto_masuk) }}" class="w-full h-full object-cover"
                         onerror="this.outerHTML='<div class=\'w-full h-full flex items-center justify-center text-white text-xs font-bold\' style=\'background:{{ avatarColor($absensi->siswa->nama) }}\'>{{ avatarInitials($absensi->siswa->nama) }}</div>'">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-white text-xs font-bold"
                         style="background:{{ avatarColor($absensi->siswa->nama) }}">{{ avatarInitials($absensi->siswa->nama) }}</div>
                    @endif
                </div>

                {{-- Detail Siswa --}}
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-[#1E1E1E] text-sm truncate">{{ $absensi->siswa->nama }}</p>
                    <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1.5 truncate">
                        <span class="font-medium text-gray-700">{{ $absensi->siswa->kelas }}</span>
                        <span class="text-gray-300">&middot;</span>
                        <span class="font-mono text-gray-400">{{ $absensi->siswa->nis ?? 'NIS —' }}</span>
                        @if($absensi->device_id)
                        <span class="text-gray-300">&middot;</span>
                        <span class="text-[#5C2828] font-bold font-mono text-[10px] bg-[#5C2828]/10 px-1.5 py-0.2 rounded">{{ $absensi->device_id }}</span>
                        @endif
                    </p>
                </div>

                {{-- Jam & Status --}}
                <div class="text-right shrink-0">
                    <p class="text-xs text-gray-700 font-mono font-semibold tabular-nums">
                        {{ $absensi->waktu_masuk?->format('H:i') }}
                        @if($absensi->waktu_keluar)
                            <span class="text-gray-400 mx-0.5">&rarr;</span>{{ $absensi->waktu_keluar->format('H:i') }}
                        @endif
                    </p>
                    <span class="inline-flex items-center gap-1 mt-1 text-[11px] px-2.5 py-0.5 rounded-full font-bold
                        {{ $absensi->waktu_keluar 
                            ? 'bg-[#2E5A27]/10 text-[#2E5A27] border border-[#2E5A27]/25' 
                            : 'bg-[#FF8533]/10 text-[#FF8533] border border-[#FF8533]/25' }}">
                        @if($absensi->waktu_keluar)
                            <i class="fa-solid fa-circle-check text-[10px]"></i> Lengkap
                        @else
                            <i class="fa-solid fa-arrow-right-to-bracket text-[10px]"></i> Hadir
                        @endif
                    </span>
                </div>
            </div>
            @empty
            <div class="py-16 text-center text-gray-400">
                <div class="w-16 h-16 rounded-full bg-[#F4F6F8] flex items-center justify-center mx-auto mb-4 text-gray-400">
                    <i class="fa-solid fa-id-card-clip text-3xl"></i>
                </div>
                <p class="text-sm font-bold text-gray-600">Belum ada absensi hari ini</p>
                <p class="text-xs text-gray-400 mt-1">Menunggu scan kartu RFID dari modul ESP32-S3...</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Kolom Kanan: Kehadiran Per Kelas (1 Kolom) --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden flex flex-col">
        <div class="px-5 py-4 border-b border-gray-100 bg-white">
            <h2 class="font-bold text-[#1E1E1E] text-sm flex items-center gap-2">
                <i class="fa-solid fa-chart-column text-[#FF8533]"></i>
                <span>Kehadiran Per Kelas</span>
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ now()->translatedFormat('d F Y') }}</p>
        </div>

        <div class="p-5 space-y-4 flex-1 overflow-y-auto" id="kelas-list">
            @forelse($perKelas as $kelas => $data)
            @php 
                $pct = $data['total'] > 0 ? round(($data['hadir'] / $data['total']) * 100) : 0; 
                $barColor = $pct >= 80 ? 'bg-[#2E5A27]' : ($pct >= 50 ? 'bg-amber-500' : 'bg-red-500');
                $textColor = $pct >= 80 ? 'text-[#2E5A27]' : ($pct >= 50 ? 'text-amber-600' : 'text-red-600');
            @endphp
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $barColor }}"></span>
                        <span class="text-xs font-bold text-[#1E1E1E]">{{ $kelas }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-400 font-mono">{{ $data['hadir'] }}/{{ $data['total'] }}</span>
                        <span class="text-xs font-black {{ $textColor }}">{{ $pct }}%</span>
                    </div>
                </div>
                <div class="w-full bg-[#F4F6F8] rounded-full h-2 overflow-hidden border border-gray-100">
                    <div class="h-2 rounded-full transition-all duration-500 {{ $barColor }}"
                         style="width:{{ $pct }}%"></div>
                </div>
            </div>
            @empty
            <div class="py-12 text-center">
                <div class="w-12 h-12 rounded-full bg-[#F4F6F8] flex items-center justify-center mx-auto mb-2 text-gray-400">
                    <i class="fa-solid fa-chalkboard text-xl"></i>
                </div>
                <p class="text-sm font-semibold text-gray-500">Belum ada data kelas</p>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
let lastHadir  = {{ $hadirHariIni }};
let lastPulang = {{ $sudahPulang }};

// Live stats polling
async function pollStats() {
    try {
        const [r, feedResp] = await Promise.all([
            fetch('{{ route("dashboard.stats") }}', { headers: { 'Accept': 'application/json' } }),
            fetch('{{ route("dashboard.feed") }}',  { headers: { 'Accept': 'application/json' } }),
        ]);
        if (!r.ok || !feedResp.ok) return;
        const d    = await r.json();
        const feed = await feedResp.json();

        // Update card numbers
        document.getElementById('stat-hadir').textContent      = d.hadir;
        document.getElementById('stat-pulang').textContent     = d.pulang;
        document.getElementById('stat-belum').textContent      = d.belum;
        document.getElementById('stat-hadir-pct').innerHTML  = '<i class="fa-solid fa-arrow-trend-up text-[10px]"></i> ' + d.pct_hadir  + '%';
        document.getElementById('stat-pulang-pct').innerHTML = '<i class="fa-solid fa-circle-check text-[10px]"></i> ' + d.pct_pulang + '%';
        document.getElementById('stat-belum-pct').innerHTML  = '<i class="fa-solid fa-hourglass-half text-[10px]"></i> ' + d.pct_belum  + '%';
        document.getElementById('bar-hadir').style.width       = d.pct_hadir  + '%';
        document.getElementById('bar-pulang').style.width      = d.pct_pulang + '%';
        document.getElementById('bar-belum').style.width       = d.pct_belum  + '%';

        renderFeed(feed);
        renderKelas(d.per_kelas);

        // Toast notifications on new scan
        const masukBaru  = d.hadir  - lastHadir;
        const pulangBaru = d.pulang - lastPulang;
        if (masukBaru > 0)  showToast('info', `${masukBaru} scan masuk baru terdeteksi`);
        if (pulangBaru > 0) showToast('success', `${pulangBaru} scan pulang terkonfirmasi`);
        lastHadir  = d.hadir;
        lastPulang = d.pulang;

        const now = new Date();
        const pad = n => String(n).padStart(2,'0');
        document.getElementById('last-update').textContent = `Update ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
    } catch(e) {}
}

function renderKelas(items) {
    const el = document.getElementById('kelas-list');
    if (!el) return;
    if (!items.length) {
        el.innerHTML = `<div class="py-12 text-center">
            <div class="w-12 h-12 rounded-full bg-[#F4F6F8] flex items-center justify-center mx-auto mb-2 text-gray-400">
                <i class="fa-solid fa-chalkboard text-xl"></i>
            </div>
            <p class="text-sm font-semibold text-gray-500">Belum ada data kelas</p>
        </div>`;
        return;
    }
    const esc = v => String(v).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    el.innerHTML = items.map(k => {
        const pct  = k.total > 0 ? Math.round(k.hadir / k.total * 100) : 0;
        const barColor  = pct >= 80 ? 'bg-[#2E5A27]' : (pct >= 50 ? 'bg-amber-500' : 'bg-red-500');
        const textColor = pct >= 80 ? 'text-[#2E5A27]' : (pct >= 50 ? 'text-amber-600' : 'text-red-600');
        return `
        <div>
            <div class="flex justify-between items-center mb-1.5">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full ${barColor}"></span>
                    <span class="text-xs font-bold text-[#1E1E1E]">${esc(k.kelas)}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400 font-mono">${k.hadir}/${k.total}</span>
                    <span class="text-xs font-black ${textColor}">${pct}%</span>
                </div>
            </div>
            <div class="w-full bg-[#F4F6F8] rounded-full h-2 overflow-hidden border border-gray-100">
                <div class="h-2 rounded-full transition-all duration-500 ${barColor}" style="width:${pct}%"></div>
            </div>
        </div>`;
    }).join('');
}

function renderFeed(items) {
    const el = document.getElementById('feed-list');
    if (!el) return;
    if (!items.length) {
        el.innerHTML = `<div class="py-16 text-center text-gray-400">
            <div class="w-16 h-16 rounded-full bg-[#F4F6F8] flex items-center justify-center mx-auto mb-4 text-gray-400">
                <i class="fa-solid fa-id-card-clip text-3xl"></i>
            </div>
            <p class="text-sm font-bold text-gray-600">Belum ada absensi hari ini</p>
            <p class="text-xs text-gray-400 mt-1">Menunggu scan kartu RFID dari modul ESP32-S3...</p>
        </div>`;
        return;
    }
    const esc = v => String(v).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    el.innerHTML = items.map(a => `
        <div class="px-5 py-3.5 flex items-center gap-3.5 hover:bg-[#F4F6F8]/60 transition-colors">
            <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 ring-2 ${a.waktu_keluar ? 'ring-[#2E5A27]/40' : 'ring-[#FF8533]/40'}">
                ${a.foto_url
                    ? `<img src="${a.foto_url}" class="w-full h-full object-cover">`
                    : `<div class="w-full h-full flex items-center justify-center text-white text-xs font-bold" style="background:${a.avatar_color}">${esc(a.initials)}</div>`
                }
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-[#1E1E1E] text-sm truncate">${esc(a.nama)}</p>
                <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1.5 truncate">
                    <span class="font-medium text-gray-700">${esc(a.kelas)}</span>
                    <span class="text-gray-300">&middot;</span>
                    <span class="font-mono text-gray-400">${esc(a.nis || 'NIS —')}</span>
                    ${a.device_id ? `
                        <span class="text-gray-300">&middot;</span>
                        <span class="text-[#5C2828] font-bold font-mono text-[10px] bg-[#5C2828]/10 px-1.5 py-0.2 rounded">${esc(a.device_id)}</span>
                    ` : ''}
                </p>
            </div>
            <div class="text-right shrink-0">
                <p class="text-xs text-gray-700 font-mono font-semibold tabular-nums">
                    ${esc(a.waktu_masuk || '—')}${a.waktu_keluar ? ' &rarr; ' + esc(a.waktu_keluar) : ''}
                </p>
                <span class="inline-flex items-center gap-1 mt-1 text-[11px] px-2.5 py-0.5 rounded-full font-bold ${
                    a.waktu_keluar 
                        ? 'bg-[#2E5A27]/10 text-[#2E5A27] border border-[#2E5A27]/25' 
                        : 'bg-[#FF8533]/10 text-[#FF8533] border border-[#FF8533]/25'
                }">
                    ${a.waktu_keluar 
                        ? '<i class="fa-solid fa-circle-check text-[10px]"></i> Lengkap' 
                        : '<i class="fa-solid fa-arrow-right-to-bracket text-[10px]"></i> Hadir'}
                </span>
            </div>
        </div>
    `).join('');
}

setInterval(pollStats, 5000);

// Device monitoring polling
async function pollDevices() {
    try {
        const r = await fetch('{{ route("dashboard.devices") }}');
        if (!r.ok) return;
        const devices = await r.json();
        renderDevices(devices);
        const now = new Date();
        const pad = n => String(n).padStart(2,'0');
        const el = document.getElementById('device-last-update');
        if (el) el.innerHTML = `<i class="fa-regular fa-clock text-xs"></i> Update ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
    } catch(e) {}
}

function renderDevices(devices) {
    const el = document.getElementById('device-grid');
    if (!el) return;
    if (!devices.length) {
        el.innerHTML = `<div class="col-span-full bg-[#F4F6F8] rounded-xl border border-dashed border-gray-300 py-8 text-center">
            <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center mx-auto mb-3 shadow-2xs text-gray-400">
                <i class="fa-solid fa-video-slash text-xl"></i>
            </div>
            <p class="text-sm font-bold text-gray-600">Belum ada perangkat terdaftar</p>
        </div>`;
        return;
    }
    const cfg = {
        online:  { dot: 'bg-[#2E5A27]', badge: 'bg-[#2E5A27]/10 text-[#2E5A27] border-[#2E5A27]/30', icon: 'fa-circle-check', label: 'Online',  ping: true  },
        warning: { dot: 'bg-amber-400',   badge: 'bg-amber-50 text-amber-700 border-amber-200',       icon: 'fa-triangle-exclamation', label: 'Lambat',  ping: true  },
        offline: { dot: 'bg-red-400',     badge: 'bg-red-50 text-red-600 border-red-200',             icon: 'fa-circle-xmark', label: 'Offline', ping: false },
    };
    const esc = v => String(v).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    el.innerHTML = devices.map(d => {
        const c    = cfg[d.status] || cfg.offline;
        const nama = d.nama || ('Ruang ' + d.device_id.replace(/^R/i, ''));
        return `
        <div class="bg-[#F4F6F8] rounded-xl p-3.5 border border-gray-200/80 hover:bg-white hover:border-[#FF8533]/40 hover:shadow-sm transition-all group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-[#5C2828] bg-white px-2 py-0.5 rounded-md font-mono border border-gray-200 tracking-wider">
                    ${esc(d.device_id)}
                </span>
                <div class="relative shrink-0 flex items-center justify-center">
                    <span class="w-2 h-2 rounded-full ${c.dot} block"></span>
                    ${c.ping ? `<span class="w-2 h-2 rounded-full ${c.dot} block absolute inset-0 animate-ping opacity-60"></span>` : ''}
                </div>
            </div>
            <p class="text-xs font-bold text-[#1E1E1E] truncate">${esc(nama)}</p>
            <p class="text-[10px] text-gray-500 mt-0.5 font-mono truncate flex items-center gap-1">
                <i class="fa-solid fa-network-wired text-[9px] text-gray-400"></i>
                ${esc(d.ip || '—.—.—.—')}
            </p>
            <div class="mt-2.5 pt-2 border-t border-gray-200/60 flex items-center justify-between gap-1">
                <span class="text-[10px] px-2 py-0.5 rounded-full border font-bold flex items-center gap-1 ${c.badge}">
                    <i class="fa-solid ${c.icon} text-[9px]"></i>
                    ${c.label}
                </span>
                <span class="text-[10px] text-gray-400 font-mono shrink-0">${esc(d.last_seen || '—')}</span>
            </div>
        </div>`;
    }).join('');
}

pollDevices();
setInterval(pollDevices, 15000);
</script>
@endpush
