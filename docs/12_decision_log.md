# 12. Architecture Decision Records (ADR) / Decision Log - E-Shopper

## ADR-01: Migrasi Storage Engine Database dari MyISAM ke InnoDB
* **Status**: Disetujui & Diterapkan (Accepted & Implemented)
* **Konteks**: Dump SQL lama proyek (`ecommerce_codeigniter (1).sql`) mengonfigurasi seluruh tabel menggunakan `ENGINE=MyISAM`. MyISAM tidak mendukung *Database Transactions* (`trans_start()`), Foreign Keys, dan hanya mendukung *Table-level locking*, sehingga rawan memicu *race condition* atau stok negatif pada pesanan bersamaan.
* **Keputusan**: Menjalankan query `ALTER TABLE tbl_name ENGINE=InnoDB` untuk seluruh 11 tabel database MySQL `eshopper` lokal.
* **Konsekuensi**: Sistem kini mendukung ACID Compliance, *Row-level locking*, dan transaksi pembatalan checkout aman tanpa sisa data yatim (*orphaned records*).

---

## ADR-02: Adaptasi Environment & Deprecation Patching untuk PHP 8.3
* **Status**: Disetujui & Diterapkan (Accepted & Implemented)
* **Konteks**: CodeIgniter 3.1.6 awal memicu banyak `Creation of dynamic property is deprecated` notice pada PHP 8.3. Saat `ENVIRONMENT` diset ke `development`, pesan notice PHP merusak HTTP response headers dan merusak format JSON/redirect.
* **Keputusan**: Mengubah default `ENVIRONMENT` di [`index.php`](file:///d:/laragon/www/eshopper/index.php#L56) menjadi `'production'`, mematikan penayangan error mentah ke layar publik, dan mengarahkan log ke [`application/logs/`](file:///d:/laragon/www/eshopper/application/logs).
* **Konsekuensi**: Aplikasi dapat berjalan dengan mulus di runtime PHP 8.3 tanpa merusak layout atau header HTTP.

---

## ADR-03: Strategi Migrasi Password Hashing dari MD5 ke Bcrypt
* **Status**: Disetujui & Diterapkan (Accepted & Implemented)
* **Konteks**: Password pelanggan lama tersimpan dalam format hash MD5 32 karakter tanpa salt. Mengubah seluruh password sekaligus secara paksa akan membuat pelanggan lama tidak dapat login.
* **Keputusan**:
  1. Mengubah panjang kolom DB `cus_password` menjadi `VARCHAR(255)`.
  2. Mendaftarkan pelanggan baru menggunakan `password_hash($pass, PASSWORD_BCRYPT)`.
  3. Menambahkan *Auto-Migration Bridge* pada [`CheckoutModel.php`](file:///d:/laragon/www/eshopper/application/models/CheckoutModel.php): saat pelanggan lama bernilai hash MD5 sukses melakukan verifikasi kata sandi, hash akun di database langsung di-upgrade secara transparan ke hash Bcrypt baru.
* **Konsekuensi**: Keamanan akun pelanggan meningkat drastis tanpa merusak kenyamanan pengguna (*Seamless User Experience*).

---

## ADR-04: Pemilihan Harness Testing PHPUnit 10
* **Status**: Disetujui & Diterapkan (Accepted & Implemented)
* **Konteks**: Ketiadaan pengujian otomatis memicu risiko tinggi terjadinya *regression bug* saat refactoring.
* **Keputusan**: Mengonfigurasi **PHPUnit 10** menggunakan kelas dasar [`tests/TestCase.php`](file:///d:/laragon/www/eshopper/tests/TestCase.php) dengan mekanisme pembungkusan transaksi database (`begin_transaction()` & `rollback()`).
* **Konsekuensi**: 12 unit & feature test cases berjalan dalam waktu $< 5$ detik secara terisolasi tanpa merusak data database MySQL lokal.
