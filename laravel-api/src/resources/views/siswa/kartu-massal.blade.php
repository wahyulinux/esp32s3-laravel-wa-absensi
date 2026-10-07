<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu RFID{{ $kelas ? ' — ' . $kelas : '' }}</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🪪</text></svg>">
    @include('siswa.partials.kartu-style')
    <style>
        body { background: #f1f5f9; margin: 0; font-family: 'Segoe UI', Tahoma, Arial, sans-serif; }
        .toolbar {
            position: sticky; top: 0; z-index: 10;
            background: #fff; border-bottom: 1px solid #e5e7eb;
            padding: 12px 20px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;
        }
        .toolbar a, .toolbar button {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 13px; font-weight: 600; border-radius: 10px;
            padding: 9px 16px; border: none; cursor: pointer; text-decoration: none;
        }
        .btn-back { color: #4b5563; background: #f3f4f6; }
        .btn-back:hover { background: #e5e7eb; }
        .btn-print { color: #fff; background: #2563eb; }
        .btn-print:hover { background: #1d4ed8; }

        .sheet {
            width: 210mm;
            min-height: 297mm;
            margin: 24px auto;
            background: #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,.15);
            padding: 10mm;
            display: grid;
            grid-template-columns: repeat(3, 54mm);
            gap: 5mm 6mm;
            justify-content: center;
            align-content: start;
        }
        .kartu-wrap { page-break-inside: avoid; break-inside: avoid; }
        .kartu-wrap:nth-child(9n) { page-break-after: always; }

        .empty {
            text-align: center; padding: 80px 20px; color: #94a3b8;
        }

        @media print {
            @page { size: A4; margin: 0; }
            .toolbar { display: none; }
            .sheet { margin: 0; box-shadow: none; }
        }
    </style>
</head>
<body>

    <div class="toolbar no-print">
        <a href="{{ route('siswa.index', request()->only('cari', 'kelas')) }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z" clip-rule="evenodd"/></svg>
            Kembali
        </a>
        <span style="font-size:13px;color:#6b7280;font-weight:600;">
            {{ $siswaList->count() }} kartu siap cetak{{ $kelas ? " — Kelas {$kelas}" : '' }}
        </span>
        <button onclick="window.print()" class="btn-print">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
                <path d="M6 9V3h12v6"/>
                <path d="M6 18H4a1 1 0 01-1-1v-6a1 1 0 011-1h16a1 1 0 011 1v6a1 1 0 01-1 1h-2"/>
                <rect x="6" y="14" width="12" height="7"/>
            </svg>
            Cetak Semua
        </button>
    </div>

    @if($siswaList->isEmpty())
        <div class="empty no-print">Tidak ada siswa untuk dicetak.</div>
    @else
        <div class="sheet">
            @foreach($siswaList as $s)
                @include('siswa.partials.kartu-card', ['s' => $s])
            @endforeach
        </div>
    @endif

</body>
</html>
