<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ $namaSekolah }}</title>
    {{-- School Identity Favicon --}}
    <link rel="icon" href="{{ asset('images/logo-sekolah.png') }}" type="image/png">
    
    {{-- FontAwesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    {{-- Tailwind CSS CDN & Custom Config --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#FF8533',
                            50: '#FFF7ED',
                            100: '#FFEDD5',
                            200: '#FED7AA',
                            300: '#FDBA74',
                            400: '#FB923C',
                            500: '#FF8533',
                            600: '#EA6D1A',
                            700: '#C85207',
                            800: '#9E3E04',
                            900: '#7B3105',
                        },
                        secondary: {
                            DEFAULT: '#5C2828',
                            50: '#FBF5F5',
                            100: '#F5E6E6',
                            200: '#E6C6C6',
                            300: '#D29E9E',
                            400: '#B87272',
                            500: '#8A4545',
                            600: '#6F3131',
                            700: '#5C2828',
                            800: '#471E1E',
                            900: '#341515',
                        },
                        accent: {
                            DEFAULT: '#2E5A27',
                            50: '#F2F8F1',
                            100: '#E1EFE0',
                            200: '#C1DFC0',
                            300: '#97C895',
                            400: '#64A961',
                            500: '#2E5A27',
                            600: '#254A20',
                            700: '#1D3B19',
                            800: '#152C13',
                            900: '#0E1E0D',
                        },
                        canvas: '#F4F6F8',
                        darktext: '#1E1E1E',
                    }
                }
            }
        }
    </script>
    <style>
        #sidebar { transition: transform .25s cubic-bezier(.4,0,.2,1); }
        @media(max-width:1023px) { #sidebar { transform: translateX(-100%); } #sidebar.open { transform: translateX(0); } }
        .toast { animation: slideIn .3s cubic-bezier(.16,1,.3,1); }
        @keyframes slideIn { from { opacity:0; transform:translateX(2rem); } to { opacity:1; transform:translateX(0); } }
        .sort-link { display:inline-flex; align-items:center; gap:.25rem; cursor:pointer; user-select:none; }
        .sort-link:hover, th.sortable:hover { color:#FF8533; }
        
        /* Modern scrollbar for sidebar */
        #sidebar-nav::-webkit-scrollbar { width: 5px; }
        #sidebar-nav::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); }
        #sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }
        #sidebar-nav::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.25); }
    </style>
    @stack('styles')
</head>
<body class="bg-[#F4F6F8] text-[#1E1E1E] min-h-screen font-sans antialiased" x-data="{ sidebarOpen: false }">

{{-- Overlay mobile --}}
<div id="overlay" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>

{{-- ── Sidebar: Merah Kecokelatan (#5C2828) ── --}}
<aside id="sidebar" class="w-64 min-h-screen bg-[#5C2828] flex flex-col fixed top-0 left-0 z-40 shadow-2xl border-r border-[#471E1E]">

    {{-- Sidebar Brand Header --}}
    <div class="px-5 py-5 border-b border-white/10 flex items-center justify-between gap-2">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-10 h-10 rounded-xl bg-white p-1 flex items-center justify-center shadow-md shadow-black/20 shrink-0 overflow-hidden border border-white/20">
                <img src="{{ asset('images/logo-sekolah.png') }}" alt="{{ $namaSekolah }}" class="w-full h-full object-contain">
            </div>
            <div class="min-w-0">
                <p class="text-white font-bold text-sm leading-tight truncate tracking-tight">{{ $namaSekolah }}</p>
                <p class="text-rose-200/70 text-xs mt-0.5 flex items-center gap-1.5 font-medium">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#FF8533]"></span>
                    ESP32-CAM · RFID
                </p>
            </div>
        </div>
        <button onclick="toggleSidebar()" class="lg:hidden text-rose-200/70 hover:text-white transition-colors p-1.5 rounded-lg hover:bg-white/10">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    {{-- Sidebar Nav --}}
    <nav id="sidebar-nav" class="flex-1 px-3 py-4 overflow-y-auto space-y-1">

        {{-- Section: Utama --}}
        <p class="text-rose-200/50 text-[10px] font-bold uppercase tracking-widest px-3 mb-2 mt-1 select-none flex items-center gap-1.5">
            <i class="fa-solid fa-house-chimney text-[9px] opacity-70"></i>
            <span>Menu Utama</span>
        </p>

        @php $isDash = request()->routeIs('dashboard'); @endphp
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group
                  {{ $isDash ? 'bg-[#FF8533] text-white font-semibold shadow-md shadow-black/20' : 'text-rose-100/80 hover:bg-white/10 hover:text-white' }}">
            <span class="w-8 h-8 flex items-center justify-center rounded-lg shrink-0 transition-colors
                         {{ $isDash ? 'bg-white/20 text-white' : 'bg-white/5 text-rose-200/70 group-hover:bg-white/15 group-hover:text-white' }}">
                <i class="fa-solid fa-chart-pie text-sm"></i>
            </span>
            <span>Dashboard</span>
        </a>

        {{-- Section: Absensi --}}
        <p class="text-rose-200/50 text-[10px] font-bold uppercase tracking-widest px-3 mb-2 mt-6 select-none flex items-center gap-1.5">
            <i class="fa-solid fa-clipboard-user text-[9px] opacity-70"></i>
            <span>Absensi Siswa</span>
        </p>

        @php $isHariIni = request()->routeIs('absensi.hari-ini'); @endphp
        <a href="{{ route('absensi.hari-ini') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group
                  {{ $isHariIni ? 'bg-[#FF8533] text-white font-semibold shadow-md shadow-black/20' : 'text-rose-100/80 hover:bg-white/10 hover:text-white' }}">
            <span class="w-8 h-8 flex items-center justify-center rounded-lg shrink-0 transition-colors
                         {{ $isHariIni ? 'bg-white/20 text-white' : 'bg-white/5 text-rose-200/70 group-hover:bg-white/15 group-hover:text-white' }}">
                <i class="fa-solid fa-calendar-check text-sm"></i>
            </span>
            <span>Hari Ini</span>
        </a>

        @php $isRekap = request()->routeIs('absensi.rekap'); @endphp
        <a href="{{ route('absensi.rekap') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group
                  {{ $isRekap ? 'bg-[#FF8533] text-white font-semibold shadow-md shadow-black/20' : 'text-rose-100/80 hover:bg-white/10 hover:text-white' }}">
            <span class="w-8 h-8 flex items-center justify-center rounded-lg shrink-0 transition-colors
                         {{ $isRekap ? 'bg-white/20 text-white' : 'bg-white/5 text-rose-200/70 group-hover:bg-white/15 group-hover:text-white' }}">
                <i class="fa-solid fa-clipboard-list text-sm"></i>
            </span>
            <span>Rekap Kehadiran</span>
        </a>

        @auth
        {{-- Section: Master Data --}}
        <p class="text-rose-200/50 text-[10px] font-bold uppercase tracking-widest px-3 mb-2 mt-6 select-none flex items-center gap-1.5">
            <i class="fa-solid fa-database text-[9px] opacity-70"></i>
            <span>Master Data</span>
        </p>

        @php $isSiswa = request()->routeIs('siswa.*'); @endphp
        <a href="{{ route('siswa.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group
                  {{ $isSiswa ? 'bg-[#FF8533] text-white font-semibold shadow-md shadow-black/20' : 'text-rose-100/80 hover:bg-white/10 hover:text-white' }}">
            <span class="w-8 h-8 flex items-center justify-center rounded-lg shrink-0 transition-colors
                         {{ $isSiswa ? 'bg-white/20 text-white' : 'bg-white/5 text-rose-200/70 group-hover:bg-white/15 group-hover:text-white' }}">
                <i class="fa-solid fa-user-graduate text-sm"></i>
            </span>
            <span>Data Siswa</span>
        </a>

        @php $isKelas = request()->routeIs('kelas.*'); @endphp
        <a href="{{ route('kelas.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group
                  {{ $isKelas ? 'bg-[#FF8533] text-white font-semibold shadow-md shadow-black/20' : 'text-rose-100/80 hover:bg-white/10 hover:text-white' }}">
            <span class="w-8 h-8 flex items-center justify-center rounded-lg shrink-0 transition-colors
                         {{ $isKelas ? 'bg-white/20 text-white' : 'bg-white/5 text-rose-200/70 group-hover:bg-white/15 group-hover:text-white' }}">
                <i class="fa-solid fa-chalkboard-user text-sm"></i>
            </span>
            <span>Kelas</span>
        </a>

        @php $isJurusan = request()->routeIs('jurusan.*'); @endphp
        <a href="{{ route('jurusan.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group
                  {{ $isJurusan ? 'bg-[#FF8533] text-white font-semibold shadow-md shadow-black/20' : 'text-rose-100/80 hover:bg-white/10 hover:text-white' }}">
            <span class="w-8 h-8 flex items-center justify-center rounded-lg shrink-0 transition-colors
                         {{ $isJurusan ? 'bg-white/20 text-white' : 'bg-white/5 text-rose-200/70 group-hover:bg-white/15 group-hover:text-white' }}">
                <i class="fa-solid fa-layer-group text-sm"></i>
            </span>
            <span>Jurusan</span>
        </a>

        {{-- Section: Log --}}
        <p class="text-rose-200/50 text-[10px] font-bold uppercase tracking-widest px-3 mb-2 mt-6 select-none flex items-center gap-1.5">
            <i class="fa-solid fa-clock-rotate-left text-[9px] opacity-70"></i>
            <span>Log Sistem</span>
        </p>

        @php $isRfidLog = request()->routeIs('rfid-log.*'); @endphp
        <a href="{{ route('rfid-log.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group
                  {{ $isRfidLog ? 'bg-[#FF8533] text-white font-semibold shadow-md shadow-black/20' : 'text-rose-100/80 hover:bg-white/10 hover:text-white' }}">
            <span class="w-8 h-8 flex items-center justify-center rounded-lg shrink-0 transition-colors
                         {{ $isRfidLog ? 'bg-white/20 text-white' : 'bg-white/5 text-rose-200/70 group-hover:bg-white/15 group-hover:text-white' }}">
                <i class="fa-solid fa-id-card text-sm"></i>
            </span>
            <span>Log UID Kartu</span>
        </a>
        @endauth

        @admin
        {{-- Section: Sistem --}}
        <p class="text-rose-200/50 text-[10px] font-bold uppercase tracking-widest px-3 mb-2 mt-6 select-none flex items-center gap-1.5">
            <i class="fa-solid fa-gear text-[9px] opacity-70"></i>
            <span>Pengaturan</span>
        </p>

        @php $isPengaturan = request()->routeIs('pengaturan.*'); @endphp
        <a href="{{ route('pengaturan.edit') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group
                  {{ $isPengaturan ? 'bg-[#FF8533] text-white font-semibold shadow-md shadow-black/20' : 'text-rose-100/80 hover:bg-white/10 hover:text-white' }}">
            <span class="w-8 h-8 flex items-center justify-center rounded-lg shrink-0 transition-colors
                         {{ $isPengaturan ? 'bg-white/20 text-white' : 'bg-white/5 text-rose-200/70 group-hover:bg-white/15 group-hover:text-white' }}">
                <i class="fa-solid fa-sliders text-sm"></i>
            </span>
            <span>Pengaturan Web</span>
        </a>

        @php $isPengguna = request()->routeIs('pengguna.*'); @endphp
        <a href="{{ route('pengguna.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group
                  {{ $isPengguna ? 'bg-[#FF8533] text-white font-semibold shadow-md shadow-black/20' : 'text-rose-100/80 hover:bg-white/10 hover:text-white' }}">
            <span class="w-8 h-8 flex items-center justify-center rounded-lg shrink-0 transition-colors
                         {{ $isPengguna ? 'bg-white/20 text-white' : 'bg-white/5 text-rose-200/70 group-hover:bg-white/15 group-hover:text-white' }}">
                <i class="fa-solid fa-user-shield text-sm"></i>
            </span>
            <span>Kelola Pengguna</span>
        </a>
        @endadmin

    </nav>

    {{-- Sidebar Footer Widgets --}}
    <div class="px-3 py-3.5 border-t border-white/10 space-y-2 bg-[#471E1E]/40">

        @admin
        {{-- WA Gateway Status --}}
        <div class="flex items-center gap-2">
            <a href="http://{{ request()->getHost() }}:3001" target="_blank" id="wa-status-card"
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-white/5 hover:bg-white/10 transition-colors cursor-pointer group flex-1 min-w-0 border border-white/5">
                <div class="relative shrink-0 flex items-center justify-center">
                    <span id="wa-dot" class="w-2.5 h-2.5 rounded-full bg-slate-400 block"></span>
                    <span id="wa-dot-ping" class="w-2.5 h-2.5 rounded-full block absolute inset-0 opacity-0"></span>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-1.5">
                        <i class="fa-brands fa-whatsapp text-emerald-400 text-xs"></i>
                        <p id="wa-label" class="text-rose-100 text-xs font-semibold leading-tight truncate">WhatsApp</p>
                    </div>
                    <p id="wa-sub" class="text-rose-200/60 text-[10px] mt-0.5 truncate">Memeriksa...</p>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-rose-200/40 group-hover:text-white shrink-0 transition-colors"></i>
            </a>
            {{-- Disconnect button --}}
            <button id="wa-disconnect-btn"
                    onclick="waDisconnect()"
                    title="Putuskan WhatsApp"
                    class="hidden w-8 h-8 shrink-0 flex items-center justify-center rounded-xl bg-red-500/20 hover:bg-red-500/35 text-red-300 hover:text-white transition-colors border border-red-500/30">
                <i class="fa-solid fa-power-off text-xs"></i>
            </button>
        </div>
        @endadmin

        {{-- Server Aktif (Accent Forest Green Indicator #2E5A27) --}}
        <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-white/5 border border-white/5">
            <div class="relative shrink-0 flex items-center justify-center">
                <span class="w-2.5 h-2.5 rounded-full bg-[#2E5A27] block"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 block absolute inset-0 animate-ping opacity-60"></span>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-1.5">
                    <i class="fa-solid fa-server text-rose-200/60 text-xs"></i>
                    <p class="text-rose-100 text-xs font-semibold leading-tight">Server Aktif</p>
                </div>
                <p class="text-rose-200/60 text-[10px] mt-0.5 truncate">FrankenPHP · PostgreSQL</p>
            </div>
        </div>
    </div>
</aside>

{{-- ── Main Content Area ── --}}
<div class="lg:ml-64 flex flex-col min-h-screen">

    {{-- Topbar: Putih Bersih (#FFFFFF) dengan aksen garis Oranye Terang (#FF8533) di bawah --}}
    <header class="bg-white border-b-2 border-[#FF8533] shadow-xs sticky top-0 z-20">
        <div class="px-4 lg:px-6 py-3 flex items-center justify-between gap-3">
            
            <div class="flex items-center gap-3 min-w-0">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-[#5C2828] transition-colors">
                    <i class="fa-solid fa-bars text-base"></i>
                </button>

                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-1.5 text-sm min-w-0">
                    <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-[#FF8533] transition-colors shrink-0 flex items-center gap-1">
                        <i class="fa-solid fa-house text-xs"></i>
                    </a>
                    @hasSection('breadcrumb')
                        <i class="fa-solid fa-chevron-right text-[10px] text-gray-300 shrink-0"></i>
                        @yield('breadcrumb')
                    @endif
                </nav>
            </div>

            {{-- Right info: Clock, Date, User & Actions --}}
            <div class="flex items-center gap-2.5 shrink-0">
                {{-- Clock --}}
                <div class="hidden sm:flex items-center gap-2 bg-[#F4F6F8] border border-gray-200/80 rounded-xl px-3 py-1.5 shadow-2xs">
                    <i class="fa-regular fa-clock text-[#FF8533] text-xs"></i>
                    <span id="clock" class="text-xs font-mono font-semibold text-[#1E1E1E] tabular-nums">{{ now()->format('H:i:s') }}</span>
                </div>

                {{-- Date --}}
                <div class="hidden md:flex items-center gap-1.5 text-xs text-gray-500 font-medium px-2 py-1.5">
                    <i class="fa-regular fa-calendar-days text-gray-400 text-xs"></i>
                    <span>{{ now()->translatedFormat('d F Y') }}</span>
                </div>

                @auth
                <div class="flex items-center gap-2 pl-2 ml-1 border-l border-gray-200">
                    <div class="hidden sm:flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-[#5C2828]/10 text-[#5C2828] flex items-center justify-center font-bold text-xs">
                            <i class="fa-solid fa-circle-user text-base"></i>
                        </div>
                        <div class="text-left leading-tight">
                            <p class="text-xs font-bold text-[#1E1E1E] truncate max-w-[10rem]">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-gray-400 font-medium">{{ auth()->user()->roleLabel() }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Keluar dari sistem"
                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-gray-600 border border-gray-200 hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-colors">
                            <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
                @else
                <a href="{{ route('login') }}"
                   class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-[#FF8533] text-white hover:bg-[#EA6D1A] transition-all shadow-sm shadow-[#FF8533]/30">
                    <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                    <span>Masuk</span>
                </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Page Content Canvas: Abu-abu Kebiruan Terang (#F4F6F8) --}}
    <main class="flex-1 p-4 lg:p-6 max-w-screen-2xl w-full mx-auto">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="px-6 py-4 text-center text-xs text-gray-500 border-t border-gray-200/70 bg-white/40">
        <div class="flex items-center justify-center gap-2 flex-wrap">
            <img src="{{ asset('images/logo-sekolah.png') }}" alt="{{ $namaSekolah }}" class="w-4 h-4 object-contain inline-block">
            <span class="font-semibold text-gray-700">{{ $namaSekolah }}</span>
            <span>&middot;</span>
            <span class="flex items-center gap-1.5 text-gray-500">
                <i class="fa-solid fa-microchip text-[#FF8533]"></i>
                ESP32-S3 WROOM CAM + RFID RC522
            </span>
        </div>
    </footer>
</div>

{{-- ── Toast Notifications ── --}}
<div id="toastContainer" class="fixed top-4 right-4 z-50 flex flex-col gap-2 pointer-events-none"></div>

@if(session('success'))
<script>window._toasts = window._toasts||[]; window._toasts.push({type:'success',msg:{{ Js::from(session('success')) }}});</script>
@endif
@if(session('error'))
<script>window._toasts = window._toasts||[]; window._toasts.push({type:'error',msg:{{ Js::from(session('error')) }}});</script>
@endif

<script>
function toggleSidebar() {
    const s = document.getElementById('sidebar');
    const o = document.getElementById('overlay');
    s.classList.toggle('open');
    o.classList.toggle('hidden');
}

// Live Clock
setInterval(() => {
    const now = new Date();
    const pad = n => String(n).padStart(2,'0');
    const el = document.getElementById('clock');
    if (el) el.textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
}, 1000);

// Modern Toast Notification with FontAwesome Icons (No Emojis)
function showToast(type, msg, duration = 4000) {
    const c = document.getElementById('toastContainer');
    if (!c) return;
    
    // Forest Green for Success (#2E5A27), Red for Error, Bright Orange for Info (#FF8533), Amber for Warning
    const colors = {
        success: 'bg-[#2E5A27] text-white border border-[#2E5A27]/30 shadow-lg shadow-[#2E5A27]/20',
        error:   'bg-red-600 text-white border border-red-700 shadow-lg shadow-red-600/20',
        info:    'bg-[#FF8533] text-white border border-[#EA6D1A] shadow-lg shadow-[#FF8533]/20',
        warning: 'bg-amber-600 text-white border border-amber-700 shadow-lg shadow-amber-600/20'
    };
    
    const icons = {
        success: '<i class="fa-solid fa-circle-check text-base"></i>',
        error:   '<i class="fa-solid fa-circle-xmark text-base"></i>',
        info:    '<i class="fa-solid fa-circle-info text-base"></i>',
        warning: '<i class="fa-solid fa-triangle-exclamation text-base"></i>'
    };

    const t = document.createElement('div');
    t.className = `toast pointer-events-auto flex items-start gap-3 px-4 py-3 rounded-xl text-sm font-medium max-w-sm ${colors[type] || colors.info}`;
    t.innerHTML = `
        <span class="shrink-0 mt-0.5">${icons[type] || icons.info}</span>
        <span class="flex-1 leading-snug">${msg}</span>
        <button onclick="this.parentElement.remove()" class="opacity-70 hover:opacity-100 transition-opacity ml-1.5 p-0.5 text-xs leading-none">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>
    `;
    c.appendChild(t);
    setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity .3s ease'; }, duration - 300);
    setTimeout(() => t.remove(), duration);
}

// Show queued toasts
(window._toasts || []).forEach(({type, msg}) => showToast(type, msg));

// Expose globally for AJAX
window.showToast = showToast;

@admin
// WA Gateway status polling
(function () {
    const dot        = document.getElementById('wa-dot');
    const dotPing    = document.getElementById('wa-dot-ping');
    const label      = document.getElementById('wa-label');
    const sub        = document.getElementById('wa-sub');
    const disconnBtn = document.getElementById('wa-disconnect-btn');
    if (!dot) return;

    function applyState(color, pingColor, labelText, subText, connected) {
        dot.className     = `w-2.5 h-2.5 rounded-full ${color} block`;
        dotPing.className = `w-2.5 h-2.5 rounded-full ${pingColor} block absolute inset-0 animate-ping opacity-60`;
        label.textContent = labelText;
        sub.textContent   = subText;
        if (disconnBtn) disconnBtn.classList.toggle('hidden', !connected);
    }

    function poll() {
        fetch('{{ route("wa-gateway.status") }}')
            .then(r => r.json())
            .then(data => {
                if (!data.online) {
                    applyState('bg-red-500', 'bg-red-500', 'WA Tidak Aktif', 'Gateway offline', false);
                } else if (data.connected) {
                    applyState('bg-[#2E5A27]', 'bg-emerald-400', 'WA Terhubung', 'Siap kirim notifikasi', true);
                } else if (data.qr) {
                    applyState('bg-[#FF8533]', 'bg-[#FF8533]', 'Scan QR Code', 'Klik untuk buka scanner', false);
                } else {
                    applyState('bg-amber-400', 'bg-amber-400', 'WA Menghubungkan', 'Menunggu koneksi...', false);
                }
            })
            .catch(() => {
                applyState('bg-red-500', 'bg-red-500', 'WA Tidak Aktif', 'Gateway offline', false);
            });
    }

    poll();
    setInterval(poll, 15000);
})();

async function waDisconnect() {
    if (!confirm('Putuskan koneksi WhatsApp? Sesi akan dihapus dan perlu scan QR ulang.')) return;
    const btn = document.getElementById('wa-disconnect-btn');
    btn.disabled = true;
    btn.classList.add('opacity-50');
    try {
        const r = await fetch('{{ route("wa-gateway.disconnect") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
        });
        const data = await r.json();
        if (data.success) {
            showToast('success', 'WhatsApp berhasil diputus');
        } else {
            showToast('error', data.message || 'Gagal memutus WhatsApp');
        }
    } catch (e) {
        showToast('error', 'Gagal menghubungi gateway');
    } finally {
        btn.disabled = false;
        btn.classList.remove('opacity-50');
    }
}
@endadmin
</script>
@stack('scripts')
</body>
</html>
