#!/bin/bash
set -e

cd /app

echo "=== Memulai ESP32-CAM API (Development) ==="

# ── Bootstrap: buat proyek Laravel jika belum ada ────────────────────────────
if [ ! -f "/app/composer.json" ]; then
    echo "[BOOTSTRAP] Proyek Laravel belum ada, membuat dari awal..."

    composer create-project laravel/laravel /app \
        --prefer-dist \
        --no-interaction \
        --no-progress

    echo "[BOOTSTRAP] Menyalin file kustom dari image..."

    cp /stubs/app/Http/Controllers/AbsensiController.php /app/app/Http/Controllers/
    cp /stubs/app/Http/Controllers/SiswaController.php   /app/app/Http/Controllers/
    cp /stubs/app/Models/Siswa.php                       /app/app/Models/
    cp /stubs/app/Models/Absensi.php                     /app/app/Models/
    cp /stubs/database/migrations/2024_01_01_000001_create_siswa_table.php \
                                                          /app/database/migrations/
    cp /stubs/database/migrations/2024_01_01_000002_create_absensi_table.php \
                                                          /app/database/migrations/
    cp /stubs/routes/api.php                              /app/routes/api.php
    cp /stubs/bootstrap/app.php                           /app/bootstrap/app.php

    # Tulis .env Laravel dari variabel environment container
    cat > /app/.env << EOF
APP_NAME="Sistem Absensi Siswa"
APP_ENV=${APP_ENV:-local}
APP_KEY=
APP_DEBUG=true
APP_URL=${APP_URL:-http://localhost}

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=${DB_HOST:-db}
DB_PORT=${DB_PORT:-5432}
DB_DATABASE=${DB_DATABASE:-espcam_db}
DB_USERNAME=${DB_USERNAME:-espcam}
DB_PASSWORD=${DB_PASSWORD:-secret}

FILESYSTEM_DISK=local
EOF

    php artisan key:generate --force
    echo "[BOOTSTRAP] Proyek Laravel siap."
fi

# ── 1. Install dependensi Composer (dengan dev dependencies) ─────────────────
if [ ! -d "/app/vendor" ]; then
    echo "[1/4] Menginstal dependensi Composer..."
    composer install --no-interaction
else
    echo "[1/4] Vendor sudah ada, skip."
fi

# ── 2. Sinkronkan APP_KEY ke .env jika disuplai dari environment ─────────────
if [ -n "$APP_KEY" ] && [ "$APP_KEY" != "base64:" ]; then
    sed -i "s|^APP_KEY=.*|APP_KEY=$APP_KEY|" /app/.env
    echo "[2/4] APP_KEY disinkronkan dari environment."
else
    current_key=$(grep "^APP_KEY=" /app/.env | cut -d= -f2-)
    if [ -z "$current_key" ] || [ "$current_key" = "base64:" ]; then
        php artisan key:generate --force
        echo "[2/4] APP_KEY digenerate."
    else
        echo "[2/4] APP_KEY sudah tersedia."
    fi
fi

# ── 3. Migrasi database ──────────────────────────────────────────────────────
echo "[3/4] Menjalankan migrasi database..."
php artisan migrate --force

# ── 4. Storage symlink ───────────────────────────────────────────────────────
echo "[4/4] Membuat storage symlink..."
php artisan storage:link --force 2>/dev/null || true

echo "=== API siap! Memulai FrankenPHP (dev mode)... ==="

exec docker-php-entrypoint "$@"
