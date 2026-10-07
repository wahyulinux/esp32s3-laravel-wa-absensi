const {
    default: makeWASocket,
    useMultiFileAuthState,
    DisconnectReason,
    fetchLatestBaileysVersion,
} = require('@whiskeysockets/baileys');
const express = require('express');
const qrcode  = require('qrcode');
const pino    = require('pino');
const fs      = require('fs');
const path    = require('path');

const app  = express();
const PORT = process.env.PORT || 3001;

app.use(express.json({ limit: '10mb' }));

// ── State ─────────────────────────────────────────────────────────────────────
let sock        = null;
let qrDataUrl   = null;
let isConnected = false;
let isConnecting = false;

// ── Session helpers ───────────────────────────────────────────────────────────
function clearSession() {
    const dir = '/app/session';
    if (fs.existsSync(dir)) {
        fs.readdirSync(dir).forEach(f => {
            try { fs.rmSync(path.join(dir, f), { recursive: true, force: true }); } catch (_) {}
        });
    }
}

// ── WhatsApp connect ──────────────────────────────────────────────────────────
async function connect() {
    if (isConnecting) return;
    isConnecting = true;

    const { state, saveCreds } = await useMultiFileAuthState('/app/session');
    const { version }          = await fetchLatestBaileysVersion();

    sock = makeWASocket({
        version,
        auth:              state,
        logger:            pino({ level: 'silent' }),
        printQRInTerminal: true,
        browser:           ['WA Gateway', 'Chrome', '120.0'],
    });

    sock.ev.on('creds.update', saveCreds);

    sock.ev.on('connection.update', async (update) => {
        const { connection, lastDisconnect, qr } = update;

        if (qr) {
            qrDataUrl   = await qrcode.toDataURL(qr);
            isConnected = false;
            console.log('[WA] QR code baru — silakan scan');
        }

        if (connection === 'open') {
            isConnected  = true;
            isConnecting = false;
            qrDataUrl    = null;
            console.log('[WA] Terhubung!');
        }

        if (connection === 'close') {
            isConnected  = false;
            isConnecting = false;
            const code   = lastDisconnect?.error?.output?.statusCode;
            const logout = code === DisconnectReason.loggedOut;
            console.log('[WA] Koneksi terputus, kode:', code, '| logout:', logout);
            if (!logout) {
                console.log('[WA] Mencoba reconnect dalam 5 detik...');
                setTimeout(connect, 5000);
            } else {
                qrDataUrl = null;
                console.log('[WA] Sesi tidak valid (logged out). Menghapus sesi dan membuat QR baru...');
                clearSession();
                setTimeout(connect, 2000);
            }
        }
    });
}

// ── Format nomor ──────────────────────────────────────────────────────────────
function formatNumber(no) {
    let n = no.replace(/[^0-9]/g, '');
    if (n.startsWith('0')) n = '62' + n.slice(1);
    return n + '@s.whatsapp.net';
}

// ── Routes ────────────────────────────────────────────────────────────────────

// HTML status page — buka di browser untuk scan QR
app.get('/', (req, res) => {
    let body;
    if (isConnected) {
        body = `
            <div style="text-align:center;padding:40px;font-family:sans-serif">
                <div style="display:inline-block;background:#dcfce7;border:2px solid #16a34a;border-radius:16px;padding:32px 48px">
                    <div style="font-size:48px;margin-bottom:12px">✅</div>
                    <h2 style="color:#15803d;margin:0 0 8px">WhatsApp Terhubung</h2>
                    <p style="color:#166534;margin:0">Gateway siap menerima request</p>
                </div>
            </div>`;
    } else if (qrDataUrl) {
        body = `
            <div style="text-align:center;padding:40px;font-family:sans-serif">
                <h2 style="color:#1e40af;margin-bottom:4px">Scan QR Code</h2>
                <p style="color:#6b7280;margin-bottom:24px">Buka WhatsApp → Perangkat Tertaut → Tambahkan Perangkat</p>
                <img src="${qrDataUrl}" style="border:4px solid #e5e7eb;border-radius:12px;width:280px;height:280px">
                <p style="color:#9ca3af;font-size:13px;margin-top:16px">QR auto-refresh setiap 5 detik</p>
            </div>
            <script>setTimeout(()=>location.reload(),5000)</script>`;
    } else {
        body = `
            <div style="text-align:center;padding:40px;font-family:sans-serif">
                <div style="font-size:40px;margin-bottom:16px">⏳</div>
                <h2 style="color:#6b7280">Menghubungkan...</h2>
                <p style="color:#9ca3af">Menunggu QR code atau koneksi</p>
            </div>
            <script>setTimeout(()=>location.reload(),3000)</script>`;
    }

    res.send(`<!DOCTYPE html><html lang="id"><head>
        <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
        <title>WA Gateway</title>
    </head><body style="margin:0;background:#f9fafb">${body}</body></html>`);
});

// JSON status — untuk health check / polling
app.get('/status', (req, res) => {
    res.json({
        connected: isConnected,
        qr:        qrDataUrl ? true : false,
    });
});

// Kirim pesan teks
app.post('/send', async (req, res) => {
    if (!isConnected || !sock) {
        return res.status(503).json({ success: false, message: 'WhatsApp belum terhubung' });
    }

    const { to, message } = req.body;

    if (!to || !message) {
        return res.status(400).json({ success: false, message: 'Field "to" dan "message" wajib diisi' });
    }

    try {
        const jid = formatNumber(String(to));
        await sock.sendMessage(jid, { text: message });
        console.log('[SEND]', jid, '→', message.slice(0, 50));
        res.json({ success: true, to: jid.replace('@s.whatsapp.net', '') });
    } catch (err) {
        console.error('[SEND ERROR]', err.message);
        res.status(500).json({ success: false, message: err.message });
    }
});

// Kirim foto dengan caption
app.post('/send-image', async (req, res) => {
    if (!isConnected || !sock) {
        return res.status(503).json({ success: false, message: 'WhatsApp belum terhubung' });
    }

    const { to, caption, image_base64 } = req.body;

    if (!to || !image_base64) {
        return res.status(400).json({ success: false, message: 'Field "to" dan "image_base64" wajib diisi' });
    }

    try {
        const jid    = formatNumber(String(to));
        const buffer = Buffer.from(image_base64, 'base64');
        await sock.sendMessage(jid, { image: buffer, caption: caption || '' });
        console.log('[IMG]', jid, '→ foto terkirim');
        res.json({ success: true, to: jid.replace('@s.whatsapp.net', '') });
    } catch (err) {
        console.error('[IMG ERROR]', err.message);
        res.status(500).json({ success: false, message: err.message });
    }
});

// Logout — hapus sesi, tidak reconnect
app.post('/logout', async (req, res) => {
    try {
        if (sock) {
            await sock.logout().catch(() => {});
            sock.end();
        }
    } catch (_) {}

    isConnected  = false;
    isConnecting = false;
    qrDataUrl    = null;
    sock         = null;

    clearSession();

    console.log('[WA] Logout — sesi dihapus');
    res.json({ success: true, message: 'Berhasil logout dari WhatsApp' });

    // Reconnect agar QR baru muncul
    setTimeout(connect, 2000);
});

// ── Start ─────────────────────────────────────────────────────────────────────
app.listen(PORT, () => {
    console.log(`[WA] Gateway berjalan di port ${PORT}`);
    connect();
});
