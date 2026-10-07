@php
    $bulan = now()->month;
    $tahunAwal = $bulan >= 7 ? now()->year : now()->year - 1;
    $tahunAjaran = $tahunAwal . '/' . ($tahunAwal + 1);
@endphp
<div class="kartu-wrap">
    <div class="kartu">
        <div class="kartu-header">
            <div class="kartu-header-text">
                <p class="kartu-sekolah">{{ $namaSekolah }}</p>
                <p class="kartu-subtitle">Kartu Identitas Siswa &middot; TA {{ $tahunAjaran }}</p>
            </div>
            <svg class="kartu-contactless" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <path d="M8.5 8.5a5 5 0 017 7"/>
                <path d="M6 6a9 9 0 0112 12"/>
                <circle cx="12" cy="15" r="1.4" fill="currentColor" stroke="none"/>
            </svg>
        </div>

        <div class="kartu-body">
            <div class="kartu-avatar" style="background:{{ avatarColor($s->nama) }}">
                @if($s->foto_url)
                <img src="{{ $s->foto_url }}" alt="{{ $s->nama }}">
                @else
                {{ avatarInitials($s->nama) }}
                @endif
            </div>
            <div class="kartu-info">
                <p class="kartu-nama">{{ $s->nama }}</p>
                @if($s->jurusan)
                <span class="kartu-kelas">{{ $s->jurusan->nama }}</span>
                @elseif($s->kelas)
                <span class="kartu-kelas">{{ $s->kelas }}</span>
                @endif
            </div>
        </div>

        <div class="kartu-footer">
            <span class="kartu-nis">NIS&nbsp;{{ $s->nis ?? '-' }}</span>
            <span class="kartu-uid">{{ $s->rfid_uid }}</span>
        </div>
    </div>
</div>
