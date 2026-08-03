# 06. Design System & UI/UX Guidelines - E-Shopper

## 1. Prinsip Desain UI/UX
Tampilan **E-Shopper** mengusung filosofi antarmuka retail modern yang bersih, kontras, dan efisien:
1. **Clarity & Visual Hierarchy**: Menyoroti harga, tombol *Add to Cart*, dan status ketersediaan stok produk secara transparan.
2. **Responsive Grid**: Layout 12-kolom Bootstrap yang beradaptasi secara mulus di perangkat desktop, tablet, dan smartphone.
3. **Feedback Serta-Merta**: Menggunakan pesan Flashdata (Sukses, Peringatan, Lockout) untuk memberikan konfirmasi instan atas aksi pengguna.

---

## 2. Palette Warna & Tipografi

### Palet Warna Utama
* **Primary Accent (Orange)**: `#FE980F` (Digunakan untuk highlight harga, badge kategori, dan tombol utama *Add to cart* / *Checkout*).
* **Dark Background / Text**: `#363432` (Teks utama & header navigasi admin/front).
* **Light Neutral Gray**: `#F0F0E6` (Latar belakang container kartu produk & form).
* **Success Green**: `#4CAF50` (Alert transaksi & status ketersediaan stok).
* **Danger Red**: `#D9534F` (Pesan kesalahan validasi, lockout, & stok habis).

### Tipografi
* **Font Family**: `'Roboto', sans-serif`, `'Open Sans', sans-serif`
* **Heading 1**: 24px Bold (Header halaman & judul produk)
* **Body Text**: 14px Regular (Deskripsi & label form)

---

## 3. Komponen UI Utama

### 3.1 Kartu Produk (Product Card Component)
Komponen kartu produk dirancang seragam di seluruh halaman (*Featured Items*, *Category Filter*, *Search Results*):
* **Elemen**: Gambar Produk, Label Harga (Bold Orange), Judul Produk, Tombol *Add to cart* (dengan Icon Keranjang), Tombol *View Detail*.
* **Status**: Menampilkan badge overlay *Out of Stock* jika `pro_quantity <= 0`.

### 3.2 Form Controls & Security Badges
* Seluruh input form menyertakan pembatas `required` HTML5 dan pesan eror validasi berwarna merah.
* Setiap form POST terintegrasi secara transparan dengan hidden input `<input type="hidden" name="csrf_test_name" value="...">`.
