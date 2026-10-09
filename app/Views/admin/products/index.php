<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<?php
$hasFilters = $filters['q'] !== '' || $filters['status'] !== '' || $filters['category'] > 0;
$pageUrl = static function (int $target) use ($filters): string {
    $query = array_filter([
        'q' => $filters['q'],
        'status' => $filters['status'],
        'category' => $filters['category'] > 0 ? $filters['category'] : null,
        'page' => $target > 1 ? $target : null,
    ], static fn ($value): bool => $value !== null && $value !== '');

    return site_url('admin/products') . ($query === [] ? '' : '?' . http_build_query($query));
};
?>

<div class="admin-page-heading">
  <div>
    <div class="admin-eyebrow">KATALOG</div>
    <h1>Produk</h1>
    <p>Ubah informasi barang, foto, dan status tampilnya di katalog.</p>
  </div>
  <a href="<?= site_url('admin/products/new') ?>" class="btn btn-accent"><i class="bi bi-plus-lg me-1"></i> Tambah Produk</a>
</div>

<form action="<?= site_url('admin/products') ?>" method="get" class="admin-card admin-toolbar" role="search">
  <div>
    <label for="q" class="form-label">Cari produk</label>
    <input id="q" type="search" name="q" class="form-control" value="<?= esc($filters['q']) ?>" placeholder="Nama, SKU, atau slug">
  </div>
  <div>
    <label for="category" class="form-label">Kategori</label>
    <select id="category" name="category" class="form-select">
      <option value="">Semua kategori</option>
      <?php foreach ($categories as $category): ?>
        <option value="<?= esc((string) $category['id']) ?>" <?= $filters['category'] === (int) $category['id'] ? 'selected' : '' ?>><?= esc($category['name']) ?></option>
      <?php endforeach ?>
    </select>
  </div>
  <div>
    <label for="status" class="form-label">Status</label>
    <select id="status" name="status" class="form-select">
      <option value="">Semua status</option>
      <option value="active" <?= $filters['status'] === 'active' ? 'selected' : '' ?>>Aktif</option>
      <option value="inactive" <?= $filters['status'] === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
    </select>
  </div>
  <button type="submit" class="btn btn-outline-dark">Terapkan</button>
  <?php if ($hasFilters): ?><a href="<?= site_url('admin/products') ?>" class="btn btn-link text-secondary text-decoration-none">Reset</a><?php endif ?>
</form>

<?php if ($products === []): ?>
  <div class="admin-empty admin-card">
    <span class="admin-empty-icon"><i class="bi <?= $hasFilters ? 'bi-search' : 'bi-box-seam' ?>"></i></span>
    <h2><?= $hasFilters ? 'Produk tidak ditemukan' : 'Belum ada produk' ?></h2>
    <p><?= $hasFilters ? 'Coba nama yang lebih singkat atau ganti filter.' : 'Tambah barang pertama agar katalog mulai terisi.' ?></p>
    <a href="<?= $hasFilters ? site_url('admin/products') : site_url('admin/products/new') ?>" class="btn btn-accent"><?= $hasFilters ? 'Lihat Semua Produk' : 'Tambah Produk' ?></a>
  </div>
<?php else: ?>
  <div class="admin-card admin-list">
    <div class="admin-section-heading">
      <h2>Daftar produk</h2>
      <span class="small text-secondary"><?= esc((string) $total) ?> produk</span>
    </div>
    <?php foreach ($products as $product): ?>
      <article class="admin-product-row">
        <div class="admin-product-thumb" aria-hidden="true">
          <?php if (! empty($product['image_url'])): ?>
            <img src="<?= esc($product['image_url']) ?>" alt="" loading="lazy">
          <?php else: ?>
            <i class="bi bi-image"></i>
          <?php endif ?>
        </div>
        <div class="admin-product-info">
          <h3 class="admin-product-title"><?= esc($product['name']) ?></h3>
          <div class="admin-product-sub"><?= esc($product['sku'] ?: $product['slug']) ?><?php if (! empty($product['is_featured'])): ?> <span class="text-warning-emphasis">· Unggulan</span><?php endif ?></div>
          <div class="admin-product-sub d-xl-none"><?= esc($product['category_name']) ?> · <?= esc($product['brand_name'] ?: 'Tanpa merek') ?></div>
        </div>
        <div class="admin-product-extra"><?= esc($product['category_name']) ?><br><span class="text-secondary"><?= esc($product['brand_name'] ?: 'Tanpa merek') ?></span></div>
        <div class="admin-product-status"><span class="admin-status <?= $product['is_active'] ? 'is-active' : 'is-inactive' ?>"><?= $product['is_active'] ? 'Aktif' : 'Nonaktif' ?></span></div>
        <div class="admin-product-actions">
          <a href="<?= site_url('admin/products/' . $product['id'] . '/edit') ?>" class="btn btn-outline-dark btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
          <?php if ($product['is_active']): ?><a href="<?= site_url('products/' . $product['slug']) ?>" class="btn btn-outline-secondary btn-sm" target="_blank" rel="noopener noreferrer" aria-label="Lihat <?= esc($product['name']) ?> di website"><i class="bi bi-box-arrow-up-right"></i></a><?php endif ?>
          <form action="<?= site_url('admin/products/' . $product['id'] . '/delete') ?>" method="post" onsubmit="return confirm('Hapus produk ini beserta fotonya?')">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
          </form>
        </div>
      </article>
    <?php endforeach ?>
  </div>
  <?php if ($pageCount > 1): ?>
    <nav aria-label="Halaman produk" class="mt-3">
      <ul class="pagination justify-content-end flex-wrap">
        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>"><a class="page-link" href="<?= esc($pageUrl(max(1, $page - 1))) ?>">Sebelumnya</a></li>
        <?php for ($number = 1; $number <= $pageCount; $number++): ?>
          <li class="page-item <?= $number === $page ? 'active' : '' ?>"><a class="page-link" href="<?= esc($pageUrl($number)) ?>" <?= $number === $page ? 'aria-current="page"' : '' ?>><?= $number ?></a></li>
        <?php endfor ?>
        <li class="page-item <?= $page >= $pageCount ? 'disabled' : '' ?>"><a class="page-link" href="<?= esc($pageUrl(min($pageCount, $page + 1))) ?>">Berikutnya</a></li>
      </ul>
    </nav>
  <?php endif ?>
<?php endif ?>

<?= $this->endSection() ?>
