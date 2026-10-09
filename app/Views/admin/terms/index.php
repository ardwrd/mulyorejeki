<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="admin-page-heading">
  <div>
    <div class="admin-eyebrow">KATALOG</div>
    <h1><?= esc($label) ?></h1>
    <p>Kelola <?= $type === 'categories' ? 'kelompok produk yang tampil di katalog' : 'merek yang dapat dipilih saat menambah produk' ?>.</p>
  </div>
  <a href="<?= site_url('admin/' . $type . '/new') ?>" class="btn btn-accent"><i class="bi bi-plus-lg me-1"></i> Tambah <?= esc($label) ?></a>
</div>

<?php if ($terms === []): ?>
  <div class="admin-empty admin-card">
    <span class="admin-empty-icon"><i class="bi <?= $type === 'categories' ? 'bi-grid' : 'bi-tag' ?>"></i></span>
    <h2>Belum ada <?= strtolower(esc($label)) ?></h2>
    <p>Tambahkan <?= strtolower(esc($label)) ?> pertama agar bisa dipilih saat mengelola produk.</p>
    <a href="<?= site_url('admin/' . $type . '/new') ?>" class="btn btn-accent">Tambah <?= esc($label) ?></a>
  </div>
<?php else: ?>
  <div class="admin-card admin-list">
    <?php foreach ($terms as $term): ?>
      <article class="admin-term-row">
        <div class="admin-term-icon" aria-hidden="true"><i class="bi <?= esc($type === 'categories' ? ($term['icon'] ?: 'bi-grid') : 'bi-tag') ?>"></i></div>
        <div class="admin-term-details">
          <div class="admin-term-title">
            <h2><?= esc($term['name']) ?></h2>
            <span class="admin-status <?= $term['is_active'] ? 'is-active' : 'is-inactive' ?>"><?= $term['is_active'] ? 'Aktif' : 'Nonaktif' ?></span>
          </div>
          <p><?= esc($type === 'categories' ? ($term['description'] ?: 'Belum ada deskripsi') : $term['slug']) ?></p>
          <span class="admin-term-meta"><?= esc((string) $term['product_count']) ?> produk <span aria-hidden="true">·</span> Urutan <?= esc((string) $term['sort_order']) ?></span>
        </div>
        <div class="admin-term-actions">
          <a href="<?= site_url('admin/' . $type . '/' . $term['id'] . '/edit') ?>" class="btn btn-outline-dark btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
          <?php if ((int) $term['product_count'] === 0): ?>
            <form action="<?= site_url('admin/' . $type . '/' . $term['id'] . '/delete') ?>" method="post" onsubmit="return confirm('Hapus <?= esc($label) ?> ini?')">
              <?= csrf_field() ?>
              <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
            </form>
          <?php else: ?>
            <button type="button" class="btn btn-outline-secondary btn-sm" disabled title="Masih dipakai oleh produk">Hapus</button>
          <?php endif ?>
        </div>
      </article>
    <?php endforeach ?>
  </div>
<?php endif ?>

<?= $this->endSection() ?>
