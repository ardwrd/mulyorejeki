<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
  <div>
    <h1 class="h3 fw-bold mb-1">Dashboard</h1>
    <p class="text-secondary mb-0">Ringkasan katalog Mulyorejeki.</p>
  </div>
  <a href="<?= site_url('admin/products/new') ?>" class="btn btn-accent"><i class="bi bi-plus-lg me-1"></i> Tambah Produk</a>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="admin-card p-4 h-100">
      <div class="text-secondary small mb-2">Produk</div>
      <div class="display-6 fw-bold"><?= esc((string) $productCount) ?></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="admin-card p-4 h-100">
      <div class="text-secondary small mb-2">Kategori</div>
      <div class="display-6 fw-bold"><?= esc((string) $categoryCount) ?></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="admin-card p-4 h-100">
      <div class="text-secondary small mb-2">Merek</div>
      <div class="display-6 fw-bold"><?= esc((string) $brandCount) ?></div>
    </div>
  </div>
</div>

<div class="admin-card p-4">
  <h2 class="h5 fw-bold mb-2">Alur pengelolaan</h2>
  <p class="text-secondary mb-0">Tambah atau edit produk dari menu Produk. Gambar dapat disimpan lokal selama development dan dialihkan ke Cloudflare R2 melalui konfigurasi environment tanpa mengubah controller.</p>
</div>

<?= $this->endSection() ?>
