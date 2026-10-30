<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ $namaSekolah }}</title>
    <link rel="icon" href="{{ asset('images/logo-sekolah.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    {{-- Font Awesome dipertahankan agar ikon halaman lain tetap berfungsi selama migrasi bertahap. --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui'] } } } };
    </script>
    <style>
        #sidebar { transition: transform .25s cubic-bezier(.4,0,.2,1); }
        @media(max-width:1023px) { #sidebar { transform: translateX(-100%); } #sidebar.open { transform: translateX(0); } }
        .toast { animation: slideIn .25s ease-out; }
        @keyframes slideIn { from { opacity:0; transform:translateX(1rem); } to { opacity:1; transform:translateX(0); } }
        .sort-link { display:inline-flex; align-items:center; gap:.25rem; cursor:pointer; user-select:none; }
        .sort-link:hover, th.sortable:hover { color:#2563eb; }
        #sidebar-nav::-webkit-scrollbar { width:5px; }
        #sidebar-nav::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:4px; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">
<div id="overlay" class="fixed inset-0 z-30 hidden bg-slate-950/40 lg:hidden" onclick="toggleSidebar()"></div>

<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col border-r border-slate-200 bg-white">
    <div class="flex items-center justify-between gap-3 border-b border-slate-200 px-5 py-5">
        <div class="flex min-w-0 items-center gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-slate-200 bg-white p-1">
                <img src="{{ asset('images/logo-sekolah.png') }}" alt="{{ $namaSekolah }}" class="h-full w-full object-contain">
            </div>
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-slate-900">{{ $namaSekolah }}</p>
                <p class="mt-0.5 text-xs text-slate-500">Sistem Absensi</p>
            </div>
        </div>
        <button onclick="toggleSidebar()" aria-label="Tutup navigasi" class="rounded-md p-2 text-slate-500 hover:bg-slate-100 lg:hidden">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m6 6 12 12M18 6 6 18" stroke-width="1.8" stroke-linecap="round"/></svg>
        </button>
    </div>

    <nav id="sidebar-nav" class="flex-1 space-y-6 overflow-y-auto px-3 py-5">
        <div class="space-y-1">
            <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Menu Utama</p>
            @php $isDash = request()->routeIs('dashboard'); @endphp
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ $isDash ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 13h8V3H3v10Zm10 8h8V11h-8v10ZM3 21h8v-6H3v6Zm10-12h8V3h-8v6Z" stroke-width="1.7" stroke-linejoin="round"/></svg>Dashboard
            </a>
        </div>
        <div class="space-y-1">
            <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Absensi Siswa</p>
            @php $isHariIni = request()->routeIs('absensi.hari-ini'); @endphp
            <a href="{{ route('absensi.hari-ini') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ $isHariIni ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="5" width="18" height="16" rx="2" stroke-width="1.7"/><path d="M16 3v4M8 3v4M3 10h18m-13 5 2 2 4-4" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>Hari Ini
            </a>
            @php $isRekap = request()->routeIs('absensi.rekap'); @endphp
            <a href="{{ route('absensi.rekap') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ $isRekap ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 19V5m0 14h16M8 15v-4m4 4V7m4 8v-6m4 6V9" stroke-width="1.7" stroke-linecap="round"/></svg>Rekap Kehadiran
            </a>
        </div>
        @auth
        <div class="space-y-1">
            <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Master Data</p>
            @php $isSiswa = request()->routeIs('siswa.*'); @endphp
            <a href="{{ route('siswa.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ $isSiswa ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"><svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M16 20v-1.5a4.5 4.5 0 0 0-4.5-4.5h-3A4.5 4.5 0 0 0 4 18.5V20m6-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm10 10v-1.5a4.5 4.5 0 0 0-3-4.25M16 2.2a4 4 0 0 1 0 7.6" stroke-width="1.7" stroke-linecap="round"/></svg>Data Siswa</a>
            @php $isKelas = request()->routeIs('kelas.*'); @endphp
            <a href="{{ route('kelas.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ $isKelas ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"><svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 9h.01M15 9h.01" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>Kelas</a>
            @php $isJurusan = request()->routeIs('jurusan.*'); @endphp
            <a href="{{ route('jurusan.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ $isJurusan ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"><svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 3 3 8l9 5 9-5-9-5Zm-9 9 9 5 9-5m-18 5 9 5 9-5" stroke-width="1.7" stroke-linejoin="round"/></svg>Jurusan</a>
        </div>
        <div class="space-y-1">
            <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Log Sistem</p>
            @php $isRfidLog = request()->routeIs('rfid-log.*'); @endphp
            <a href="{{ route('rfid-log.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ $isRfidLog ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"><svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 8v4l3 2m6-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" stroke-width="1.7" stroke-linecap="round"/></svg>Log UID Kartu</a>
        </div>
        @endauth
        @admin
        <div class="space-y-1">
            <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Pengaturan</p>
            @php $isPengaturan = request()->routeIs('pengaturan.*'); @endphp
            <a href="{{ route('pengaturan.edit') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ $isPengaturan ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"><svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z" stroke-width="1.7"/><path d="m19.4 15 .1.1a1.8 1.8 0 1 1-2.5 2.5l-.1-.1a1.8 1.8 0 0 0-3 .9v.2a1.8 1.8 0 1 1-3.6 0v-.2a1.8 1.8 0 0 0-3-.9l-.1.1a1.8 1.8 0 1 1-2.5-2.5l.1-.1a1.8 1.8 0 0 0-.9-3h-.2a1.8 1.8 0 1 1 0-3.6h.2a1.8 1.8 0 0 0 .9-3l-.1-.1a1.8 1.8 0 1 1 2.5-2.5l.1.1a1.8 1.8 0 0 0 3-.9v-.2a1.8 1.8 0 1 1 3.6 0v.2a1.8 1.8 0 0 0 3 .9l.1-.1a1.8 1.8 0 1 1 2.5 2.5l-.1.1a1.8 1.8 0 0 0 .9 3h.2a1.8 1.8 0 1 1 0 3.6h-.2a1.8 1.8 0 0 0-.9 3Z" stroke-width="1.4" stroke-linecap="round"/></svg>Pengaturan Web</a>
            @php $isPengguna = request()->routeIs('pengguna.*'); @endphp
            <a href="{{ route('pengguna.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ $isPengguna ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"><svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M16 20v-1.5a4.5 4.5 0 0 0-4.5-4.5h-3A4.5 4.5 0 0 0 4 18.5V20m6-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm10 10v-1.5a4.5 4.5 0 0 0-3-4.25M16 2.2a4 4 0 0 1 0 7.6" stroke-width="1.7" stroke-linecap="round"/></svg>Kelola Pengguna</a>
        </div>
        @endadmin
    </nav>

    <div class="space-y-2 border-t border-slate-200 p-3">
        @admin
        <div class="flex items-center gap-2">
            <a href="http://{{ request()->getHost() }}:3001" target="_blank" id="wa-status-card" class="flex min-w-0 flex-1 items-center gap-3 rounded-lg border border-slate-200 px-3 py-2.5 hover:bg-slate-50">
                <span class="relative flex h-2.5 w-2.5 shrink-0"><span id="wa-dot" class="block h-2.5 w-2.5 rounded-full bg-slate-400"></span><span id="wa-dot-ping" class="absolute inset-0 block h-2.5 w-2.5 rounded-full opacity-0"></span></span>
                <span class="min-w-0 flex-1"><span id="wa-label" class="block truncate text-xs font-semibold text-slate-800">WhatsApp</span><span id="wa-sub" class="mt-0.5 block truncate text-[11px] text-slate-500">Memeriksa...</span></span>
                <svg class="h-4 w-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 4h6v6m0-6-9 9M18 13v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h6" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
            <button id="wa-disconnect-btn" onclick="waDisconnect()" title="Putuskan WhatsApp" class="hidden flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-red-200 text-red-600 hover:bg-red-50"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 2v10m6.4-6.4a9 9 0 1 1-12.8 0" stroke-width="1.8" stroke-linecap="round"/></svg></button>
        </div>
        @endadmin
        <div class="flex items-center gap-3 rounded-lg bg-slate-50 px-3 py-2.5">
            <span class="h-2 w-2 shrink-0 rounded-full bg-emerald-500"></span>
            <span class="min-w-0"><span class="block text-xs font-semibold text-slate-800">Server Aktif</span><span class="mt-0.5 block truncate text-[11px] text-slate-500">FrankenPHP · PostgreSQL</span></span>
        </div>
    </div>
</aside>

<div class="flex min-h-screen flex-col lg:ml-64">
    <header class="sticky top-0 z-20 border-b border-slate-200 bg-white">
        <div class="flex min-h-16 items-center justify-between gap-3 px-4 py-3 sm:px-6">
            <div class="flex min-w-0 items-center gap-3">
                <button onclick="toggleSidebar()" aria-label="Buka navigasi" class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 6h16M4 12h16M4 18h16" stroke-width="1.8" stroke-linecap="round"/></svg></button>
                <nav class="flex min-w-0 items-center gap-2 text-sm">
                    <a href="{{ route('dashboard') }}" aria-label="Dashboard" class="shrink-0 text-slate-400 hover:text-blue-600"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1V10Z" stroke-width="1.7" stroke-linejoin="round"/></svg></a>
                    @hasSection('breadcrumb')<svg class="h-4 w-4 shrink-0 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m9 18 6-6-6-6" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>@yield('breadcrumb')@endif
                </nav>
            </div>
            <div class="flex shrink-0 items-center gap-2 sm:gap-4">
                <div class="hidden items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 sm:flex"><svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="9" stroke-width="1.7"/><path d="M12 7v5l3 2" stroke-width="1.7" stroke-linecap="round"/></svg><span id="clock" class="font-mono text-xs font-medium tabular-nums text-slate-700">{{ now()->format('H:i:s') }}</span></div>
                <div class="hidden items-center gap-2 text-xs text-slate-500 md:flex"><svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="5" width="18" height="16" rx="2" stroke-width="1.7"/><path d="M16 3v4M8 3v4M3 10h18" stroke-width="1.7" stroke-linecap="round"/></svg>{{ now()->translatedFormat('d F Y') }}</div>
                @auth
                <div class="flex items-center gap-3 border-l border-slate-200 pl-3 sm:pl-4">
                    <div class="hidden items-center gap-2 sm:flex"><div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-xs font-semibold text-blue-700">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div><div class="max-w-40 leading-tight"><p class="truncate text-xs font-semibold text-slate-800">{{ auth()->user()->name }}</p><p class="mt-0.5 text-[11px] text-slate-500">{{ auth()->user()->roleLabel() }}</p></div></div>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" title="Keluar dari sistem" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M10 17l5-5-5-5m5 5H3m9-9h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg><span class="hidden sm:inline">Keluar</span></button></form>
                </div>
                @else
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-blue-700"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4m-4-4 5-5-5-5m5 5H3" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg><span>Masuk</span></a>
                @endauth
            </div>
        </div>
    </header>
    <main class="mx-auto w-full max-w-screen-2xl flex-1 p-4 sm:p-6">@yield('content')</main>
    <footer class="border-t border-slate-200 bg-white px-4 py-4 text-center text-xs text-slate-500 sm:px-6"><div class="flex flex-wrap items-center justify-center gap-2"><img src="{{ asset('images/logo-sekolah.png') }}" alt="{{ $namaSekolah }}" class="h-4 w-4 object-contain"><span class="font-medium text-slate-700">{{ $namaSekolah }}</span><span aria-hidden="true">·</span><span>Sistem Absensi SMKS Nurul Amalia</span></div></footer>
</div>
<div id="toastContainer" class="pointer-events-none fixed right-4 top-4 z-50 flex flex-col gap-2"></div>
@if(session('success'))<script>window._toasts = window._toasts||[]; window._toasts.push({type:'success',msg:{{ Js::from(session('success')) }}});</script>@endif
@if(session('error'))<script>window._toasts = window._toasts||[]; window._toasts.push({type:'error',msg:{{ Js::from(session('error')) }}});</script>@endif
<script>
function toggleSidebar() { document.getElementById('sidebar').classList.toggle('open'); document.getElementById('overlay').classList.toggle('hidden'); }
setInterval(() => { const n=new Date(), p=x=>String(x).padStart(2,'0'), el=document.getElementById('clock'); if(el) el.textContent=`${p(n.getHours())}:${p(n.getMinutes())}:${p(n.getSeconds())}`; }, 1000);
function showToast(type, msg, duration=4000) {
    const c=document.getElementById('toastContainer'); if(!c) return;
    const colors={success:'border-emerald-200 bg-white text-slate-800',error:'border-red-200 bg-white text-slate-800',info:'border-blue-200 bg-white text-slate-800',warning:'border-amber-200 bg-white text-slate-800'};
    const icons={success:'<svg class="h-5 w-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m5 12 4 4L19 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',error:'<svg class="h-5 w-5 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="9" stroke-width="1.8"/><path d="m9 9 6 6m0-6-6 6" stroke-width="1.8" stroke-linecap="round"/></svg>',info:'<svg class="h-5 w-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="9" stroke-width="1.8"/><path d="M12 11v5m0-8h.01" stroke-width="1.8" stroke-linecap="round"/></svg>',warning:'<svg class="h-5 w-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 3 2.8 19h18.4L12 3Z" stroke-width="1.8" stroke-linejoin="round"/><path d="M12 9v4m0 3h.01" stroke-width="1.8" stroke-linecap="round"/></svg>'};
    const t=document.createElement('div'); t.className=`toast pointer-events-auto flex max-w-sm items-start gap-3 rounded-lg border px-4 py-3 text-sm shadow-sm ${colors[type]||colors.info}`;
    t.innerHTML=`<span class="mt-0.5 shrink-0">${icons[type]||icons.info}</span><span class="flex-1 leading-snug"></span><button aria-label="Tutup" class="ml-1 shrink-0 p-0.5 text-slate-400 hover:text-slate-700"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m6 6 12 12M18 6 6 18" stroke-width="1.8" stroke-linecap="round"/></svg></button>`; t.querySelector('span.flex-1').textContent=msg; t.querySelector('button').onclick=()=>t.remove(); c.appendChild(t); setTimeout(()=>t.remove(),duration);
}
(window._toasts||[]).forEach(({type,msg})=>showToast(type,msg)); window.showToast=showToast;
@admin
(function(){const dot=document.getElementById('wa-dot'), ping=document.getElementById('wa-dot-ping'), label=document.getElementById('wa-label'), sub=document.getElementById('wa-sub'), btn=document.getElementById('wa-disconnect-btn'); if(!dot)return;
function state(color,pingColor,title,detail,connected){dot.className=`block h-2.5 w-2.5 rounded-full ${color}`;ping.className=`absolute inset-0 block h-2.5 w-2.5 rounded-full ${pingColor} opacity-60 animate-ping`;label.textContent=title;sub.textContent=detail;if(btn)btn.classList.toggle('hidden',!connected)}
function poll(){fetch('{{ route("wa-gateway.status") }}').then(r=>r.json()).then(d=>{if(!d.online)state('bg-red-500','bg-red-500','WA Tidak Aktif','Gateway offline',false);else if(d.connected)state('bg-emerald-500','bg-emerald-400','WA Terhubung','Siap kirim notifikasi',true);else if(d.qr)state('bg-blue-600','bg-blue-500','Scan QR Code','Klik untuk buka scanner',false);else state('bg-amber-500','bg-amber-400','WA Menghubungkan','Menunggu koneksi...',false)}).catch(()=>state('bg-red-500','bg-red-500','WA Tidak Aktif','Gateway offline',false))} poll();setInterval(poll,15000)})();
async function waDisconnect(){if(!confirm('Putuskan koneksi WhatsApp? Sesi akan dihapus dan perlu scan QR ulang.'))return;const b=document.getElementById('wa-disconnect-btn');b.disabled=true;b.classList.add('opacity-50');try{const r=await fetch('{{ route("wa-gateway.disconnect") }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}});const d=await r.json();if(d.success)showToast('success','WhatsApp berhasil diputus');else showToast('error',d.message||'Gagal memutus WhatsApp')}catch(e){showToast('error','Gagal menghubungi gateway')}finally{b.disabled=false;b.classList.remove('opacity-50')}}
@endadmin
</script>
@stack('scripts')
</body>
</html>
