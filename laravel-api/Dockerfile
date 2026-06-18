FROM dunglas/frankenphp:latest-php8.3

# Install PHP extensions yang dibutuhkan Laravel
RUN install-php-extensions \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    zip \
    opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy Caddyfile kustom
COPY Caddyfile /etc/caddy/Caddyfile

# Simpan stub files di dalam image agar tersedia saat bootstrap otomatis
COPY stubs/ /stubs/

# Copy entrypoint script
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
