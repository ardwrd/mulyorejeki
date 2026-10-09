<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<?php
$isEdit = $term !== null;
$value = static function (string $key, mixed $default = '') {
    $oldValue = old($key);
    return $oldValue !== null ? $oldValue : $default;
};
?>

<div class="admin-page-heading">
  <div>
    <div class="admin-eyebrow"><a href="<?= site_url('admin/' . $type) ?>"><?= esc($label) ?></a> / <?= $isEdit ? 'EDIT' : 'BARU' ?></div>
    <h1><?= $isEdit ? 'Edit' : 'Tambah' ?> <?= esc($label) ?></h1>
    <p><?= $type === 'categories' ? 'Isi nama dan keterangan kategori.' : 'Isi nama merek yang digunakan pada produk.' ?></p>
  </div>
  <a href="<?= site_url('admin/' . $type) ?>" class="btn btn-outline-dark">Kembali</a>
</div>

<?php if ($errors = session('errors')): ?>
  <div class="alert alert-danger" role="alert">
    <strong>Periksa isian berikut:</strong>
    <ul class="mb-0 mt-2 ps-3"><?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach ?></ul>
  </div>
<?php endif ?>

<form action="<?= $isEdit ? site_url('admin/' . $type . '/' . $term['id']) : site_url('admin/' . $type) ?>" method="post" class="admin-form-layout">
  <?= csrf_field() ?>
  <div class="admin-card admin-form-card">
    <h2>Informasi <?= strtolower(esc($label)) ?></h2>
    <div class="mb-3">
      <label for="name" class="form-label">Nama <?= strtolower(esc($label)) ?> <span class="text-danger">*</span></label>
      <input id="name" name="name" type="text" class="form-control" value="<?= esc($value('name', $term['name'] ?? '')) ?>" maxlength="120" required autofocus>
    </div>
    <?php if ($type === 'categories'): ?>
      <div class="mb-3">
        <label for="description" class="form-label">Deskripsi singkat</label>
        <textarea id="description" name="description" class="form-control" rows="3" maxlength="500"><?= esc($value('description', $term['description'] ?? '')) ?></textarea>
      </div>
    <?php endif ?>
  </div>

  <div class="admin-form-side">
    <div class="admin-card admin-form-card">
      <h2>Tampilan di website</h2>
      <input type="hidden" name="is_active" value="0">
      <div class="form-check form-switch">
        <input id="is_active" name="is_active" class="form-check-input" type="checkbox" role="switch" value="1" <?= (string) $value('is_active', $term['is_active'] ?? 1) === '1' ? 'checked' : '' ?>>
        <label for="is_active" class="form-check-label">Tampilkan <?= strtolower(esc($label)) ?> di katalog</label>
      </div>
      <?php if ($type === 'categories' && $isEdit): ?><p class="form-text mt-3 mb-0">Kategori nonaktif menyembunyikan produk di dalamnya dari katalog publik.</p><?php endif ?>
    </div>
    <div class="admin-form-actions">
      <button type="submit" class="btn btn-accent btn-lg"><?= $isEdit ? 'Simpan Perubahan' : 'Tambah ' . esc($label) ?></button>
      <a href="<?= site_url('admin/' . $type) ?>" class="btn btn-outline-secondary">Batal</a>
    </div>
  </div>
</form>

<?= $this->endSection() ?>
