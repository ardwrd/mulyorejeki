<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
  <div>
    <h1 class="h3 fw-bold mb-1">Produk</h1>
    <p class="text-secondary mb-0">Kelola item yang tampil di katalog publik.</p>
  </div>
  <a href="<?= site_url('admin/products/new') ?>" class="btn btn-accent"><i class="bi bi-plus-lg me-1"></i> Tambah Produk</a>
</div>

<div class="admin-card overflow-hidden">
  <?php if ($products === []): ?>
    <div class="p-5 text-center">
      <i class="bi bi-box-seam fs-1 text-secondary"></i>
      <h2 class="h5 mt-3">Belum ada produk</h2>
      <p class="text-secondary">Tambahkan produk pertama untuk mulai mengisi katalog.</p>
      <a href="<?= site_url('admin/products/new') ?>" class="btn btn-accent">Tambah Produk</a>
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th style="width:72px">Gambar</th>
            <th>Produk</th>
            <th>Kategori</th>
            <th>Merek</th>
            <th>Status</th>
            <th class="text-end" style="width:170px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $product): ?>
            <tr>
              <td>
                <?php if (! empty($product['image_url'])): ?>
                  <img src="<?= esc($product['image_url']) ?>" alt="" class="product-thumb">
                <?php else: ?>
                  <div class="product-thumb d-flex align-items-center justify-content-center text-secondary"><i class="bi bi-image"></i></div>
                <?php endif ?>
              </td>
              <td>
                <strong class="d-block"><?= esc($product['name']) ?></strong>
                <span class="small text-secondary"><?= esc($product['sku'] ?: $product['slug']) ?></span>
                <?php if (! empty($product['is_featured'])): ?><span class="badge text-bg-warning ms-1">Featured</span><?php endif ?>
              </td>
              <td><?= esc($product['category_name']) ?></td>
              <td><?= esc($product['brand_name'] ?: 'Generic') ?></td>
              <td>
                <?php if (! empty($product['is_active'])): ?>
                  <span class="badge text-bg-success">Aktif</span>
                <?php else: ?>
                  <span class="badge text-bg-secondary">Nonaktif</span>
                <?php endif ?>
              </td>
              <td class="text-end">
                <a href="<?= site_url('products/' . $product['slug']) ?>" class="btn btn-outline-secondary btn-sm" target="_blank" aria-label="Lihat produk"><i class="bi bi-eye"></i></a>
                <a href="<?= site_url('admin/products/' . $product['id'] . '/edit') ?>" class="btn btn-outline-dark btn-sm">Edit</a>
                <form action="<?= site_url('admin/products/' . $product['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus produk ini?')">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                </form>
              </td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  <?php endif ?>
</div>

<?= $this->endSection() ?>
