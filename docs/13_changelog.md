# 13. Changelog & Version History - E-Shopper

Seluruh riwayat perubahan versi dan rilis proyek **E-Shopper** dicatat berdasarkan format [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

---

## [v1.2.0] - 2026-08-03 (Structural Refactoring & Testing Release)
### Added
- **Automated Test Suite**: Menambahkan PHPUnit 10 test harness dengan 12 Unit & Feature test cases (100% PASS).
- **Contact Reply System**: Menambahkan layout form balasan pesan kontak dan handler `send_reply()` pada `Contact.php`.
- **Database Engine Migration**: Memindahkan seluruh 11 tabel MySQL dari `MyISAM` ke `InnoDB` untuk mendukung ACID transactions dan row-level locking.
- **System Documentation**: Menambahkan 13 dokumen panduan lengkap di folder `docs/`.

### Changed
- **Autoload Optimization**: Mengosongkan `$autoload['model']` global pada `autoload.php` untuk memuat model secara *lazy-loaded* pada controller terkait.
- **Composer Cleanup**: Menghapus dependency palsu `"other/dependency": "^1.2.3"` dari `composer.json`.

---

## [v1.1.0] - 2026-08-03 (Emergency Security & Stability Patch)
### Security Fixes
- **SQL Injection**: Menambal SQL Injection publik pada query range harga `HomeModel.php:70` menggunakan prepared statements & float casting.
- **CSRF Protection**: Mengaktifkan `csrf_protection = TRUE` dan menyuntikkan token `csrf_field()` di 23 form aplikasi.
- **XSS Output Escaping**: Menerapkan `html_escape()` pada seluruh output dinamis di view front & back.
- **Password Hardening**: Mengganti algoritma MD5 dengan Bcrypt (`password_hash`), memperbesar kolom `cus_password` ke `VARCHAR(255)`, dan menghentikan pengiriman password via email.
- **Rate Limiting**: Menerapkan lockout 15 menit pada login admin setelah 5x percobaan gagal.
- **Anti-IDOR**: Memastikan `cus_id` pada pembaruan data billing dibaca langsung dari variabel sesi server.
- **Path Traversal Protection**: Menerapkan verifikasi path aman `realpath()` sebelum eksekusi `unlink()` gambar produk.

### Bug Fixes
- Menambal 6 lokasi fatal error *NULL dereference* pada controller `Home`, `Login`, `Checkout`, `Cart`, dan `Invoice`.
- Memperbaiki penanganan `_404_page` pada ID produk yang tidak ditemukan.

---

## [v1.0.0] - 2017-12-31 (Legacy Initial Release)
### Added
- Rilis awal aplikasi E-Shopper berbasis CodeIgniter 3.1.6 dan MySQL.
- Fitur keranjang belanja, checkout, katalog produk, dan admin panel dasar.
