# Mulyorejeki

Website katalog produk untuk Mulyorejeki, toko teknik dan peralatan kerja.

## Stack

- CodeIgniter 4.7.4
- PHP 8.2+
- Bootstrap 5 + Bootstrap Icons
- MySQL/MariaDB untuk data katalog dan admin
- Cloudflare R2 (S3-compatible) untuk gambar produk
- AWS SDK for PHP untuk koneksi S3/R2

## Branch

- `main` — baseline aplikasi yang sudah di-merge
- `feature/catalog-backend` — database, admin CRUD, dan object storage
- `gh-pages` — preview statis UI; bukan backend aplikasi

## Menjalankan lokal

```bash
composer install
cp .env.example .env
php spark migrate --all
php spark db:seed DatabaseSeeder
php spark serve
```

Buka `http://localhost:8080` untuk website dan `http://localhost:8080/admin/login` untuk panel admin.

Sebelum menjalankan seeder, ubah nilai berikut di `.env`:

```dotenv
admin.seed.name = 'Administrator'
admin.seed.email = 'admin@domainanda.com'
admin.seed.password = 'PASSWORD_YANG_KUAT'
```

Seeder admin hanya membuat atau memperbarui akun jika ketiga nilai tersebut terisi. Password tidak disimpan di repository; database hanya menyimpan hasil `password_hash()`.

## Database

Database aplikasi tetap dapat berada di hosting/server MySQL atau MariaDB. Struktur database dibawa bersama source code menggunakan CodeIgniter migrations, sehingga saat pindah server cukup mengatur koneksi database lalu menjalankan:

```bash
php spark migrate --all
```

Data dummy awal untuk kategori, merek, dan produk dapat dimasukkan dengan:

```bash
php spark db:seed CatalogSeeder
```

Selama tabel katalog belum dibuat, halaman publik masih memiliki fallback ke `app/Libraries/CatalogData.php`, sehingga UI development tidak langsung rusak. Setelah migrations dijalankan, katalog otomatis membaca database.

## Gambar produk

### Development lokal

Default:

```dotenv
storage.driver = 'local'
```

File tersimpan di `public/uploads/products/...` dan path upload sudah diabaikan Git.

### Cloudflare R2

Isi konfigurasi:

```dotenv
storage.driver = 'r2'
storage.r2.endpoint = 'https://<ACCOUNT_ID>.r2.cloudflarestorage.com'
storage.r2.bucket = 'mulyorejeki'
storage.r2.accessKey = '...'
storage.r2.secretKey = '...'
storage.r2.region = 'auto'
storage.r2.publicBaseUrl = 'https://cdn.example.com'
```

`storage.r2.publicBaseUrl` sebaiknya menggunakan custom domain/public bucket URL yang memang dapat dibuka oleh pengunjung. Kredensial R2 hanya disimpan di `.env` server dan tidak boleh di-commit.

## Admin

Panel admin saat ini mencakup:

- login/logout berbasis session
- CSRF protection untuk request form
- dashboard ringkas
- tambah, edit, hapus produk
- status aktif/featured
- kategori dan merek sebagai relasi produk
- spesifikasi produk berbasis key/value
- upload JPG/PNG/WebP maksimal 5 MB
- multi image, pilih gambar utama, dan hapus gambar
- storage lokal atau Cloudflare R2 tanpa mengubah controller

## Deployment minimum

Document root web server harus diarahkan ke folder `public/`, bukan root repository. Untuk production gunakan `.env` production, database user dengan privilege minimum, HTTPS, dan kredensial R2 terpisah dari source code.
