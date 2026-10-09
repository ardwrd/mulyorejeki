<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="admin-page-heading">
  <div>
    <div class="admin-eyebrow">RINGKASAN TOKO</div>
    <h1>Ringkasan katalog</h1>
    <p>Lihat jumlah barang yang tercatat dan yang sudah tayang.</p>
  </div>
  <a href="<?= site_url('admin/products/new') ?>" class="btn btn-accent"><i class="bi bi-plus-lg me-1"></i> Tambah Produk</a>
</div>

<div class="row g-3 mb-4">
  <div class="col-6 col-xl-3"><div class="admin-card admin-stat"><div class="admin-stat-label">Total produk <i class="bi bi-box-seam"></i></div><div class="admin-stat-value"><?= esc((string) $productCount) ?></div></div></div>
  <div class="col-6 col-xl-3"><div class="admin-card admin-stat"><div class="admin-stat-label">Tayang <i class="bi bi-check-circle"></i></div><div class="admin-stat-value"><?= esc((string) $activeCount) ?></div></div></div>
  <div class="col-6 col-xl-3"><div class="admin-card admin-stat"><div class="admin-stat-label">Kategori <i class="bi bi-grid"></i></div><div class="admin-stat-value"><?= esc((string) $categoryCount) ?></div></div></div>
  <div class="col-6 col-xl-3"><div class="admin-card admin-stat"><div class="admin-stat-label">Merek <i class="bi bi-tags"></i></div><div class="admin-stat-value"><?= esc((string) $brandCount) ?></div></div></div>
</div>

<div class="row g-3">
  <div class="col-xl-8">
    <section class="admin-card admin-list admin-recent-list h-100" aria-labelledby="recent-products-title">
      <div class="admin-section-heading">
        <h2 id="recent-products-title">Produk terbaru</h2>
        <a href="<?= site_url('admin/products') ?>" class="small text-decoration-none">Lihat semua</a>
      </div>
      <?php if ($recentProducts === []): ?>
        <div class="admin-empty"><p class="mb-0">Belum ada produk. Mulai dengan menambah produk pertama.</p></div>
      <?php else: ?>
        <?php foreach ($recentProducts as $product): ?>
          <div class="admin-term-row">
            <span class="admin-term-icon"><i class="bi bi-box-seam"></i></span>
            <div class="admin-term-details">
              <div class="admin-term-title"><h2><?= esc($product['name']) ?></h2><span class="admin-status <?= $product['is_active'] ? 'is-active' : 'is-inactive' ?>"><?= $product['is_active'] ? 'Aktif' : 'Nonaktif' ?></span></div>
              <p><?= esc($product['category_name']) ?><?php if ($product['sku']): ?> · Kode <?= esc($product['sku']) ?><?php endif ?></p>
            </div>
            <div class="admin-term-actions"><a href="<?= site_url('admin/products/' . $product['id'] . '/edit') ?>" class="btn btn-outline-dark btn-sm">Edit</a></div>
          </div>
        <?php endforeach ?>
      <?php endif ?>
    </section>
  </div>
  <div class="col-xl-4">
    <section class="admin-card admin-form-card h-100" aria-labelledby="quick-actions-title">
      <h2 id="quick-actions-title">Kelola katalog</h2>
      <p class="text-secondary small mb-3"><?= esc((string) $inactiveCount) ?> produk belum tayang di katalog.</p>
      <div class="d-grid gap-2">
        <a href="<?= site_url('admin/products/new') ?>" class="btn btn-accent text-start"><i class="bi bi-plus-lg me-2"></i> Tambah produk</a>
        <a href="<?= site_url('admin/categories') ?>" class="btn btn-outline-dark text-start"><i class="bi bi-grid me-2"></i> Kelola kategori</a>
        <a href="<?= site_url('admin/brands') ?>" class="btn btn-outline-dark text-start"><i class="bi bi-tags me-2"></i> Kelola merek</a>
      </div>
    </section>
  </div>
</div>

<?= $this->endSection() ?>
