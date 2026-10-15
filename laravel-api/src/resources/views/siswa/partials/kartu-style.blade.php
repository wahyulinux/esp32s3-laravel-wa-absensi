<style>
    * { -webkit-print-color-adjust: exact; print-color-adjust: exact; box-sizing: border-box; }

    .kartu {
        width: 54mm;
        height: 85.6mm;
        border-radius: 3.5mm;
        background: #fff;
        overflow: hidden;
        position: relative;
        font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
        box-shadow: 0 1px 3px rgba(0,0,0,.25);
        display: flex;
        flex-direction: column;
    }

    .kartu-header {
        background: linear-gradient(135deg, #5C2828, #FF8533);
        padding: 3mm 3.5mm 2.8mm;
        position: relative;
        flex-shrink: 0;
    }
    .kartu-header-content {
        display: flex;
        align-items: center;
        gap: 2mm;
        padding-right: 4.5mm;
    }
    .kartu-logo-wrap {
        width: 7.5mm;
        height: 7.5mm;
        border-radius: 1.2mm;
        background: #fff;
        padding: 0.4mm;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.25);
    }
    .kartu-logo {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .kartu-header-text { min-width: 0; text-align: left; flex: 1; }
    .kartu-sekolah {
        color: #fff;
        font-weight: 700;
        font-size: 7.2pt;
        line-height: 1.2;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .kartu-subtitle {
        color: rgba(255,255,255,.85);
        font-size: 4.8pt;
        letter-spacing: .02em;
        margin: .6mm 0 0;
    }
    .kartu-contactless {
        position: absolute;
        top: 2mm;
        right: 2mm;
        width: 3.8mm;
        height: 3.8mm;
        color: rgba(255,255,255,.8);
        transform: rotate(45deg);
    }

    .kartu-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 2mm;
        padding: 3mm 4mm;
        text-align: center;
    }
    .kartu-avatar {
        width: 23mm;
        height: 23mm;
        border-radius: 50%;
        color: #fff;
        font-weight: 700;
        font-size: 15pt;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
        box-shadow: 0 0 0 2px #fff, 0 0 0 2.6mm #FFF7ED;
    }
    .kartu-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .kartu-info { min-width: 0; width: 100%; }
    .kartu-nama {
        font-weight: 700;
        font-size: 9.5pt;
        color: #111827;
        line-height: 1.25;
        margin: 0 0 1.6mm;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .kartu-kelas {
        display: inline-block;
        background: #FFF7ED;
        color: #C2410C;
        border: .18mm solid #FED7AA;
        border-radius: 1.6mm;
        font-size: 6.6pt;
        font-weight: 700;
        padding: .5mm 1.8mm;
    }

    .kartu-footer {
        background: #5C2828;
        color: #fff;
        padding: 2.6mm 3mm;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .8mm;
        flex-shrink: 0;
    }
    .kartu-nis {
        font-size: 5.8pt;
        letter-spacing: .03em;
        opacity: .8;
    }
    .kartu-uid {
        font-family: 'Courier New', monospace;
        font-size: 7.6pt;
        letter-spacing: .16em;
        font-weight: 600;
    }

    @media print {
        .no-print { display: none !important; }
        body { margin: 0; background: #fff !important; }
    }
</style>
