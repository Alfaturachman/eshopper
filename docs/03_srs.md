# 03. Software Requirements Specification (SRS) - E-Shopper

## 1. Pendahuluan
Dokumen ini mendeskripsikan spesifikasi kebutuhan teknis perangkat lunak untuk sistem **E-Shopper**, mencakup fungsi-fungsi internal, modul aplikasi, dan standar kualitas non-fungsional.

---

## 2. Kebutuhan Fungsional (Functional Requirements)

### 2.1 Modul Authentication & Authorization
* **FR-01**: Sistem harus mengamankan password akun menggunaan algoritma `password_hash()` (Bcrypt dengan cost factor 10).
* **FR-02**: Sistem harus memproteksi route admin (`/dashboard`, `/add-product`, `/category-list`, dll.) dan mengarahkan pengguna non-admin ke halaman login.
* **FR-03**: Sistem harus menyediakan mekanisme migrasi otomatis kata sandi dari hash MD5 lama ke Bcrypt saat pelanggan melakukan login berhasil.
* **FR-04**: Sistem harus menerapkan *Session Regeneration* (`sess_regenerate(TRUE)`) setelah autentikasi berhasil untuk mencegah *Session Fixation*.

### 2.2 Modul Katalog & Inventaris
* **FR-05**: Sistem harus menampilkan daftar produk terpaginasi (paged catalog).
* **FR-06**: Sistem harus mendukung pencarian produk berdasarkan kata kunci (*title/description*) dan filter dinamis rentang harga.
* **FR-07**: Sistem harus memotong kuantitas stok produk (`pro_quantity`) secara otomatis dan atomic ketika pemesanan dikonfirmasi (*place order*).

### 2.3 Modul Transaksi & Checkout
* **FR-08**: Sistem harus memvalidasi ketersediaan stok barang sebelum dimasukkan ke keranjang maupun saat pemrosesan akhir checkout.
* **FR-09**: Sistem harus melakukan rekalkulasi total tagihan secara mutlak di sisi server (Server-Side Recalculation) dan mengabaikan nilai total yang dikirim dari klien.
* **FR-10**: Pemrosesan *place order* harus dibungkus dalam *Database Transaction* (`trans_start()` / `trans_complete()`) untuk menjamin konsistensi data.

---

## 3. Kebutuhan Non-Fungsional (Non-Functional Requirements)

### 3.1 Performa & Efisiensi
* **NFR-01**: *Time to First Byte* (TTFB) halaman publik tidak boleh melebihi $1.5$ detik pada server standar.
* **NFR-02**: Pemanggilan model database harus bersifat *lazy-loaded* pada controller terkait untuk menghemat konsumsi memori RAM server.

### 3.2 Keamanan (Security)
* **NFR-03**: Semua form submit berbasis POST wajib diproteksi dengan token CSRF dinamis.
* **NFR-04**: Seluruh variabel output dinamis pada view wajib di-escape menggunakan `html_escape()` untuk mencegah *Stored & Reflected Cross-Site Scripting* (XSS).
* **NFR-05**: Operasi penghapusan file gambar (`unlink`) wajib divalidasi agar tidak dapat menembus direktori luar (*Path Traversal Protection*).

### 3.3 Reliability & Database Integrity
* **NFR-06**: Seluruh tabel MySQL wajib menggunakan Storage Engine `InnoDB` untuk mendukung ACID Compliance dan *Row-Level Locking*.
