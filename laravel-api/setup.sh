#!/bin/bash
set -e

BASE_DIR="$(cd "$(dirname "$0")" && pwd)"
SRC_DIR="$BASE_DIR/src"

echo "╔═══════════════════════════════════════════╗"
echo "║   Setup ESP32-CAM Laravel API             ║"
echo "╚═══════════════════════════════════════════╝"
echo ""

# ── 1. Cek Docker tersedia ──────────────────────────────────────────────────
if ! command -v docker &>/dev/null; then
    echo "[ERROR] Docker tidak ditemukan. Instal Docker terlebih dahulu."
    exit 1
fi

# ── 2. Buat proyek Laravel (jika belum ada) ─────────────────────────────────
if [ ! -f "$SRC_DIR/artisan" ]; then
    echo "[1/5] Membuat proyek Laravel baru di src/ ..."
    mkdir -p "$SRC_DIR"
    docker run --rm \
        -v "$SRC_DIR:/app" \
        -w /app \
        composer:latest \
        composer create-project laravel/laravel . \
            --prefer-dist \
            --no-interaction \
            --no-progress
    echo "      ✓ Proyek Laravel berhasil dibuat"
else
    echo "[1/5] src/artisan sudah ada, skip pembuatan proyek."
fi

# ── 3. Salin file kustom (controller, model, migration, routes) ─────────────
echo "[2/5] Menyalin file kustom..."

cp "$BASE_DIR/stubs/app/Http/Controllers/ImageController.php" \
   "$SRC_DIR/app/Http/Controllers/"

cp "$BASE_DIR/stubs/app/Models/CamImage.php" \
   "$SRC_DIR/app/Models/"

cp "$BASE_DIR/stubs/database/migrations/2024_01_01_000000_create_cam_images_table.php" \
   "$SRC_DIR/database/migrations/"

cp "$BASE_DIR/stubs/routes/api.php" \
   "$SRC_DIR/routes/api.php"

cp "$BASE_DIR/stubs/bootstrap/app.php" \
   "$SRC_DIR/bootstrap/app.php"

echo "      ✓ File kustom berhasil disalin"

# ── 4. Konfigurasi .env Laravel ─────────────────────────────────────────────
echo "[3/5] Mengatur file .env Laravel..."

# Baca variabel dari .env docker-compose (jika ada)
if [ -f "$BASE_DIR/.env" ]; then
    # shellcheck source=/dev/null
    source "$BASE_DIR/.env"
fi

DB_DATABASE="${DB_DATABASE:-espcam_db}"
DB_USERNAME="${DB_USERNAME:-espcam}"
DB_PASSWORD="${DB_PASSWORD:-secret}"

cat > "$SRC_DIR/.env" << EOF
APP_NAME="ESP32-CAM API"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=${APP_URL:-http://localhost}

LOG_CHANNEL=stack
LOG_LEVEL=warning

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=$DB_DATABASE
DB_USERNAME=$DB_USERNAME
DB_PASSWORD=$DB_PASSWORD

FILESYSTEM_DISK=local
EOF

echo "      ✓ .env Laravel dikonfigurasi"

# ── 5. Generate APP_KEY ──────────────────────────────────────────────────────
echo "[4/5] Membuat APP_KEY Laravel..."
APP_KEY=$(docker run --rm \
    -v "$SRC_DIR:/app" \
    -w /app \
    php:8.3-cli \
    php artisan key:generate --show 2>/dev/null || true)

if [ -n "$APP_KEY" ]; then
    sed -i "s|APP_KEY=|APP_KEY=$APP_KEY|" "$SRC_DIR/.env"
    echo "      ✓ APP_KEY berhasil digenerate"
fi

# ── 6. Selesai ───────────────────────────────────────────────────────────────
echo "[5/5] Setup selesai!"
echo ""
echo "╔═══════════════════════════════════════════╗"
echo "║   Langkah selanjutnya:                    ║"
echo "╠═══════════════════════════════════════════╣"
echo "║                                           ║"
echo "║  1. Edit .env (opsional):                 ║"
echo "║     nano .env                             ║"
echo "║                                           ║"
echo "║  2. Jalankan API:                         ║"
echo "║     docker compose up -d                  ║"
echo "║                                           ║"
echo "║  3. Cek status:                           ║"
echo "║     docker compose logs -f app            ║"
echo "║                                           ║"
echo "║  Endpoint:                                ║"
echo "║  POST   http://localhost/api/v1/images    ║"
echo "║  GET    http://localhost/api/v1/images    ║"
echo "║  GET    http://localhost/api/health       ║"
echo "║                                           ║"
echo "╚═══════════════════════════════════════════╝"
