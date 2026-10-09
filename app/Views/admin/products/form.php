<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<?php
$isEdit = $product !== null;
$value = static function (string $key, mixed $default = '') {
    $oldValue = old($key);
    return $oldValue !== null ? $oldValue : $default;
};
?>

<div class="admin-page-heading">
  <div>
    <div class="admin-eyebrow"><a href="<?= site_url('admin/products') ?>">PRODUK</a> / <?= $isEdit ? 'EDIT' : 'BARU' ?></div>
    <h1><?= $isEdit ? 'Edit Produk' : 'Tambah Produk' ?></h1>
    <p><?= $isEdit ? 'Ubah keterangan, foto, dan status tampil barang ini.' : 'Isi keterangan barang yang akan ditampilkan di katalog.' ?></p>
  </div>
  <a href="<?= site_url('admin/products') ?>" class="btn btn-outline-dark">Kembali</a>
</div>

<?php if ($errors = session('errors')): ?>
  <div class="alert alert-danger">
    <strong>Periksa kembali form:</strong>
    <ul class="mb-0 mt-2 ps-3"><?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach ?></ul>
  </div>
<?php endif ?>

<?php if ($categories === []): ?>
  <div class="alert alert-warning" role="alert">Belum ada kategori aktif. <a href="<?= site_url('admin/categories/new') ?>">Tambah kategori</a> sebelum menyimpan produk.</div>
<?php endif ?>

<form action="<?= $isEdit ? site_url('admin/products/' . $product['id']) : site_url('admin/products') ?>" method="post" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <div class="row g-4">
    <div class="col-xl-8">
      <div class="admin-card p-4 mb-4">
        <h2 class="h5 fw-bold mb-4">Informasi utama</h2>
        <div class="mb-3">
          <label for="name" class="form-label">Nama produk</label>
          <input id="name" type="text" name="name" class="form-control" value="<?= esc($value('name', $product['name'] ?? '')) ?>" maxlength="180" required>
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <label for="slug" class="form-label">Slug</label>
            <input id="slug" type="text" name="slug" class="form-control" value="<?= esc($value('slug', $product['slug'] ?? '')) ?>" maxlength="200" placeholder="otomatis-dari-nama">
            <div class="form-text">Kosongkan untuk membuat slug otomatis.</div>
          </div>
          <div class="col-md-6">
            <label for="sku" class="form-label">SKU</label>
            <input id="sku" type="text" name="sku" class="form-control" value="<?= esc($value('sku', $product['sku'] ?? '')) ?>" maxlength="100">
          </div>
        </div>

        <div class="row g-3 mt-0">
          <div class="col-md-6">
            <label for="category_id" class="form-label">Kategori</label>
            <select id="category_id" name="category_id" class="form-select" required>
              <option value="">Pilih kategori</option>
              <?php foreach ($categories as $category): ?>
                <?php $selectedCategory = (string) $value('category_id', $product['category_id'] ?? ''); ?>
                <option value="<?= esc((string) $category['id']) ?>" <?= $selectedCategory === (string) $category['id'] ? 'selected' : '' ?>><?= esc($category['name']) ?></option>
              <?php endforeach ?>
            </select>
            <div class="form-text"><a href="<?= site_url('admin/categories') ?>">Kelola kategori</a></div>
          </div>
          <div class="col-md-6">
            <label for="brand_id" class="form-label">Merek</label>
            <select id="brand_id" name="brand_id" class="form-select">
              <option value="">Tanpa merek</option>
              <?php foreach ($brands as $brand): ?>
                <?php $selectedBrand = (string) $value('brand_id', $product['brand_id'] ?? ''); ?>
                <option value="<?= esc((string) $brand['id']) ?>" <?= $selectedBrand === (string) $brand['id'] ? 'selected' : '' ?>><?= esc($brand['name']) ?></option>
              <?php endforeach ?>
            </select>
            <div class="form-text"><a href="<?= site_url('admin/brands') ?>">Kelola merek</a></div>
          </div>
        </div>

        <div class="mt-3">
          <label for="short_description" class="form-label">Deskripsi singkat</label>
          <textarea id="short_description" name="short_description" class="form-control" rows="2" maxlength="500"><?= esc($value('short_description', $product['short_description'] ?? '')) ?></textarea>
        </div>

        <div class="mt-3">
          <label for="description" class="form-label">Deskripsi</label>
          <textarea id="description" name="description" class="form-control" rows="5" maxlength="10000"><?= esc($value('description', $product['description'] ?? '')) ?></textarea>
        </div>
      </div>

      <div class="admin-card p-4 mb-4">
        <h2 class="h5 fw-bold mb-2">Spesifikasi</h2>
        <p class="text-secondary small">Tulis satu rincian per baris dengan format <code>Nama: Nilai</code>.</p>
        <textarea name="specifications_text" class="form-control font-monospace" rows="8" maxlength="10000" placeholder="Daya: 720 W&#10;Diameter cakram: 100 mm"><?= esc($value('specifications_text', $specificationsText)) ?></textarea>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="admin-card p-4 mb-4">
        <h2 class="h5 fw-bold mb-3">Tampilan katalog</h2>
        <div class="mb-3">
          <label for="meta" class="form-label">Keterangan di kartu produk</label>
          <input id="meta" type="text" name="meta" class="form-control" value="<?= esc($value('meta', $product['meta'] ?? '')) ?>" maxlength="160" placeholder="720 W / Stainless Steel / M6–M20">
        </div>
        <div class="mb-3">
          <label for="icon" class="form-label">Ikon jika foto belum ada</label>
          <input id="icon" type="text" name="icon" class="form-control" value="<?= esc($value('icon', $product['icon'] ?? 'bi-tools')) ?>" maxlength="80" placeholder="bi-tools">
          <div class="form-text">Gunakan nama ikon Bootstrap, misalnya bi-tools.</div>
        </div>
        <div class="mb-3">
          <label for="badge" class="form-label">Label pada foto</label>
          <input id="badge" type="text" name="badge" class="form-control" value="<?= esc($value('badge', $product['badge'] ?? '')) ?>" maxlength="80" placeholder="Pilihan">
        </div>
        <div class="mb-3">
          <label for="sort_order" class="form-label">Urutan</label>
          <input id="sort_order" type="number" name="sort_order" class="form-control" value="<?= esc((string) $value('sort_order', $product['sort_order'] ?? 0)) ?>">
        </div>
        <input type="hidden" name="is_featured" value="0">
        <div class="form-check form-switch mb-2">
          <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured" value="1" <?= (string) $value('is_featured', $product['is_featured'] ?? 0) === '1' ? 'checked' : '' ?>>
          <label class="form-check-label" for="is_featured">Tampilkan di beranda</label>
        </div>
        <input type="hidden" name="is_active" value="0">
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" <?= (string) $value('is_active', $product['is_active'] ?? 1) === '1' ? 'checked' : '' ?>>
          <label class="form-check-label" for="is_active">Aktif di katalog</label>
        </div>
      </div>

      <div class="admin-card p-4 mb-4">
        <h2 class="h5 fw-bold mb-3">Foto produk</h2>
        <label for="image" class="form-label">Tambah foto</label>
        <input id="image" type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp">
        <div class="form-text">JPG, PNG, atau WebP; maksimal 5 MB. Foto pertama menjadi foto utama.</div>
      </div>

      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-accent btn-lg" <?= $categories === [] ? 'disabled' : '' ?>><?= $isEdit ? 'Simpan Perubahan' : 'Tambah Produk' ?></button>
        <?php if ($isEdit): ?><a href="<?= site_url('products/' . $product['slug']) ?>" target="_blank" class="btn btn-outline-dark">Lihat di Website</a><?php endif ?>
      </div>
    </div>
  </div>
</form>

<?php if ($isEdit && $images !== []): ?>
  <section class="admin-card p-4 mt-4">
    <h2 class="h5 fw-bold mb-3">Gambar produk</h2>
    <div class="row g-3">
      <?php foreach ($images as $image): ?>
        <div class="col-6 col-md-4 col-xl-3">
          <div class="admin-image-card">
            <img src="<?= esc($image['url']) ?>" alt="<?= esc($image['alt_text'] ?? $product['name']) ?>">
            <div class="admin-image-actions">
              <?php if (! empty($image['is_primary'])): ?>
                <span class="badge text-bg-success align-self-center">Utama</span>
              <?php else: ?>
                <form action="<?= site_url('admin/products/' . $product['id'] . '/images/' . $image['id'] . '/primary') ?>" method="post">
                  <?= csrf_field() ?>
                  <button class="btn btn-outline-dark btn-sm" type="submit">Jadikan utama</button>
                </form>
              <?php endif ?>
              <form action="<?= site_url('admin/products/' . $product['id'] . '/images/' . $image['id'] . '/delete') ?>" method="post" onsubmit="return confirm('Hapus gambar ini?')">
                <?= csrf_field() ?>
                <button class="btn btn-outline-danger btn-sm" type="submit">Hapus</button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach ?>
    </div>
  </section>
<?php endif ?>

<?= $this->endSection() ?>
