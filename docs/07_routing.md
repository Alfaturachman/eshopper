# 07. Routing & API Documentation - E-Shopper

## 1. Skema Routing Aplikasi
Seluruh alur URL dikelola melalui [`application/config/routes.php`](file:///d:/laragon/www/eshopper/application/config/routes.php) untuk memetakan endpoint publik dan admin ke controller yang sesuai.

---

## 2. Daftar Route Endpoints

### 2.1 Halaman Publik & Katalog (Front-End)
| Endpoint | Method | Controller & Action | Keterangan |
|:---|:---:|:---|:---|
| `/` | `GET` | `Home/index` | Halaman beranda utama |
| `/products` | `GET` | `Home/productpage` | Katalog produk terpaginasi |
| `/product-details/(id)` | `GET` | `Home/product_details/$1` | Detail produk rinci |
| `/show-post-by-brand-id/(id)` | `GET` | `Home/show_post_by_brand_id/$1` | Filter produk berdasarkan brand |
| `/show-post-by-sub-cat-id/(id)` | `GET` | `Home/show_post_by_sub_cat_id/$1` | Filter produk berdasarkan sub-kategori |
| `/show-product-by-price-range` | `POST` | `Home/show_product_by_price_range` | Filter produk berdasarkan range harga |
| `/search` | `POST` | `Search/index` | Pencarian produk via kata kunci |
| `/contact` | `GET` | `Home/contact_page` | Halaman form kontak pelanggan |
| `/contact-form` | `POST` | `Home/insert_contact_info` | Submit pesan kontak baru |

### 2.2 Modul Keranjang Belanja (Cart)
| Endpoint | Method | Controller & Action | Keterangan |
|:---|:---:|:---|:---|
| `/show-cart` | `GET` | `Cart/show_cart` | Tampilan rincian keranjang |
| `/add-to-cart` | `POST` | `Cart/add_to_cart` | Tambah barang ke keranjang |
| `/update-cart-qty` | `POST` | `Cart/update_cart_quantity` | Update kuantitas item cart |
| `/delete-to-cart/(rowid)` | `GET` | `Cart/delete_to_cart/$1` | Hapus barang dari keranjang |

### 2.3 Modul Checkout & Autentikasi Pelanggan
| Endpoint | Method | Controller & Action | Keterangan |
|:---|:---:|:---|:---|
| `/checkout` | `GET` | `Checkout/checkout` | Halaman login/register checkout |
| `/customer-registration` | `POST` | `Checkout/customer_registration` | Pendaftaran akun pelanggan |
| `/customer-login` | `POST` | `Checkout/customer_login` | Login akun pelanggan |
| `/billing` | `GET` | `Checkout/billing` | Form billing data pelanggan |
| `/shipping` | `GET` | `Checkout/shipping` | Form shipping alamat pengiriman |
| `/payment` | `GET` | `Checkout/payment` | Halaman konfirmasi pembayaran |
| `/place-order` | `POST` | `Checkout/place_order` | Eksekusi pembuatan order |
| `/order-success` | `GET` | `Checkout/order_success` | Halaman sukses pesanan |
| `/logout` | `GET` | `Checkout/customer_logout` | Logout sesi pelanggan |

### 2.4 Modul Admin Panel (Back-End)
| Endpoint | Method | Controller & Action | Keterangan |
|:---|:---:|:---|:---|
| `/dashboard` | `GET` | `Admin/admindashboard` | Dashboard utama admin |
| `/add-product` | `GET` | `Product/add_product_form` | Form tambah produk |
| `/save-product` | `POST` | `Product/insert_product` | Simpan produk baru |
| `/product-list` | `GET` | `Product/show_product_list` | Tabel daftar seluruh produk |
| `/category-list` | `GET` | `Category/show_category_list` | Tabel daftar kategori |
| `/brand-list` | `GET` | `Brand/show_brand_list` | Tabel daftar brand |
| `/contact-message-list` | `GET` | `Contact/get_all_contact_message` | Daftar pesan kontak masuk |
| `/replay-contact/(id)` | `GET` | `Contact/replay_contact_by_id/$1` | Form balas pesan kontak |

---

## 3. Format Response & HTTP Status Codes

| Status Code | Arti | Skenario |
|:---:|:---|:---|
| **200 OK** | Request berhasil | Halaman atau query berhasil disajikan |
| **302 Found** | Redirect HTTP | Redirect setelah login, logout, atau add to cart |
| **403 Forbidden** | CSRF / Auth Error | Token CSRF tidak valid atau request POST ditolak |
| **404 Not Found** | Halaman tidak ada | ID produk tidak ditemukan di database (`_404_page`) |
| **500 Internal Error**| Error server | Ditangani oleh global error handler tanpa mengekspos stack trace |
