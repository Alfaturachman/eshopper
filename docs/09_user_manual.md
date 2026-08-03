# 09. User & Admin Manual - E-Shopper

## 1. Panduan Penggunaan Bagi Pelanggan (User Manual)

### 1.1 Menjelajahi Katalog & Mencari Produk
1. Buka halaman utama aplikasi di URL `http://localhost/eshopper`.
2. Gunakan bilah pencarian (*Search Bar*) di bagian atas atau sidebar kiri untuk memfilter produk berdasarkan **Kategori**, **Sub-Kategori**, atau **Brand**.
3. Klik pada gambar atau tombol **View Detail** untuk membaca deskripsi rinci dan melihat ketersediaan stok barang.

### 1.2 Mengelola Keranjang Belanja & Checkout
1. Pada halaman detail produk atau daftar barang, klik tombol **Add to cart**.
2. Anda akan diarahkan ke halaman **Rincian Keranjang** (`/show-cart`). Masukkan jumlah kuantitas yang diinginkan (sistem tidak memperkenankan angka melebihi stok yang tersedia).
3. Klik **Proceed to Checkout**. Jika Anda belum memiliki akun, isi form pendaftaran di sebelah kanan; jika sudah memiliki akun, lakukan login.
4. Lengkapi rincian **Billing Information** dan **Shipping Address**.
5. Pilih metode pembayaran (misal: Cash on Delivery / Bank Transfer), lalu klik **Place Order**.
6. Halaman konfirmasi pemesanan sukses (`/order-success`) akan menampilkan rincian akhir tagihan Anda.

---

## 2. Panduan Penggunaan Bagi Admin (Admin Manual)

### 2.1 Access & Login Admin Panel
1. Buka URL `http://localhost/eshopper/login`.
2. Masukkan alamat email admin (`admin@gmail.com`) dan kata sandi Anda.
3. *Catatan Keamanan*: Apabila terjadi 5x kesalahan kata sandi berturut-turut, akses login akan dikunci (*lockout*) otomatis selama 15 menit.

### 2.2 Kelola Inventaris Produk (Add / Edit Product)
1. Pilih menu **Product** $\rightarrow$ **Add Product** pada sidebar admin.
2. Isi judul produk, deskripsi, pilih kategori, sub-kategori, dan brand pendukung.
3. Masukkan harga produk (numerik) dan jumlah kuantitas stok awal (`pro_quantity`).
4. Unggah foto produk utama (format JPG/PNG), lalu klik **Save**.
5. Untuk melihat atau menghapus produk, buka menu **Product List**.

### 2.3 Membalas Pesan Kontak Pelanggan (Contact Message Reply)
1. Pilih menu **Message List** pada sidebar admin.
2. Klik tombol **View/Reply** di sebelah pesan pelanggan yang ingin ditindaklanjuti.
3. Sistem akan menampilkan rincian nama pengirim, email, dan isi pesan asal.
4. Tuliskan pesan balasan Anda pada kolom **Reply Message**, lalu klik **Send Reply**.
