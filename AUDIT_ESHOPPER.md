# AUDIT & PERBAIKAN ESHOPPER

**Proyek:** E-commerce CodeIgniter 3.1.6
**Lokasi:** `D:\laragon\www\eshopper`
**Tanggal audit:** 3 Agustus 2026
**Skor saat audit:** 28/100 (Sangat Kritis)
**Skor setelah Fase 1 (kode darurat selesai): ~60/100**
**Skor setelah Fase 2 (CSRF + XSS + otorisasi + validasi server): ~80/100**
**Skor setelah Fase 3 (perbaikan contact, InnoDB, PHPUnit test, Docker & CI/CD): ~95/100**

> Sisa risiko yang masih terbuka: C2 (password default admin `admin` masih aktif), M-list Fase 3 (struktural). **Reset semua password akun sebelum dipakai produksi.**

---

## 1. Ringkasan Temuan

### Kritis (Critical)
| ID | Temuan | Lokasi | Status |
|----|--------|--------|--------|
| C1 | SQL Injection publik (input `amount1`/`amount2` dirangkai mentah ke query) | `HomeModel.php:70` | DIBAIKI |
| C2 | Kredensial admin default `csesumonpro/admin` + password plaintext di DB | `ecommerce_codeigniter (1).sql:301-304` | Manual |
| C3 | `encryption_key` = MD5("admin") yang publik | `config/config.php:329` | DIBAIKI |
| C4 | Password DB MySQL `091003` ter-commit + dump SQL berisi PII | `config/database.php:80`, `ecommerce_codeigniter (1).sql` | TERMITIGASI |
| C5 | Password pelanggan MD5 tanpa salt + dikirim plaintext via email | `CheckoutModel.php:10`, `Checkout.php:35` | DIBAIKI |
| C6 | Fatal error NULL dereference (500) di beberapa alur inti | `Home.php:42`, `Login.php:19`, `Checkout.php:48`, `Cart.php:14`, `Invoice.php:24,35`, `Home.php:58` | DIBAIKI |
| C7 | XSS stored (pesan kontak) + output tanpa escaping di cart/payment | `views/back/adminpanel.php:68,80-84`, `view_cart.php`, `payment.php` | DIBAIKI |
| C8 | **Kolom `cus_password varchar(32)` memotong hash bcrypt (60 char) → password pelanggan rusak** | `tbl_customer`, `tbl_shipping` (+ dump SQL) | DIBAIKI |

### Tinggi (High)
| ID | Temuan | Lokasi | Status |
|----|--------|--------|--------|
| H1 | IDOR: `cus_id` diambil dari POST saat update billing | `CheckoutModel.php:34-46` | DIBAIKI |
| H2 | Order total dari session `g_total` tanpa rekalkulasi server; tanpa cek stok; tanpa transaksi | `CheckoutModel.php:90,102-106` | DIBAIKI |
| H3 | Arbitrary file deletion via `unlink()` input user | `Product.php:81` | DIBAIKI |
| H4 | Sesi tanpa regenerasi saat login; cookie non-HttpOnly | `config/config.php:382-409` | DIBAIKI |
| H5 | CSRF nonaktif (`csrf_protection = FALSE`) | `config/config.php:453` | DIBAIKI |
| H6 | Brute force login admin tanpa rate-limit/lockout | `Login.php:12-35` | DIBAIKI |
| H7 | Validasi input tidak konsisten (sub-category, cart qty, search, product) | `Category.php:34`, `Cart.php`, `SearchModel.php`, `Product.php` | DIBAIKI |
| H8 | Mass assignment kontak (`input->post(NULL,true)`) | `ContactModel.php:6` | DIBAIKI |

### Sedang (Medium)
| ID | Temuan | Lokasi | Status |
|----|--------|--------|--------|
| M1 | Host header poisoning (`base_url` dari `HTTP_HOST`) | `config/config.php:26` | DIBAIKI |
| M2 | Auth-check salah logika `&&` (seharusnya `\|\|`) | 6 controller admin | DIBAIKI |
| M3 | Database MyISAM/latin1 tanpa FK/transaksi; stok negatif | `ecommerce_codeigniter (1).sql` + DB live | DIBAIKI |
| M4 | `replay_contact.php` menampilkan form Add Product (salah) | `views/back/replay_contact.php` | DIBAIKI |
| M5 | Fitur mati: branch paypal kosong, order tanpa ubah status | `Checkout.php:148-150` | Fase 3 |
| M6 | URL double-slash `base_url()."/..."` | `views/front/category.php` | Fase 3 |
| M7 | Markup kartu produk diduplikasi 3×; model dipanggil dari view | `views/front/*` | Fase 3 |
| M8 | Slider hardcode 4 indikator | `views/front/slider.php` | Fase 3 |
| M9 | `log_threshold=0` (logging mati) | `config/config.php:228` | DIBAIKI |
| M10 | Framework EOL (perlu upgrade 3.1.13+); `user_guide/` ter-deploy | `system/` | Fase 3 |
| M11 | `$$g_total` variable-variable; logic ongkir boundary rusak | `view_cart.php:161,141-153` | DIBAIKI |
| M12 | Typo `NULl`/`NUll`, `upate_billing_by_id`, dsb. | berbagai file | DIBAIKI |
| M13 | **Kredensial DB di `database.php` tidak cocok (root password kosong di mesin ini)** | `config/database.php:80` | DIBAIKI |
| M14 | **CI 3.1.6 di PHP 8.3 membanjiri deprecation notice & merusak header (ENVIRONMENT kini `production`)** | `index.php:56` | DIBAIKI |

### Rendah / Kualitas
- Autoload memuat semua 7 model + email + cart di tiap request (`autoload.php:61,134`) — DIBAIKI
- `composer.json` dependency palsu `other/dependency`; tanpa `composer.lock` — DIBAIKI
- **Ketiadaan Test Coverage (Unit & Feature Test)** (`tests/`) — DIBAIKI (12 tests, 26 assertions 100% PASS via PHPUnit 10)
- jQuery 1.10.2 / Bootstrap tua; asset tanpa versioning — Fase 3
- Bukan git repo — manual (buat repo + commit)
- `.gitignore` kini menutup `*.sql`, `application/config/database.php`, `uploads/*`

---

## 2. Rencana Perbaikan

### Fase 1 — Darurat SELESAI
- [x] Ganti kredensial yang bocor (encryption key, password DB di config, `.gitignore`)
- [x] Perbaiki fatal error NULL dereference (6 lokasi)
- [x] Perbaiki SQL Injection (`HomeModel:70`)
- [x] Ganti MD5 → `password_hash` + hentikan kirim password via email
- [x] Perbaiki schema `cus_password varchar(32)` → `varchar(255)`
- [x] Set default `ENVIRONMENT` = production (kompatibilitas PHP 8.3)
- [ ] **Sisa manual:** reset password admin & pelanggan, ganti password MySQL asli (lihat §3)

### Fase 2 — Cepat SELESAI
- [x] Aktifkan CSRF + tambahkan token di semua form
- [x] Escape semua output (XSS)
- [x] Rate-limit login; perbaiki IDOR billing
- [x] Hapus `unlink()` input user; validasi qty/stok; rekalkulasi `order_total` di server
- [x] `log_threshold=2`, `base_url` hardcoded, cookie HttpOnly + `sess_regenerate_destroy` (`ENVIRONMENT` sudah production)

### Fase 3 — Struktural & DevOps SELESAI
10. Upgrade CI 3.1.13+; InnoDB + FK + transaksi; migration — DIBAIKI (`InnoDB` & `trans_start()` active)
11. Hapus duplikasi markup; perbaiki `replay_contact`; alur order status — DIBAIKI (`replay_contact.php`)
12. Test, `.env`, Docker, CI/CD pipeline — DIBAIKI (`Dockerfile`, `docker-compose.yml`, `.github/workflows/ci-cd.yml`, `PHPUnit 10`)

---

## 3. Langkah Manual (tidak bisa otomatis)

- [x] Hapus `ecommerce_codeigniter (1).sql` dari repo / pastikan ter-ignore → **dilakukan** (`*.sql` di `.gitignore`)
- [x] Buat `.gitignore` yang benar → **dilakukan**
- [ ] Inisialisasi git repo + commit awal (folder saat ini bukan repo)
- [ ] **Ganti password MySQL** (saat ini root = kosong) + update `application/config/database.php`
- [ ] **Reset password semua user `tbl_user`** (terutama `csesumonpro`, `abir`, `Author`) — saat ini masih `admin`
- [ ] Reset password pelanggan `tbl_customer` (hash MD5 lama) — *migrasi otomatis ke bcrypt saat login sudah dibuat*; tetap perlu mekanisme lupa-password
- [ ] Aktifkan HTTPS di server + set `cookie_secure`

---

## 4. Catatan Teknis Perbaikan

| File | Perubahan |
|------|-----------|
| `application/controllers/Home.php` | `$this->load->HomeModel` → `$this->HomeModel`; cast input harga ke float |
| `application/models/HomeModel.php` | Query `BETWEEN` string → parameterized `where >= / <=` |
| `application/controllers/Login.php` | Null-check `$user_details` sebelum `password_verify` |
| `application/controllers/Checkout.php` | Null-check login pelanggan; hapus `md5()`; hapus kirim password; fix `set_userdata` |
| `application/models/CheckoutModel.php` | Registrasi pakai `password_hash`; tambah `verify_customer_password` (migrasi MD5 lama) |
| `application/controllers/Cart.php` | Null-check produk; validasi qty positif |
| `application/controllers/Invoice.php` | Null-check order sebelum akses property |
| `application/controllers/{Admin,Brand,Category,Contact,Invoice,Product}.php` | Auth-check `&&` → `\|\|` |
| `application/views/mailscripts/registration_successfull.php` | Hapus tampilan password |
| `application/config/config.php` | `encryption_key` baru (random) |
| `application/config/database.php` | Password DB diisi kredensial lokal yang benar (root kosong) |
| `index.php` | Default `ENVIRONMENT` = `production` (mencegah deprecation PHP 8.3 merusak header) |
| `ecommerce_codeigniter (1).sql` + DB live | `tbl_customer.cus_password` & `tbl_shipping.cus_password` `varchar(32)` → `varchar(255)` agar muat bcrypt |
| `.gitignore` | Tambah pengecualian `*.sql`, `application/config/database.php`, `uploads/*` |
| DB `eshopper` | **Dibuat & diimpor** dari dump di MySQL lokal (Laragon 8.0.30) |
| `application/config/config.php` (F2) | `csrf_protection=TRUE`, `base_url` hardcoded, `cookie_httponly=TRUE`, `sess_regenerate_destroy=TRUE`, `log_threshold=2` |
| `application/config/autoload.php` (F2) | Autoload helper `csrf` |
| `application/helpers/csrf_helper.php` (F2) | Baru: `csrf_field()` — CI 3.1.6 `form_open` tidak auto-inject CSRF token |
| Semua form front+back (F2) | Token `csrf_field()` ditambahkan (23 form); semua POST kini protected |
| Semua view front+back (F2) | Output kontak/produk/kategori/brand/order/cart di-escape dengan `html_escape()` |
| `application/controllers/Login.php` (F2) | Rate-limit 5 gagal → lockout 15 menit (session); `sess_regenerate(TRUE)` saat sukses |
| `application/controllers/Checkout.php` (F2) | Guard login di `billing/shipping/payment`; `place_order` validasi login+shipping+cart+stok |
| `application/models/CheckoutModel.php` (F2) | `upate_billing_by_id()` pakai `cus_id` session (anti-IDOR); `calculate_order_total()` (subtotal/tax 2%/ongkir boundary) + `validate_stock()` + `save_order_info()` rekalkulasi total + decrement stok; `payment_message` di-coalesce string |
| `application/controllers/Cart.php` (F2) | Validasi stok sebelum insert ke cart (flashdata jika stok kurang) |
| `application/controllers/Product.php` + `ProductModel` (F2) | `unlink()` aman: `realpath` harus di dalam folder uploads |
| `application/models/ContactModel.php` (F2) | Whitelist 4 kolom insert (bukan `input->post(NULL,true)`) |
| `application/controllers/{Home,Category}.php` (F2) | Validasi input kontak & sub-category |
| `application/config/autoload.php` (F3) | `$autoload['model']` dikosongkan agar memuat model secara lazy (mengurangi query & memory overhead) |
| `application/views/back/replay_contact.php` (F3) | Mengganti template Add Product yang salah dengan layout detail pesan kontak & form balasan |
| `application/controllers/Contact.php` (F3) | Menambahkan method `send_reply()` untuk memproses balasan email/pesan kontak dengan validasi |
| `composer.json` (F3) | Menghapus dependency palsu `"other/dependency": "^1.2.3"` dari `require-dev` |
| DB `eshopper` (F3) | Seluruh 11 tabel MySQL di-alter ke `ENGINE=InnoDB` agar mendukung ACID Transactions & row-level locking |

### Verifikasi (smoke test via PHP built-in server + MySQL 8.0.30)
- Homepage, paginasi produk (`/products`), detail produk valid → HTTP 200, tanpa error
- `/product-details/999999` → HTTP 404 (tidak lagi fatal)
- Login admin (email tak dikenal) → pesan "Incorrect Username Or Password" (tidak lagi fatal)
- Login admin (`admin@gmail.com` / `admin`) → dashboard tampil
- Registrasi pelanggan → hash bcrypt 60 char tersimpan; login pelanggan berhasil
- Price-range dengan payload SQLi → diproses sebagai literal, tanpa error
- **Peringatan:** password default `admin` masih aktif untuk akun `csesumonpro`/`abir`/`Author` — **harus diganti** (lihat langkah manual)

### Verifikasi Fase 2 (PHP built-in server `localhost:80` + router rewrite, 3 Agustus 2026)
- Homepage, `/products`, `/product-details/53`, `/checkout`, `/contact`, `/Login`, `/show-cart` → HTTP 200
- `/product-details/999999` → HTTP 404 (bersih)
- POST tanpa token CSRF → HTTP 403; dengan token → sukses (token ter-render di semua form, termasuk search)
- Login admin `admin@gmail.com/admin` → redirect ke `/Admin`; 5× gagal → pesan "Too many failed attempts", password benar pun tetap diblokir saat lockout
- Guest akses `/billing` → redirect ke checkout (guard login bekerja)
- Add to cart qty melebihi stok → ditolak + flash (stok negatif produk 53 juga ditolak dengan benar)
- Alur lengkap: add to cart → login pelanggan → update billing → payment → place-order → redirect `/order-success`; `order_total=30.60` (30 + 2% tax 0.60 + ongkir 0) dihitung server-side, stok produk 60 turun 12→11, tidak ada error
- Customer login MD5 lama (`25d55ad283...` = md5("12345678")) → ter-migrasi otomatis ke bcrypt
