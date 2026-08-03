# 10. Deployment & DevOps Guide - E-Shopper

## 1. Persyaratan Server Production

| Komponen | Persyaratan Minimum | Persyaratan Direkomendasikan |
|:---|:---|:---|
| **Sistem Operasi** | Ubuntu 22.04 LTS / Debian 12 / Windows Server | Ubuntu 24.04 LTS Linux |
| **Web Server** | Apache 2.4 (mod_rewrite enabled) / Nginx | Apache 2.4 dengan HTTP/2 |
| **Runtime Language** | PHP 8.1 / PHP 8.2 / PHP 8.3 | PHP 8.3 (Extension: pdo_mysql, mysqli, mbstring, gd) |
| **Database System** | MySQL 5.7+ / MariaDB 10.3+ | MySQL 8.0+ (Engine: InnoDB) |
| **SSL Certificate** | TLS 1.2+ Certificate (Let's Encrypt) | HTTPS Enabled dengan HSTS |

---

## 2. Langkah Deployment Manual (Laragon / Apache)

1. **Clone Repository / Extract Project**:
   ```bash
   cd D:\laragon\www
   git clone https://github.com/your-org/eshopper.git
   ```
2. **Konfigurasi Database MySQL**:
   * Buat database baru bernama `eshopper` di MySQL:
     ```sql
     CREATE DATABASE eshopper CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
     ```
   * Impor skema dasar dan pastikan seluruh tabel ber-engine InnoDB.
3. **Konfigurasi File Environment & Config**:
   * Buka [`application/config/database.php`](file:///d:/laragon/www/eshopper/application/config/database.php) dan sesuaikan username, password, dan hostname DB lokal.
   * Pastikan `ENVIRONMENT` di [`index.php`](file:///d:/laragon/www/eshopper/index.php#L56) diset ke `'production'`.
4. **Verifikasi Izin Direktori (Directory Permissions)**:
   * Berikan hak akses *write* pada folder `uploads/` dan `application/logs/`:
     ```bash
     chmod -R 775 uploads application/logs
     ```

---

## 3. Deployment Menggunakan Docker

### `Dockerfile`
```dockerfile
FROM php:8.3-apache

# Install ekstensi MySQL & Apache Rewrite
RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && a2enmod rewrite

COPY . /var/www/html/
WORKDIR /var/www/html/

RUN chown -R www-data:www-data /var/www/html/uploads /var/www/html/application/logs

EXPOSE 80
```

### `docker-compose.yml`
```yaml
version: '3.8'

services:
  web:
    build: .
    ports:
      - "80:80"
    depends_on:
      - db
    environment:
      - ENVIRONMENT=production

  db:
    image: mysql:8.0
    command: --default-authentication-plugin=mysql_native_password
    environment:
      MYSQL_ROOT_PASSWORD: rootpassword
      MYSQL_DATABASE: eshopper
    ports:
      - "3306:3306"
    volumes:
      - db_data:/var/lib/mysql

volumes:
  db_data:
```

---

## 4. Pipeline CI/CD (.github/workflows/deploy.yml)

```yaml
name: CI/CD Pipeline E-Shopper

on:
  push:
    branches: [ "main" ]

jobs:
  test-and-deploy:
    runs-on: ubuntu-latest

    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: root
          MYSQL_DATABASE: eshopper
        ports:
          - 3306:3306

    steps:
    - uses: actions/checkout@v3

    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.3'
        extensions: mysqli, pdo_mysql, mbstring, gd

    - name: Install Dependencies
      run: composer install --prefer-dist --no-progress

    - name: Run Automated Tests
      run: vendor/bin/phpunit
```
