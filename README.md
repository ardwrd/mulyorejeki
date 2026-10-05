# Mulyorejeki

Website katalog produk untuk Mulyorejeki, toko teknik dan peralatan kerja.

## Stack

- CodeIgniter 4.7.4
- PHP 8.2+
- Bootstrap 5
- Bootstrap Icons
- MySQL/MariaDB (tahap berikutnya)
- Cloudflare R2 untuk gambar produk (tahap berikutnya)

## Branch

- `main` — source aplikasi production
- `feature/ci4-bootstrap` — integrasi CodeIgniter yang sedang dikerjakan
- `gh-pages` — preview statis UI di GitHub Pages

## Menjalankan lokal

```bash
composer install
cp env .env
php spark serve
```

Buka `http://localhost:8080`.

## Status saat ini

UI landing page, katalog, dan detail produk sudah dipindahkan ke CodeIgniter Views. Data produk masih berupa dummy data di `app/Libraries/CatalogData.php` agar UI dapat dikerjakan tanpa database terlebih dahulu.

Tahap berikutnya adalah migrasi MySQL, model produk/kategori/brand, admin CRUD, dan integrasi Cloudflare R2.
