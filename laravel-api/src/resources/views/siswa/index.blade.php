@extends('layouts.app')
@section('title', 'Data Siswa')

@section('breadcrumb')
    <span class="text-slate-700 text-sm font-semibold">Data Siswa</span>
@endsection

@section('content')

{{-- Page Header --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Manajemen Data Siswa</h1>
        <p class="mt-1 flex items-center gap-1.5 text-sm text-slate-500">
            <i class="fa-solid fa-users text-blue-600"></i>
            <span>Daftar siswa dan kartu RFID MIFARE 1K</span>
        </p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('siswa.kartu-massal', request()->only('cari', 'kelas', 'jurusan_id')) }}" target="_blank"
           class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50">
            <i class="fa-solid fa-print text-xs"></i>
            <span>Cetak Kartu Massal</span>
        </a>
        <a href="{{ route('siswa.create') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-blue-700">
            <i class="fa-solid fa-user-plus text-xs"></i>
            <span>Daftarkan Siswa</span>
        </a>
    </div>
</div>

{{-- Ringkasan per kelas --}}
@if($kelasList->count() > 0)
<div class="flex flex-wrap gap-2 mb-5">
    @foreach($kelasList as $kelas)
    @php $jumlah = \App\Models\Siswa::where('kelas', $kelas)->where('aktif', true)->count(); @endphp
    <a href="{{ route('siswa.index', ['kelas' => $kelas]) }}"
       class="flex items-center gap-2 rounded-lg border px-3 py-1.5 text-xs font-semibold transition-colors
              {{ request('kelas') === $kelas ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-slate-200 bg-white text-slate-600 hover:border-blue-200 hover:text-blue-700' }}">
        <span>{{ $kelas }}</span>
        <span class="w-5 h-5 rounded-full text-[11px] font-bold flex items-center justify-center
                     {{ request('kelas') === $kelas ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $jumlah }}</span>
    </a>
    @endforeach
</div>
@endif

{{-- Toolbar --}}
<div class="flex flex-wrap items-center gap-2.5 mb-4">
    <form method="GET" action="{{ route('siswa.index') }}" class="flex gap-2 flex-wrap flex-1 min-w-0">
        <div class="relative">
            <i class="fa-solid fa-magnifying-glass text-gray-400 absolute left-3.5 top-3 text-xs"></i>
            <x-input name="cari" value="{{ request('cari') }}" placeholder="Cari nama / NIS / RFID..."
                     class="w-56 py-2 pl-9 pr-4" />
        </div>
        <x-select name="kelas" class="w-auto px-3 py-2">
            <option value="">Semua Kelas</option>
            @foreach($kelasList as $kelas)
            <option value="{{ $kelas }}" {{ request('kelas') === $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
            @endforeach
        </x-select>
        <x-select name="jurusan_id" class="w-auto px-3 py-2">
            <option value="">Semua Jurusan</option>
            @foreach($jurusanList as $j)
            <option value="{{ $j->id }}" {{ (string) request('jurusan_id') === (string) $j->id ? 'selected' : '' }}>{{ $j->nama }}</option>
            @endforeach
        </x-select>
        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50">
            <i class="fa-solid fa-filter text-xs"></i>
            <span>Filter</span>
        </button>
        @if(request('cari') || request('kelas') || request('jurusan_id'))
        <a href="{{ route('siswa.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition-colors hover:bg-slate-50">
            <i class="fa-solid fa-rotate-left text-xs"></i>
            <span>Reset</span>
        </a>
        @endif
    </form>
</div>

{{-- Tabel --}}
<x-card class="overflow-hidden">
    <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-5 py-3">
        <p class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
            <i class="fa-solid fa-user-graduate text-blue-600"></i>
            <span>{{ $siswa->total() }} siswa terdaftar</span>
        </p>
    </div>
    <x-table class="rounded-none border-0">
        <thead>
            <tr class="border-b border-slate-200 bg-slate-50">
                <x-table.th class="text-left px-5 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider w-10">#</x-table.th>
                <x-table.th colspan="2" class="text-left px-4 py-3 text-slate-500">Siswa</x-table.th>
                <x-table.th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">NIS</x-table.th>
                <x-table.th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">Kelas</x-table.th>
                <x-table.th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">Jurusan</x-table.th>
                <x-table.th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">UID RFID</x-table.th>
                <x-table.th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">Status</x-table.th>
                <x-table.th class="text-left px-4 py-3 text-gray-400 font-bold text-[11px] uppercase tracking-wider">Aksi</x-table.th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($siswa as $i => $s)
            <tr class="hover:bg-[#F8FAFC]/60 transition-colors {{ !$s->aktif ? 'opacity-50' : '' }}">
                <x-table.td class="px-5 py-3.5 text-gray-400 text-xs tabular-nums">{{ $siswa->firstItem() + $i }}</x-table.td>

                <x-table.td class="pl-4 pr-2 py-3.5 w-12">
                    <div class="w-9 h-9 rounded-full overflow-hidden flex items-center justify-center text-white text-xs font-bold shadow-2xs"
                         style="background: {{ avatarColor($s->nama) }}">
                        @if($s->foto_url)
                        <img src="{{ $s->foto_url }}" class="w-full h-full object-cover" alt="{{ $s->nama }}">
                        @else
                        {{ avatarInitials($s->nama) }}
                        @endif
                    </div>
                </x-table.td>

                <x-table.td class="pr-4 py-3.5">
                    <p class="font-bold text-[#1E1E1E]">{{ $s->nama }}</p>
                </x-table.td>
                <x-table.td class="px-4 py-3.5 font-mono text-gray-500 text-xs tabular-nums">{{ $s->nis ?? '—' }}</x-table.td>
                <x-table.td class="px-4 py-3.5">
                    @if($s->kelas)
                    <span class="inline-block px-2.5 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-bold">{{ $s->kelas }}</span>
                    @else <span class="text-gray-300 text-xs">—</span> @endif
                </x-table.td>
                <x-table.td class="px-4 py-3.5">
                    @if($s->jurusan)
                    <span class="inline-block px-2.5 py-1 bg-[#334155]/10 text-[#334155] border border-[#334155]/20 rounded-lg text-xs font-bold font-mono">{{ $s->jurusan->kode }}</span>
                    @else <span class="text-gray-300 text-xs">—</span> @endif
                </x-table.td>
                <x-table.td class="px-4 py-3.5">
                    <code class="text-xs bg-[#F8FAFC] text-[#334155] px-2.5 py-1 rounded-lg tracking-widest font-mono border border-gray-200 font-bold">{{ $s->rfid_uid }}</code>
                </x-table.td>
                <x-table.td class="px-4 py-3.5">
                    @if($s->aktif)
                    <x-badge tone="green" class="gap-1.5">
                        <i class="fa-solid fa-circle-check text-[10px]"></i> Aktif
                    </x-badge>
                    @else
                    <x-badge tone="slate" class="gap-1.5">
                        <i class="fa-solid fa-circle-xmark text-[10px]"></i> Nonaktif
                    </x-badge>
                    @endif
                </x-table.td>
                <x-table.td class="px-4 py-3.5">
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('siswa.kartu', $s) }}" target="_blank" title="Cetak Kartu RFID"
                           class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-bold bg-gray-100 text-gray-700 rounded-lg hover:bg-[#2563EB]/15 hover:text-[#2563EB] transition-colors">
                            <i class="fa-solid fa-id-card text-xs"></i>
                            <span>Kartu</span>
                        </a>
                        <a href="{{ route('siswa.edit', $s) }}"
                           class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-bold bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                            <span>Edit</span>
                        </a>
                        @if($s->aktif && auth()->user()->isAdmin())
                        <button onclick="confirmDeactivate({{ $s->id }}, '{{ addslashes($s->nama) }}')"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-bold bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors border border-red-100">
                            <i class="fa-solid fa-user-xmark text-xs"></i>
                            <span>Nonaktifkan</span>
                        </button>
                        @endif
                    </div>
                </x-table.td>
            </tr>
            @empty
            <tr>
                <x-table.td colspan="9" class="py-20 text-center">
                    <div class="w-16 h-16 rounded-full bg-[#F8FAFC] flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <i class="fa-solid fa-users-slash text-3xl"></i>
                    </div>
                    <p class="font-bold text-gray-600">Belum ada siswa terdaftar</p>
                    <a href="{{ route('siswa.create') }}" class="inline-flex items-center gap-1.5 text-[#2563EB] text-xs font-bold hover:underline mt-2">
                        <span>Daftarkan siswa pertama</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </x-table.td>
            </tr>
            @endforelse
        </tbody>
    </x-table>

    @if($siswa->hasPages())
    <div class="border-t border-slate-200 px-5 py-4">
        {{ $siswa->appends(request()->query())->links() }}
    </div>
    @endif
</x-card>

{{-- Modal konfirmasi nonaktifkan --}}
<div id="deactivateModal" class="fixed inset-0 bg-black/60 z-50 hidden items-center justify-center p-4 backdrop-blur-xs">
    <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl p-6">
        <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center mx-auto mb-4 text-red-600">
            <i class="fa-solid fa-triangle-exclamation text-xl"></i>
        </div>
        <h3 class="text-center font-bold text-gray-900 text-base mb-1">Nonaktifkan Siswa?</h3>
        <p class="text-center text-gray-500 text-xs mb-6">
            <span id="deactivateName" class="font-bold text-gray-800"></span> tidak akan bisa melakukan scan absensi setelah dinonaktifkan.
        </p>
        <div class="flex gap-2.5">
            <button onclick="closeDeactivate()" class="flex-1 px-4 py-2 border border-gray-200 text-gray-600 rounded-xl text-xs font-bold hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <form id="deactivateForm" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-xl text-xs font-bold hover:bg-red-700 transition-colors shadow-sm">
                    Nonaktifkan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDeactivate(id, nama) {
        document.getElementById('deactivateName').textContent = nama;
        document.getElementById('deactivateForm').action = `/siswa/${id}`;
        document.getElementById('deactivateModal').classList.replace('hidden','flex');
        document.body.style.overflow = 'hidden';
    }
    function closeDeactivate() {
        document.getElementById('deactivateModal').classList.replace('flex','hidden');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', e => { if(e.key==='Escape') closeDeactivate(); });
</script>
@endpush
