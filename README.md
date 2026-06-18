# Sistem Absensi Siswa — ESP32-S3 CAM + RFID RC522

Sistem absensi otomatis berbasis IoT untuk sekolah. Setiap siswa menempelkan kartu RFID ke perangkat ESP32-S3 yang dipasang di ruangan; kamera otomatis memotret wajah, server mencatat waktu masuk/keluar, dan notifikasi beserta foto terkirim ke WhatsApp orang tua.

---

## Arsitektur

```
┌─────────────────────┐        HTTP POST        ┌──────────────────────────────────┐
│  ESP32-S3 WROOM CAM │ ──── /api/v1/absensi ──► │  Laravel 11 + FrankenPHP         │
│  + RFID RC522       │ ──── /api/v1/heartbeat ─► │  (container: espcam-api)         │
└─────────────────────┘                           │                                  │
                                                  │  ┌──────────────────────────┐   │
                                                  │  │  PostgreSQL 16           │   │
                                                  │  │  (container: espcam-db)  │   │
                                                  │  └──────────────────────────┘   │
                                                  └──────────────┬───────────────────┘
                                                                 │ HTTP POST /send-image
                                                                 ▼
                                                  ┌──────────────────────────────────┐
                                                  │  WA Gateway (Node.js + Baileys)  │
                                                  │  (container: wa-gateway :3001)   │
                                                  └──────────────────────────────────┘
                                                                 │
                                                                 ▼
                                                       WhatsApp orang tua
```

---

## Fitur

- **Absensi RFID + Foto** — scan kartu → kamera ambil foto → catat masuk/keluar
- **Notifikasi WhatsApp** — foto + info absen dikirim ke nomor orang tua secara otomatis
- **Multi-ruangan** — setiap ESP32 punya ID ruangan (R1, R2, …); terlihat di semua tampilan
- **Monitoring ESP32** — heartbeat setiap 60 detik; dashboard menampilkan status online/warning/offline tiap ruangan
- **Log UID Kartu** — setiap scan RFID tercatat di memory (7 hari, tanpa tabel DB), bisa difilter per status
- **Dashboard web** — rekap hari ini, rekap bulanan, export CSV, manajemen data siswa
- **WA Gateway panel** — status koneksi WhatsApp di sidebar + tombol disconnect

---

## Struktur Proyek

```
espcam32s3-base64-api/
├── espcam32s3-base64-api.ino   # Firmware Arduino (ESP32-S3)
└── laravel-api/
    ├── docker-compose.yml       # Orkestrasi: app + db + wa-gateway
    ├── Dockerfile               # FrankenPHP image
    ├── Caddyfile                # Konfigurasi web server
    ├── entrypoint.sh            # Migrasi + storage link saat startup
    ├── setup.sh                 # Setup awal (buat proyek Laravel, copy stubs)
    ├── .env                     # Konfigurasi Docker Compose
    ├── src/                     # Kode sumber Laravel
    │   ├── app/
    │   │   ├── Http/Controllers/
    │   │   │   ├── AbsensiController.php      # API: terima scan ESP32
    │   │   │   ├── HeartbeatController.php    # API: heartbeat ESP32
    │   │   │   ├── SiswaController.php        # API: manajemen siswa
    │   │   │   └── Web/
    │   │   │       ├── DashboardController.php
    │   │   │       ├── WebAbsensiController.php
    │   │   │       ├── WebSiswaController.php
    │   │   │       ├── RfidLogController.php
    │   │   │       └── ExportController.php
    │   │   ├── Models/
    │   │   │   ├── Siswa.php
    │   │   │   ├── Absensi.php
    │   │   │   └── Device.php
    │   │   └── Services/
    │   │       ├── WaService.php        # Kirim pesan/foto ke WA Gateway
    │   │       └── RfidLogService.php   # In-memory RFID scan log (Cache)
    │   ├── database/migrations/
    │   └── resources/views/             # Blade + Tailwind CSS
    └── wa-gateway/
        ├── Dockerfile
        ├── package.json
        └── src/index.js                 # Express + Baileys WA client
```

---

## Komponen Hardware

| Komponen | Keterangan |
|---|---|
| ESP32-S3 WROOM CAM | Modul kamera + WiFi utama |
| RFID RC522 | Reader kartu RFID 13.56 MHz (SPI) |
| Kartu RFID MIFARE 1K | Satu kartu per siswa |

**Wiring RFID RC522 → ESP32-S3:**

| RC522 | ESP32-S3 GPIO |
|---|---|
| SS (SDA) | 2 |
| SCK | 1 |
| MOSI | 3 |
| MISO | 46 |
| RST | 45 |
| 3.3V / GND | 3.3V / GND |

---

## Instalasi & Menjalankan

### Prasyarat

- Docker & Docker Compose
- Arduino IDE 2.x (untuk flash firmware)

### 1. Clone & konfigurasi

```bash
git clone <repo-url>
cd espcam32s3-base64-api/laravel-api
cp .env.example .env   # atau edit langsung
nano .env
```

Variabel penting di `.env`:

```env
APP_URL=http://<IP-SERVER>
APP_PORT=80
DB_DATABASE=espcam_db
DB_USERNAME=espcam
DB_PASSWORD=secret
```

### 2. Setup Laravel (pertama kali)

```bash
chmod +x setup.sh
./setup.sh
```

Script ini membuat proyek Laravel baru di `src/` dan menyalin file kustom dari `stubs/`.

### 3. Jalankan semua container

```bash
docker compose up -d --build
```

Container yang berjalan:

| Container | Port | Deskripsi |
|---|---|---|
| `espcam-api` | 80 / 443 | Laravel API + Web UI |
| `espcam-db` | — (internal) | PostgreSQL 16 |
| `wa-gateway` | 3001 | WhatsApp Gateway |

### 4. Flash firmware ESP32

Buka `espcam32s3-base64-api.ino` di Arduino IDE, sesuaikan konfigurasi:

```cpp
const char* ssid         = "nama-wifi";
const char* password     = "password-wifi";
const char* server_url   = "http://<IP-SERVER>/api/v1/absensi";
const char* heartbeat_url = "http://<IP-SERVER>/api/v1/heartbeat";
const char* device_id    = "R1";   // ganti per ruangan: R1, R2, R3, ...
```

Library yang dibutuhkan (Arduino Library Manager):
- `MFRC522` by GithubCommunity
- `ArduinoJson` by Benoit Blanchon
- `esp32` board package (Espressif)

### 5. Hubungkan WhatsApp

Buka `http://<IP-SERVER>:3001` di browser → scan QR Code dengan WhatsApp → koneksi tersambung.

Status koneksi juga terlihat di sidebar web app. Gunakan tombol power (🔴) untuk disconnect.

---

## API Endpoints

### ESP32 → Server

| Method | URL | Deskripsi |
|---|---|---|
| `POST` | `/api/v1/absensi` | Kirim scan RFID + foto (base64) |
| `POST` | `/api/v1/heartbeat` | Heartbeat perangkat (setiap 60 detik) |

**Body POST `/api/v1/absensi`:**

```json
{
  "rfid_uid": "AABBCCDD",
  "image_data": "<base64-jpeg>",
  "device_id": "R1"
}
```

**Response status:**

| HTTP | `status` | Arti |
|---|---|---|
| 201 | `masuk` | Absen masuk berhasil dicatat |
| 200 | `keluar` | Absen pulang berhasil dicatat |
| 409 | `sudah_lengkap` | Sudah absen masuk & pulang hari ini |
| 404 | — | Kartu tidak terdaftar / siswa tidak aktif |

**Body POST `/api/v1/heartbeat`:**

```json
{
  "device_id": "R1",
  "ip": "192.168.1.x",
  "firmware_version": "1.0.0"
}
```

### Manajemen Siswa

| Method | URL | Deskripsi |
|---|---|---|
| `GET` | `/api/v1/siswa` | Daftar siswa (filter: `kelas`, `aktif`) |
| `POST` | `/api/v1/siswa` | Tambah siswa baru |
| `GET` | `/api/v1/siswa/{id}` | Detail siswa |
| `PUT` | `/api/v1/siswa/{id}` | Update data siswa |
| `DELETE` | `/api/v1/siswa/{id}` | Nonaktifkan siswa |
| `GET` | `/api/v1/siswa/{id}/rekap` | Rekap absensi bulanan |

### Rekap Absensi

| Method | URL | Parameter |
|---|---|---|
| `GET` | `/api/v1/absensi` | `tanggal`, `siswa_id`, `kelas`, `bulan`, `tahun`, `per_page` |
| `GET` | `/api/v1/absensi/{id}` | Detail satu record |

### Kesehatan Server

```
GET /api/health
```

---

## Halaman Web

| URL | Deskripsi |
|---|---|
| `/` | Dashboard — rekap hari ini + monitoring perangkat |
| `/absensi/hari-ini` | Tabel absensi hari ini (live refresh) |
| `/absensi/rekap` | Rekap bulanan dengan filter kelas |
| `/absensi/export` | Export CSV |
| `/siswa` | Manajemen data siswa & kartu RFID |
| `/rfid-log` | Log setiap scan RFID (7 hari, filter status) |

---

## Logika Absensi

```
Scan RFID
    │
    ├─ Kartu tidak ditemukan → status: tidak_dikenal (404)
    │
    └─ Siswa ditemukan
           │
           ├─ Belum ada record hari ini → MASUK (201)
           │   └─ Kirim foto + notif WA ke orang tua
           │
           ├─ Sudah masuk, belum pulang → KELUAR (200)
           │   └─ Kirim foto + notif WA ke orang tua
           │
           └─ Sudah masuk & pulang → sudah_lengkap (409)
```

---

## Zona Waktu

Aplikasi dikonfigurasi menggunakan **Asia/Jakarta (UTC+7)**. Diatur di `src/config/app.php`:

```php
'timezone' => 'Asia/Jakarta',
```

---

## WA Gateway

WA Gateway berjalan di port **3001** menggunakan [Baileys](https://github.com/WhiskeySockets/Baileys) (WhatsApp Web API).

| Endpoint | Deskripsi |
|---|---|
| `GET /` | Halaman scan QR / status koneksi |
| `GET /status` | Status JSON (`connected`, `qr`) |
| `POST /send` | Kirim pesan teks (`to`, `message`) |
| `POST /send-image` | Kirim foto + caption (`to`, `caption`, `image_base64`) |
| `POST /logout` | Putuskan sesi + hapus file sesi |

Sesi disimpan di Docker volume `wa_session` sehingga tidak perlu scan QR ulang setelah restart container.

---

## Catatan Pengembangan

- **Tailwind CSS** digunakan via CDN (Play CDN) — jangan gunakan `@apply` di `<style>` tag, selalu tulis class langsung di HTML.
- **Log RFID** disimpan di Laravel Cache (file driver), bukan database — otomatis hangus setelah 7 hari.
- **Docker bind mount** — jika mengedit file host dan container tidak melihat perubahan, gunakan `docker cp` untuk menyalin file secara eksplisit ke container.
# esp32s3-laravel-wa-absensi
