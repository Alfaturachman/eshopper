# E-Shopper — Modern E-Commerce Platform

![PHP Version](https://img.shields.io/badge/PHP-8.3.10-777BB4?style=flat-square&logo=php)
![Framework](https://img.shields.io/badge/CodeIgniter-3.1.6+-EF4223?style=flat-square&logo=codeigniter)
![Database](https://img.shields.io/badge/Database-MySQL_InnoDB-4479A1?style=flat-square&logo=mysql)
![PHPUnit](https://img.shields.io/badge/PHPUnit-10.0.0_PASS-46B48A?style=flat-square&logo=phpunit)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=flat-square&logo=docker)
![CI/CD](https://img.shields.io/badge/CI%2FCD-GitHub_Actions-2088FF?style=flat-square&logo=githubactions)
![Security Score](https://img.shields.io/badge/Security_Score-95%2F100-brightgreen?style=flat-square)

**E-Shopper** adalah aplikasi web e-commerce berbasis PHP (CodeIgniter 3) yang telah dimodernisasi, di-hardening keamanannya, dan dioptimasi kinerjanya. Aplikasi ini mencakup portal belanja publik bagi pelanggan dan admin panel komprehensif bagi pengelola toko.

---

## Fitur Utama

### Portal Pelanggan (Front-End)
* **Katalog & Filter Produk**: Pencarian produk, navigasi kategori/sub-kategori, filter brand, serta filter rentang harga dinamis.
* **Keranjang Belanja**: Pengelolaan item cart, validasi kuantitas stok otomatis, dan rekalkulasi total biaya secara transparan.
* **Checkout Terintegrasi**: Pendaftaran akun, login pelanggan, pengisian billing/shipping info, serta konfirmasi pemesanan (*place order*).
* **Migrasi Password Otomatis**: Transisi kata sandi otomatis dari MD5 legacy ke **Bcrypt** secara aman saat login.

### Admin Panel (Back-End)
* **Manajemen Inventaris**: Fitur CRUD Produk (judul, deskripsi, harga, stok, brand, kategori, & foto produk).
* **Pengolahan Pesanan**: Melihat daftar transaksi pelanggan, tagihan, dan rincian item pesanan.
* **Fitur Balas Pesan Kontak**: Membaca dan membalas pesan kontak dari pelanggan secara langsung.
* **Proteksi Brute-Force**: Fitur *Rate Limiting Lockout* 15 menit setelah 5x kesalahan kata sandi berturut-turut.

---

## Hardening Keamanan (OWASP Compliance)

| Aspek Keamanan | Fitur / Penambalan Kebocoran |
|:---|:---|
| **Password Hashing** | Bcrypt (`password_hash`) dengan cost factor 10 & DB column `VARCHAR(255)`. |
| **CSRF Guard** | `csrf_protection = TRUE` dengan token `csrf_field()` pada seluruh 23 form aplikasi. |
| **Anti-SQL Injection** | Parameterized Prepared Statements & float casting pada seluruh query sensitif. |
| **Anti-XSS** | Output escaping menggunakan `html_escape()` pada seluruh variabel dinamis di view. |
| **Path Traversal Protection** | Validasi `realpath()` sebelum penghapusan berkas media (`unlink`). |
| **Session Security** | `cookie_httponly = TRUE`, `sess_regenerate_destroy = TRUE`, & Anti-IDOR session check. |

---

## Tech Stack

* **Language Runtime**: PHP 8.3.10 (Production Mode)
* **Framework**: CodeIgniter 3.1.6 / 3.1.13
* **Database Engine**: MySQL 8.0 (Storage Engine: **InnoDB**)
* **Automated Testing**: PHPUnit 10.0.0
* **Containerization**: Docker & Docker Compose (`php:8.3-apache` & `mysql:8.0`)
* **CI/CD Pipeline**: GitHub Actions (`.github/workflows/ci-cd.yml`)

---

## Panduan Instalasi Cepat

### Cara 1: Menggunakan Docker (Rekomendasi)

Persyaratan: Terpasang **Docker** dan **Docker Compose**.

1. **Clone repository ini**:
   ```bash
   git clone https://github.com/your-username/eshopper.git
   cd eshopper
   ```
2. **Jalankan container via Docker Compose**:
   ```bash
   docker-compose up -d --build
   ```
3. Buka browser di `http://localhost:8080`.

---

### Cara 2: Instalasi Lokal (Laragon / Apache + MySQL)

Persyaratan: PHP 8.1+, MySQL 8.0, Apache Web Server, Composer.

1. **Clone & Tempatkan di Web Root**:
   ```bash
   cd D:\laragon\www
   git clone https://github.com/your-username/eshopper.git eshopper
   cd eshopper
   ```
2. **Install Dependensi Composer**:
   ```bash
   composer install
   ```
3. **Konfigurasi Database MySQL**:
   * Buat database MySQL baru bernama `eshopper`.
   * Ubah kredensial di [`application/config/database.php`](file:///d:/laragon/www/eshopper/application/config/database.php) jika diperlukan.
   * Eksekusi query pengubahan storage engine ke InnoDB:
     ```sql
     ALTER TABLE tbl_brand ENGINE=InnoDB;
     ALTER TABLE tbl_category ENGINE=InnoDB;
     ALTER TABLE tbl_contact ENGINE=InnoDB;
     ALTER TABLE tbl_customer ENGINE=InnoDB;
     ALTER TABLE tbl_order ENGINE=InnoDB;
     ALTER TABLE tbl_order_details ENGINE=InnoDB;
     ALTER TABLE tbl_payment ENGINE=InnoDB;
     ALTER TABLE tbl_product ENGINE=InnoDB;
     ALTER TABLE tbl_shipping ENGINE=InnoDB;
     ALTER TABLE tbl_sub_category ENGINE=InnoDB;
     ALTER TABLE tbl_user ENGINE=InnoDB;
     ```
4. Access via browser: `http://localhost/eshopper`.

---

## Automated Testing (PHPUnit 10)

Aplikasi dilengkapi dengan suite pengujian otomatis untuk memverifikasi kalkulasi transaksi, validasi stok, keamanan CSRF, sanitasi XSS, dan rate limiting.

### Menjalankan Test Suite:
```bash
vendor/bin/phpunit
```

### Hasil Pengujian:
```bash
PHPUnit 10.0.0 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.3.10
Configuration: D:\laragon\www\eshopper\phpunit.xml

............                                                      12 / 12 (100%)

OK (12 tests, 26 assertions)
```

---

## Indeks Dokumentasi Proyek (`docs/`)

Dokumentasi lengkap proyek dapat diakses di direktori [`docs/`](file:///d:/laragon/www/eshopper/docs):

1. [01. Business Requirements Document (BRD)](file:///d:/laragon/www/eshopper/docs/01_brd.md)
2. [02. Product Requirements Document (PRD)](file:///d:/laragon/www/eshopper/docs/02_prd.md)
3. [03. Software Requirements Specification (SRS)](file:///d:/laragon/www/eshopper/docs/03_srs.md)
4. [04. System Architecture](file:///d:/laragon/www/eshopper/docs/04_architecture.md)
5. [05. Database Documentation & ERD](file:///d:/laragon/www/eshopper/docs/05_database.md)
6. [06. Design System & UI/UX Guidelines](file:///d:/laragon/www/eshopper/docs/06_desain.md)
7. [07. Routing & API Documentation](file:///d:/laragon/www/eshopper/docs/07_routing.md)
8. [08. Testing & QA Documentation](file:///d:/laragon/www/eshopper/docs/08_testing.md)
9. [09. User & Admin Manual](file:///d:/laragon/www/eshopper/docs/09_user_manual.md)
10. [10. Deployment & DevOps Guide](file:///d:/laragon/www/eshopper/docs/10_deployment.md)
11. [11. Security Architecture & OWASP Audit](file:///d:/laragon/www/eshopper/docs/11_security.md)
12. [12. Architecture Decision Records (ADR)](file:///d:/laragon/www/eshopper/docs/12_decision_log.md)
13. [13. Changelog & Version History](file:///d:/laragon/www/eshopper/docs/13_changelog.md)

---

## Lisensi
Proyek ini dilisensikan di bawah [Lisensi MIT](LICENSE).
