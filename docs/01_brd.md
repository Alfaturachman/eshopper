# 01. Business Requirements Document (BRD) - E-Shopper

## 1. Latar Belakang & Visi Proyek
Proyek **E-Shopper** adalah platform e-commerce berbasis web yang dirancang untuk menyediakan pengalaman berbelanja online yang intuitif, aman, dan cepat bagi pembeli, sekaligus memberikan kemudahan bagi pengelola bisnis (admin) dalam mengelola katalog produk, transaksi, dan komunikasi dengan pelanggan.

Visi proyek ini adalah menghadirkan solusi e-commerce yang handal dengan arsitektur yang aman dan berkinerja tinggi, mampu menangani transaksi ritel secara real-time dan terintegrasi.

---

## 2. Tujuan & Sasaran Bisnis
1. **Meningkatkan Penjualan Ritel Online**: Menyediakan kanal penjualan 24/7 bagi pelanggan untuk menjelajahi katalog produk, melakukan pemesanan, dan menyelesaikan pembayaran secara mandiri.
2. **Efisiensi Operasional Toko**: Memangkas proses manual dalam pencatatan stok dan transaksi melalui otomatisasi sistem keranjang belanja dan inventaris.
3. **Peningkatan Kepuasan Pelanggan**: Menyediakan antarmuka yang responsif, pencarian produk yang cepat, kalkulasi biaya transparan (pajak & ongkir), serta keamanan data pribadi pelanggan.
4. **Keamanan & Kepatuhan Data**: Memastikan seluruh data pengguna, kredensial, dan transaksi aman dari ancaman kejahatan siber (OWASP Top 10 compliance).

---

## 3. Key Performance Indicators (KPI)
| Indikator Kinerja (KPI) | Target Kinerja |
|:---|:---|
| **Uptime Aplikasi** | 99.9% ketersediaan server |
| **Response Time (TTFB)** | $< 1.5$ detik untuk halaman katalog & checkout |
| **Konversi Checkout** | $\ge 15\%$ pembeli berhasil hingga tahap *place order* |
| **Skor Keamanan Sistem** | $\ge 90/100$ berdasarkan audit OWASP |
| **Cakupan Testing (PHPUnit)** | 100% PASS pada unit & feature test core logic |

---

## 4. Pemangku Kepentingan (Stakeholder Analysis)
* **Pelanggan (Customer)**: Pengguna akhir yang mencari produk, menambah barang ke keranjang, dan melakukan transaksi pembayaran.
* **Administrator Toko (Admin)**: Pengelola sistem yang memiliki hak akses penuh untuk mengelola barang (CRUD produk, kategori, brand), melihat laporan pemesanan, serta membalas pesan pelanggan.
* **Tim Tim Pengembang (Developers & QA)**: Penanggung jawab pemeliharaan kode, refactoring, keamanan, dan deployment aplikasi.
