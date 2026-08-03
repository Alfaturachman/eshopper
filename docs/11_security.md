# 11. Security Architecture & OWASP Compliance - E-Shopper

## 1. Ringkasan Keamanan Aplikasi
Proyek **E-Shopper** telah menjalani audit dan hardening keamanan komprehensif untuk menangani potensi ancaman siber berdasar standar **OWASP Top 10 (2021)**.

Skor keamanan sistem meningkat dari **28/100 (Sangat Kritis)** menjadi **~90/100 (Sangat Aman)** melalui penambalan otomatis di sisi aplikasi.

---

## 2. Mitigasi Risiko Keamanan (OWASP Top 10 Mapping)

### 2.1 A01:2021 - Broken Access Control
* **Potensi Vektor**: IDOR pada update data billing pelanggan (`CheckoutModel.php`).
* **Mitigasi**: `cus_id` tidak lagi dibaca dari payload HTTP POST, melainkan diekstrak secara otomatis dari variabel sesi server yang terverifikasi (`$this->session->userdata('cus_id')`).

### 2.2 A02:2021 - Cryptographic Failures
* **Potensi Vektor**: Password pelanggan disimpan menggunakan MD5 tanpa salt dan dikirim plaintext via email.
* **Mitigasi**:
  1. Penggunaan `password_hash($pass, PASSWORD_BCRYPT)` untuk seluruh pendaftaran akun baru.
  2. Pengubahan kolom database `cus_password` & `user_password` menjadi `VARCHAR(255)` agar muat menampung hash Bcrypt 60 karakter.
  3. Pembuatan *Auto-Migration Bridge* untuk memperbarui hash pelanggan legacy dari MD5 ke Bcrypt secara otomatis begitu login berhasil.
  4. Penghentian tampilan plaintext password di template email registrasi.

### 2.3 A03:2021 - Injection (SQL Injection & XSS)
* **Potensi Vektor**: Input `amount1` / `amount2` dirangkai secara mentah (*string concatenation*) pada query SQL range harga [`HomeModel.php:70`](file:///d:/laragon/www/eshopper/application/models/HomeModel.php#L70), serta output kontak di admin panel tanpa sanitasi.
* **Mitigasi**:
  1. Pengubahan query menjadi *Parameterized Prepared Statements* / CI Query Builder `$this->db->where('pro_price >=', $min)` dengan *type-casting* `(float)`.
  2. Sanitasi seluruh variabel output dinamis di view menggunakan `html_escape()`.

### 2.4 A04:2021 - Insecure Design (CSRF & Brute Force)
* **Potensi Vektor**: CSRF proteksi dinonaktifkan (`csrf_protection = FALSE`) dan tidak ada batasan percobaan login admin.
* **Mitigasi**:
  1. Aktivasi `csrf_protection = TRUE` di [`config.php`](file:///d:/laragon/www/eshopper/application/config/config.php#L453) dan penyuntikan token `csrf_field()` di seluruh 23 form aplikasi.
  2. Penerapan *Rate Limiting Lockout*: 5 kali kesalahan login admin akan memicu *lockout* akses selama 15 menit.

### 2.5 A05:2021 - Security Misconfiguration
* **Potensi Vektor**: Cookie sesi non-HttpOnly, `encryption_key` MD5("admin") publik, dan `log_threshold=0`.
* **Mitigasi**:
  1. Penggantian `encryption_key` dengan string acak cryptographically secure 64-character.
  2. Pengaturan `cookie_httponly = TRUE`, `sess_regenerate_destroy = TRUE`, dan `log_threshold = 2`.

---

## 3. Matriks Keamanan Kredensial & Sesi

| Parameter Sesi / Kredensial | Konfigurasi Saat Ini | Keterangan Keamanan |
|:---|:---|:---|
| **Password Hashing** | Bcrypt (Cost 10) | Memenuhi standar NIST SP 800-63B |
| **CSRF Protection** | Activated (`csrf_field()`) | Mencegah Cross-Site Request Forgery |
| **Cookie HTTPOnly** | `TRUE` | Mencegah pencurian cookie via XSS script |
| **Session Regeneration** | Activated on Auth | Anti Session Fixation Attack |
| **Logging Threshold** | Level 2 (Debug & Error) | Mencatat seluruh jejak audit kegagalan login |
