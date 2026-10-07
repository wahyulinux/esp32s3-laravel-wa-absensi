@extends('layouts.app')
@section('title', 'Jurusan')

@section('breadcrumb')
    <span class="text-gray-700 text-sm font-medium">Jurusan</span>
@endsection

@section('content')

{{-- Page Header --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Jurusan</h1>
        <p class="text-sm text-gray-500 mt-0.5">Manajemen data jurusan / kompetensi keahlian</p>
    </div>
    <a href="{{ route('jurusan.create') }}"
       class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Jurusan
    </a>
</div>

{{-- Toolbar --}}
<div class="flex flex-wrap items-center gap-2.5 mb-4">
    <form method="GET" action="{{ route('jurusan.index') }}" class="flex gap-2 flex-wrap flex-1 min-w-0">
        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-gray-400 absolute left-3 top-2.5">
                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"/>
            </svg>
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari kode / nama jurusan..."
                   class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">
        </div>
        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors">Cari</button>
        @if(request('cari'))
        <a href="{{ route('jurusan.index') }}" class="px-4 py-2 border border-gray-200 text-gray-500 rounded-xl text-sm hover:bg-gray-50 transition-colors">Reset</a>
        @endif
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/70 flex items-center justify-between">
        <p class="text-xs text-gray-500 font-medium">{{ $jurusan->total() }} jurusan ditemukan</p>
    </div>
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100">
                <th class="text-left px-5 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide w-10">#</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Kode</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Nama Jurusan</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Jumlah Siswa</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Status</th>
                <th class="text-left px-4 py-3 text-gray-400 font-semibold text-xs uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($jurusan as $i => $j)
            <tr class="hover:bg-slate-50/60 transition-colors {{ !$j->aktif ? 'opacity-50' : '' }}">
                <td class="px-5 py-3.5 text-gray-300 text-xs tabular-nums">{{ $jurusan->firstItem() + $i }}</td>
                <td class="px-4 py-3.5">
                    <code class="text-xs bg-indigo-50 text-indigo-700 px-2.5 py-1.5 rounded-lg font-mono font-bold border border-indigo-100">{{ $j->kode }}</code>
                </td>
                <td class="px-4 py-3.5">
                    <p class="font-semibold text-gray-800">{{ $j->nama }}</p>
                </td>
                <td class="px-4 py-3.5 text-gray-500">
                    {{ $j->siswa_count }} siswa
                </td>
                <td class="px-4 py-3.5">
                    @if($j->aktif)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-400 border border-gray-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Nonaktif
                    </span>
                    @endif
                </td>
                <td class="px-4 py-3.5">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('jurusan.edit', $j) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-gray-100 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                                <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z"/>
                                <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z"/>
                            </svg>
                            Edit
                        </a>
                        <button onclick="confirmDelete({{ $j->id }}, '{{ addslashes($j->nama) }}')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors border border-red-100">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                                <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.808a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd"/>
                            </svg>
                            Hapus
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-20 text-center">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-gray-300">
                            <path d="M11.7 2.805a.75.75 0 01.6 0A60.65 60.65 0 0122.83 8.72a.75.75 0 01-.231 1.337 49.948 49.948 0 00-9.902 3.912l-.003.002-.34.18a.75.75 0 01-.707 0A50.86 50.86 0 007.5 12.174v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 014.653-2.52.75.75 0 00-.65-1.352 56.123 56.123 0 00-4.78 2.589 1.858 1.858 0 00-.859 1.228 49.803 49.803 0 00-4.634-1.527.75.75 0 01-.231-1.337A60.653 60.653 0 0111.7 2.805z"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-500">Belum ada jurusan terdaftar</p>
                    <a href="{{ route('jurusan.create') }}" class="inline-flex items-center gap-1.5 text-blue-600 text-sm hover:underline mt-2 font-medium">
                        Tambah jurusan pertama →
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    @if($jurusan->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $jurusan->appends(request()->query())->links() }}
    </div>
    @endif
</div>

{{-- Modal konfirmasi hapus --}}
<div id="deleteModal" class="fixed inset-0 bg-black/60 z-50 hidden items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl p-6">
        <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-red-600">
                <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 1.995-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.505-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"/>
            </svg>
        </div>
        <h3 class="text-center font-bold text-gray-900 text-base mb-1">Hapus Jurusan?</h3>
        <p class="text-center text-gray-500 text-sm mb-6">
            <span id="deleteName" class="font-semibold text-gray-700"></span> akan dihapus permanen. Jurusan yang masih dipakai siswa tidak bisa dihapus.
        </p>
        <div class="flex gap-3">
            <button onclick="closeDelete()" class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition-colors">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(id, nama) {
        document.getElementById('deleteName').textContent = nama;
        document.getElementById('deleteForm').action = `/jurusan/${id}`;
        document.getElementById('deleteModal').classList.replace('hidden','flex');
        document.body.style.overflow = 'hidden';
    }
    function closeDelete() {
        document.getElementById('deleteModal').classList.replace('flex','hidden');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', e => { if(e.key==='Escape') closeDelete(); });
</script>
@endpush
