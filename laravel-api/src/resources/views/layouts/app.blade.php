<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ $namaSekolah }}</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📋</text></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 600:'#2563eb', 700:'#1d4ed8', 800:'#1e3a5f', 900:'#1e3050' }
                    }
                }
            }
        }
    </script>
    <style>
        #sidebar { transition: transform .25s cubic-bezier(.4,0,.2,1); }
        @media(max-width:1023px) { #sidebar { transform: translateX(-100%); } #sidebar.open { transform: translateX(0); } }
        .toast { animation: slideIn .3s ease; }
        @keyframes slideIn { from { opacity:0; transform:translateX(2rem); } to { opacity:1; transform:translateX(0); } }
        .sort-link { display:inline-flex; align-items:center; gap:.25rem; cursor:pointer; user-select:none; }
        .sort-link:hover, th.sortable:hover { color:#2563eb; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 min-h-screen" x-data="{ sidebarOpen: false }">

{{-- Overlay mobile --}}
<div id="overlay" class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden" onclick="toggleSidebar()"></div>

{{-- ── Sidebar ── --}}
<aside id="sidebar" class="w-64 min-h-screen bg-slate-900 flex flex-col fixed top-0 left-0 z-30 shadow-2xl border-r border-slate-700/40">

    {{-- Header --}}
    <div class="px-5 py-5 border-b border-slate-700/50 flex items-center justify-between gap-2">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-9 h-9 rounded-xl bg-blue-500 flex items-center justify-center shadow-lg shadow-blue-500/30 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" class="w-5 h-5">
                    <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0118 9.375v9.375a3 3 0 003-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 00-.673-.05A3 3 0 0015 1.5h-1.5a3 3 0 00-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6zM13.5 3A1.5 1.5 0 0012 4.5h4.5A1.5 1.5 0 0015 3h-1.5z" clip-rule="evenodd"/>
                    <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 013 20.625V9.375zM6 12a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V12zm2.25 0a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75zM6 15a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V15zm2.25 0a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75zM6 18a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V18zm2.25 0a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-white font-semibold text-sm leading-tight truncate">{{ $namaSekolah }}</p>
                <p class="text-slate-400 text-xs mt-0.5">ESP32-CAM · RFID</p>
            </div>
        </div>
        <button onclick="toggleSidebar()" class="lg:hidden text-slate-500 hover:text-white transition-colors p-1 rounded-lg hover:bg-slate-800">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-3 py-4 overflow-y-auto">

        {{-- Section: Utama --}}
        <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest px-2 mb-1.5 mt-1 select-none">Utama</p>

        @php $isDash = request()->routeIs('dashboard'); @endphp
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium mb-0.5 transition-colors group
                  {{ $isDash ? 'bg-blue-500/15 text-blue-300 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <span class="w-8 h-8 flex items-center justify-center rounded-lg shrink-0 transition-colors
                         {{ $isDash ? 'bg-blue-500/25 text-blue-300' : 'bg-white/5 text-slate-500 group-hover:bg-white/10 group-hover:text-slate-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                    <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z"/>
                </svg>
            </span>
            Dashboard
        </a>

        {{-- Section: Absensi --}}
        <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest px-2 mb-1.5 mt-5 select-none">Absensi</p>

        @php $isHariIni = request()->routeIs('absensi.hari-ini'); @endphp
        <a href="{{ route('absensi.hari-ini') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium mb-0.5 transition-colors group
                  {{ $isHariIni ? 'bg-blue-500/15 text-blue-300 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <span class="w-8 h-8 flex items-center justify-center rounded-lg shrink-0 transition-colors
                         {{ $isHariIni ? 'bg-blue-500/25 text-blue-300' : 'bg-white/5 text-slate-500 group-hover:bg-white/10 group-hover:text-slate-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                    <path d="M12.75 12.75a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM7.5 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM8.25 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM9.75 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM10.5 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM16.5 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM15 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM16.5 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                    <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 017.5 3v1.5h9V3A.75.75 0 0118 3v1.5h.75a3 3 0 013 3v11.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V7.5a3 3 0 013-3H6V3a.75.75 0 01.75-.75zm13.5 9a1.5 1.5 0 00-1.5-1.5H5.25a1.5 1.5 0 00-1.5 1.5v7.5a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5v-7.5z" clip-rule="evenodd"/>
                </svg>
            </span>
            Hari Ini
        </a>

        @php $isRekap = request()->routeIs('absensi.rekap'); @endphp
        <a href="{{ route('absensi.rekap') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium mb-0.5 transition-colors group
                  {{ $isRekap ? 'bg-blue-500/15 text-blue-300 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <span class="w-8 h-8 flex items-center justify-center rounded-lg shrink-0 transition-colors
                         {{ $isRekap ? 'bg-blue-500/25 text-blue-300' : 'bg-white/5 text-slate-500 group-hover:bg-white/10 group-hover:text-slate-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z" clip-rule="evenodd"/>
                    <path fill-rule="evenodd" d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V3z" clip-rule="evenodd"/>
                </svg>
            </span>
            Rekap
        </a>

        {{-- Section: Master Data --}}
        <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest px-2 mb-1.5 mt-5 select-none">Master Data</p>

        @php $isSiswa = request()->routeIs('siswa.*'); @endphp
        <a href="{{ route('siswa.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium mb-0.5 transition-colors group
                  {{ $isSiswa ? 'bg-blue-500/15 text-blue-300 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <span class="w-8 h-8 flex items-center justify-center rounded-lg shrink-0 transition-colors
                         {{ $isSiswa ? 'bg-blue-500/25 text-blue-300' : 'bg-white/5 text-slate-500 group-hover:bg-white/10 group-hover:text-slate-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                    <path d="M4.5 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM14.25 8.625a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0zM1.5 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM17.25 19.128l-.001.144a2.25 2.25 0 01-.233.96 10.088 10.088 0 005.06-1.01.75.75 0 00.42-.643 4.875 4.875 0 00-6.957-4.611 8.586 8.586 0 011.71 5.157v.003z"/>
                </svg>
            </span>
            Data Siswa
        </a>

        @php $isKelas = request()->routeIs('kelas.*'); @endphp
        <a href="{{ route('kelas.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium mb-0.5 transition-colors group
                  {{ $isKelas ? 'bg-blue-500/15 text-blue-300 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <span class="w-8 h-8 flex items-center justify-center rounded-lg shrink-0 transition-colors
                         {{ $isKelas ? 'bg-blue-500/25 text-blue-300' : 'bg-white/5 text-slate-500 group-hover:bg-white/10 group-hover:text-slate-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                    <rect x="3" y="3" width="8" height="8" rx="1.5"/>
                    <rect x="13" y="3" width="8" height="8" rx="1.5"/>
                    <rect x="3" y="13" width="8" height="8" rx="1.5"/>
                    <rect x="13" y="13" width="8" height="8" rx="1.5"/>
                </svg>
            </span>
            Kelas
        </a>

        @php $isJurusan = request()->routeIs('jurusan.*'); @endphp
        <a href="{{ route('jurusan.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium mb-0.5 transition-colors group
                  {{ $isJurusan ? 'bg-blue-500/15 text-blue-300 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <span class="w-8 h-8 flex items-center justify-center rounded-lg shrink-0 transition-colors
                         {{ $isJurusan ? 'bg-blue-500/25 text-blue-300' : 'bg-white/5 text-slate-500 group-hover:bg-white/10 group-hover:text-slate-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                    <path d="M11.7 2.805a.75.75 0 01.6 0A60.65 60.65 0 0122.83 8.72a.75.75 0 01-.231 1.337 49.948 49.948 0 00-9.902 3.912l-.003.002-.34.18a.75.75 0 01-.707 0A50.86 50.86 0 007.5 12.174v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 014.653-2.52.75.75 0 00-.65-1.352 56.123 56.123 0 00-4.78 2.589 1.858 1.858 0 00-.859 1.228 49.803 49.803 0 00-4.634-1.527.75.75 0 01-.231-1.337A60.653 60.653 0 0111.7 2.805z"/>
                    <path d="M13.06 15.473a48.45 48.45 0 017.666-3.282c.134 1.414.22 2.843.255 4.284a.75.75 0 01-.46.71 47.87 47.87 0 00-8.105 4.342.75.75 0 01-.832 0 47.87 47.87 0 00-8.104-4.342.75.75 0 01-.461-.71c.035-1.442.121-2.87.255-4.286.921.304 1.83.634 2.726.99v1.27a1.5 1.5 0 00-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.905.434 1.356.66a6.727 6.727 0 00.551-1.607 1.5 1.5 0 00.14-2.67v-.645a48.548 48.548 0 013.44 1.667 2.25 2.25 0 002.12 0z"/>
                </svg>
            </span>
            Jurusan
        </a>

        {{-- Section: Log --}}
        <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest px-2 mb-1.5 mt-5 select-none">Log</p>

        @php $isRfidLog = request()->routeIs('rfid-log.*'); @endphp
        <a href="{{ route('rfid-log.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium mb-0.5 transition-colors group
                  {{ $isRfidLog ? 'bg-blue-500/15 text-blue-300 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <span class="w-8 h-8 flex items-center justify-center rounded-lg shrink-0 transition-colors
                         {{ $isRfidLog ? 'bg-blue-500/25 text-blue-300' : 'bg-white/5 text-slate-500 group-hover:bg-white/10 group-hover:text-slate-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd" d="M2.25 6a3 3 0 013-3h13.5a3 3 0 013 3v12a3 3 0 01-3 3H5.25a3 3 0 01-3-3V6zm3.97.97a.75.75 0 011.06 0l2.25 2.25a.75.75 0 010 1.06l-2.25 2.25a.75.75 0 01-1.06-1.06l1.72-1.72-1.72-1.72a.75.75 0 010-1.06zm4.28 4.28a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" clip-rule="evenodd"/>
                </svg>
            </span>
            Log UID Kartu
        </a>

        {{-- Section: Sistem --}}
        <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest px-2 mb-1.5 mt-5 select-none">Sistem</p>

        @php $isPengaturan = request()->routeIs('pengaturan.*'); @endphp
        <a href="{{ route('pengaturan.edit') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium mb-0.5 transition-colors group
                  {{ $isPengaturan ? 'bg-blue-500/15 text-blue-300 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <span class="w-8 h-8 flex items-center justify-center rounded-lg shrink-0 transition-colors
                         {{ $isPengaturan ? 'bg-blue-500/25 text-blue-300' : 'bg-white/5 text-slate-500 group-hover:bg-white/10 group-hover:text-slate-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd" d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 00-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 00-2.282.819l-.922 1.597a1.875 1.875 0 00.432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 000 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 00-.432 2.385l.922 1.597a1.875 1.875 0 002.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 002.28-.819l.923-1.597a1.875 1.875 0 00-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 000-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 00-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 00-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 00-1.85-1.567h-1.843zM12 15.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z" clip-rule="evenodd"/>
                </svg>
            </span>
            Pengaturan
        </a>

    </nav>

    {{-- Footer --}}
    <div class="px-3 py-4 border-t border-slate-700/50 space-y-2">

        {{-- WA Gateway Status --}}
        <div class="flex items-center gap-2">
            <a href="http://{{ request()->getHost() }}:3001" target="_blank" id="wa-status-card"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-slate-800/60 hover:bg-slate-800 transition-colors cursor-pointer group flex-1 min-w-0">
                <div class="relative shrink-0">
                    <span id="wa-dot" class="w-2.5 h-2.5 rounded-full bg-slate-600 block"></span>
                    <span id="wa-dot-ping" class="w-2.5 h-2.5 rounded-full block absolute inset-0 opacity-0"></span>
                </div>
                <div class="min-w-0 flex-1">
                    <p id="wa-label" class="text-slate-400 text-xs font-medium leading-tight">WhatsApp</p>
                    <p id="wa-sub" class="text-slate-600 text-[10px] mt-0.5 truncate">Memeriksa...</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                     class="w-3.5 h-3.5 text-slate-600 group-hover:text-slate-400 shrink-0 transition-colors">
                    <path fill-rule="evenodd" d="M5.22 14.78a.75.75 0 001.06 0l7.22-7.22v5.69a.75.75 0 001.5 0v-7.5a.75.75 0 00-.75-.75h-7.5a.75.75 0 000 1.5h5.69l-7.22 7.22a.75.75 0 000 1.06z" clip-rule="evenodd"/>
                </svg>
            </a>
            {{-- Disconnect button — shown via JS when connected --}}
            <button id="wa-disconnect-btn"
                    onclick="waDisconnect()"
                    title="Putuskan WhatsApp"
                    class="hidden w-9 h-9 shrink-0 flex items-center justify-center rounded-xl bg-red-500/15 hover:bg-red-500/30 text-red-400 hover:text-red-300 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd" d="M12 2.25a.75.75 0 01.75.75v9a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM6.166 5.106a.75.75 0 010 1.06 8.25 8.25 0 1011.668 0 .75.75 0 111.06-1.06c3.808 3.807 3.808 9.98 0 13.788-3.807 3.808-9.98 3.808-13.788 0-3.808-3.807-3.808-9.98 0-13.788a.75.75 0 011.06 0z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>

        {{-- Server Aktif --}}
        <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-slate-800/60">
            <div class="relative shrink-0">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 block"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 block absolute inset-0 animate-ping opacity-60"></span>
            </div>
            <div class="min-w-0">
                <p class="text-slate-200 text-xs font-medium leading-tight">Server Aktif</p>
                <p class="text-slate-500 text-[10px] mt-0.5 truncate">FrankenPHP · PostgreSQL</p>
            </div>
        </div>
    </div>
</aside>

{{-- ── Main ── --}}
<div class="lg:ml-64 flex flex-col min-h-screen">

    {{-- Topbar --}}
    <header class="bg-white/80 backdrop-blur-sm border-b border-gray-200/80 sticky top-0 z-10">
        <div class="px-4 lg:px-6 py-3 flex items-center gap-3">
            <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-1.5 text-sm flex-1 min-w-0">
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-blue-600 transition-colors shrink-0 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd"/></svg>
                </a>
                @hasSection('breadcrumb')
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-gray-300 shrink-0"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg>
                    @yield('breadcrumb')
                @endif
            </nav>

            <div class="flex items-center gap-2 shrink-0">
                <div class="hidden sm:flex items-center gap-1.5 bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-gray-400"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd"/></svg>
                    <span id="clock" class="text-xs font-mono text-gray-600 tabular-nums">{{ now()->format('H:i:s') }}</span>
                </div>
                <div class="hidden md:flex items-center gap-1.5 text-xs text-gray-400">
                    <span>{{ now()->translatedFormat('d F Y') }}</span>
                </div>
            </div>
        </div>
    </header>

    {{-- Page Content --}}
    <main class="flex-1 p-4 lg:p-6 max-w-screen-2xl w-full mx-auto">
        @yield('content')
    </main>

    <footer class="px-6 py-3 text-center text-xs text-gray-400 border-t border-gray-100/80">
        Sistem Absensi Siswa &middot; ESP32-S3 WROOM CAM + RFID RC522
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

// Clock
setInterval(() => {
    const now = new Date();
    const pad = n => String(n).padStart(2,'0');
    const el = document.getElementById('clock');
    if (el) el.textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
}, 1000);

// Toast system
function showToast(type, msg, duration = 4000) {
    const c = document.getElementById('toastContainer');
    const colors = { success: 'bg-green-600', error: 'bg-red-600', info: 'bg-blue-600', warning: 'bg-amber-500' };
    const icons  = { success: '✓', error: '✕', info: 'ℹ', warning: '⚠' };
    const t = document.createElement('div');
    t.className = `toast pointer-events-auto flex items-start gap-3 px-4 py-3 rounded-xl shadow-xl text-white text-sm max-w-xs ${colors[type]||colors.info}`;
    t.innerHTML = `<span class="font-bold text-base leading-none mt-0.5">${icons[type]||'ℹ'}</span><span class="flex-1">${msg}</span><button onclick="this.parentElement.remove()" class="opacity-70 hover:opacity-100 ml-1 text-lg leading-none">&times;</button>`;
    c.appendChild(t);
    setTimeout(() => t.style.opacity = '0', duration - 300);
    setTimeout(() => t.remove(), duration);
}

// Show queued toasts
(window._toasts || []).forEach(({type, msg}) => showToast(type, msg));

// Expose globally for AJAX use
window.showToast = showToast;

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
                    applyState('bg-emerald-400', 'bg-emerald-400', 'WA Terhubung', 'Siap kirim notifikasi', true);
                } else if (data.qr) {
                    applyState('bg-amber-400', 'bg-amber-400', 'Scan QR Code', 'Klik untuk buka scanner', false);
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
</script>
@stack('scripts')
</body>
</html>
