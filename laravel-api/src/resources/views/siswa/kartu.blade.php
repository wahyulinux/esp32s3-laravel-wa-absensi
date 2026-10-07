<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu RFID — {{ $siswa->nama }}</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🪪</text></svg>">
    @include('siswa.partials.kartu-style')
    <style>
        body { background: #f1f5f9; margin: 0; font-family: 'Segoe UI', Tahoma, Arial, sans-serif; }
        .toolbar {
            position: sticky; top: 0; z-index: 10;
            background: #fff; border-bottom: 1px solid #e5e7eb;
            padding: 12px 20px; display: flex; align-items: center; justify-content: space-between; gap: 12px;
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

        .stage {
            display: flex; align-items: center; justify-content: center;
            min-height: calc(100vh - 58px);
            padding: 40px 20px;
        }
        .kartu-wrap {
            width: 108mm;
            height: 171.2mm;
        }
        .kartu-wrap .kartu {
            transform: scale(2);
            transform-origin: top left;
        }

        @media print {
            @page { size: 54mm 85.6mm; margin: 0; }
            .toolbar { display: none; }
            .stage { min-height: 0; padding: 0; display: block; }
            .kartu-wrap { width: 54mm; height: 85.6mm; }
            .kartu-wrap .kartu { transform: none; box-shadow: none; border-radius: 0; }
        }
    </style>
</head>
<body>

    <div class="toolbar no-print">
        <a href="{{ route('siswa.index') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z" clip-rule="evenodd"/></svg>
            Kembali
        </a>
        <span style="font-size:13px;color:#6b7280;font-weight:600;">Kartu RFID — {{ $siswa->nama }}</span>
        <button onclick="window.print()" class="btn-print">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
                <path d="M6 9V3h12v6"/>
                <path d="M6 18H4a1 1 0 01-1-1v-6a1 1 0 011-1h16a1 1 0 011 1v6a1 1 0 01-1 1h-2"/>
                <rect x="6" y="14" width="12" height="7"/>
            </svg>
            Cetak Kartu
        </button>
    </div>

    <div class="stage">
        @include('siswa.partials.kartu-card', ['s' => $siswa])
    </div>

</body>
</html>
