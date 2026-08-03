# 08. Testing & QA Documentation - E-Shopper

## 1. Strategi Pengujian (Testing Strategy)
Sistem **E-Shopper** menerapkan strategi pengujian berlapis yang mengombinasikan **Automated Unit & Feature Testing** menggunakan **PHPUnit 10** serta **Manual Quality Assurance (QA) & User Acceptance Testing (UAT)**.

 Seluruh pengujian otomatis dijalankan secara terisolasi tanpa mengotori database MySQL produksi melalui fitur *Database Transaction Rollback* pada [`tests/TestCase.php`](file:///d:/laragon/www/eshopper/tests/TestCase.php).

---

## 2. Cakupan Automated Tests (PHPUnit 10)

```
tests/
├── unit/
│   ├── CheckoutModelTest.php     # Test kalkulasi order, stok, & password migration
│   └── SecurityHelperTest.php    # Test verifikasi CSRF, XSS escape, & path traversal
└── feature/
    ├── HomeFeatureTest.php       # Test HTTP status 200, 404 non-fatal, & SQLi safety
    └── SecurityFeatureTest.php   # Test penolakan POST tanpa CSRF & Rate Limiting lockout
```

### Rincian 12 Test Cases (100% PASS)

| No | Nama Test | Tipe | Komponen | Status |
|:---:|:---|:---:|:---|:---:|
| 1 | `test_calculate_order_total_with_free_shipping` | Unit | Subtotal $\ge \$30$ $\rightarrow$ Free Shipping | PASS |
| 2 | `test_calculate_order_total_with_shipping_fee` | Unit | Subtotal $<\$30$ $\rightarrow$ Shipping $\$15$ + Tax 2% | PASS |
| 3 | `test_stock_validation_pass_and_fail` | Unit | Validasi batas atas stok & penolakan qty negatif | PASS |
| 4 | `test_customer_password_migration_from_md5_to_bcrypt` | Unit | Auto-upgrading hash MD5 ke Bcrypt saat login | PASS |
| 5 | `test_csrf_token_verification` | Unit | Verifikasi token CSRF & penolakan tampered token | PASS |
| 6 | `test_xss_html_escape_sanitization` | Unit | Sanitasi `<script>` XSS via `html_escape()` | PASS |
| 7 | `test_safe_file_deletion_path_validation` | Unit | Blokir path traversal `../..` pada `unlink()` | PASS |
| 8 | `test_homepage_returns_http_200` | Feature | Verifikasi respons HTTP 200 beranda | PASS |
| 9 | `test_invalid_product_details_id_returns_404_not_fatal` | Feature | ID produk 999999 $\rightarrow$ HTTP 404 bersih | PASS |
| 10 | `test_price_range_filter_handles_sqli_payload_safely` | Feature | Casting float aman dari SQL Injection payload | PASS |
| 11 | `test_post_request_without_csrf_token_is_rejected` | Feature | POST tanpa token ditolak oleh helper guard | PASS |
| 12 | `test_admin_login_rate_limiting_lockout_after_five_attempts` | Feature | Lockout 15 menit setelah 5x login gagal | PASS |

---

### Eksekusi Otomatis via CI/CD Pipeline
Seluruh test suite juga diuji secara otomatis pada GitHub Actions workflow ([`.github/workflows/ci-cd.yml`](file:///d:/laragon/www/eshopper/.github/workflows/ci-cd.yml)) setiap kali terdapat *push* atau *pull request* ke branch `main`.

### Jalankan Secara Lokal
```bash
vendor/bin/phpunit
```

### Jalankan Suite Spesifik (Unit atau Feature)
```bash
vendor/bin/phpunit --testsuite Unit
vendor/bin/phpunit --testsuite Feature
```

---

## 4. Matriks User Acceptance Testing (UAT)

| Skenario UAT | Langkah Pengujian | Ekspektasi Hasil | Status |
|:---|:---|:---|:---:|
| **UAT-01: Pembelian Produk** | Pilih produk $\rightarrow$ Add to Cart $\rightarrow$ Login $\rightarrow$ Shipping $\rightarrow$ Place Order | Order masuk ke DB, total dihitung benar, stok berkurang | Lolos |
| **UAT-02: Proteksi Lockout** | Masukkan password admin salah 5x berturut-turut | Muncul pesan "Too many failed attempts" dan diblokir 15 menit | Lolos |
| **UAT-03: Filter Katalog** | Pilih brand "Asus" dan tentukan min price | Hanya produk brand Asus yang berada di range harga tersebut yang tampil | Lolos |
