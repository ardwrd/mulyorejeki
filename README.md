# Mulyorejeki

Website katalog produk Mulyorejeki — toko teknik dan peralatan industri.

## UI Prototype v1

Tahap ini fokus pada antarmuka publik terlebih dahulu sebelum integrasi CodeIgniter 4, database MySQL/MariaDB, dan Cloudflare R2.

### Halaman

- `index.html` — landing page / homepage
- `catalog.html` — katalog produk dengan pencarian dan filter kategori
- `product.html` — detail produk dan spesifikasi
- `assets/css/styles.css` — visual system dan responsive layout
- `assets/js/main.js` — pencarian dan filter katalog

### Arah visual

Industrial catalog yang bersih dan fungsional: charcoal, off-white, safety orange, grid teknis, tipografi tegas, dan tanpa dekorasi berlebihan.

### Menjalankan prototype

Buka `index.html` langsung di browser atau gunakan static web server lokal.

Contoh:

```bash
python -m http.server 8080
```

Lalu buka `http://localhost:8080`.

## Tahap berikutnya

Setelah UI disetujui, prototype akan dikonversi menjadi views CodeIgniter 4 dan dilanjutkan dengan CRUD katalog, MySQL/MariaDB, serta penyimpanan gambar di Cloudflare R2.
