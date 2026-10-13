<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<?php
$value = static function (string $key) use ($site): string {
    $oldValue = old($key);
    return (string) ($oldValue !== null ? $oldValue : $site[$key]);
};
?>

<div class="admin-page-heading">
  <div>
    <div class="admin-eyebrow">WEBSITE</div>
    <h1>Pengaturan situs</h1>
    <p>Ubah logo, tulisan di halaman, dan informasi toko. Perubahan langsung tampil setelah disimpan.</p>
  </div>
  <a href="<?= site_url('/') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-dark">Lihat situs</a>
</div>

<?php if ($errors = session('errors')): ?>
  <div class="alert alert-danger" role="alert">
    <strong>Periksa isian berikut:</strong>
    <ul class="mb-0 mt-2 ps-3"><?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach ?></ul>
  </div>
<?php endif ?>

<form action="<?= site_url('admin/site') ?>" method="post" enctype="multipart/form-data" class="admin-site-form">
  <?= csrf_field() ?>
  <div class="row g-3">
    <div class="col-xl-5">
      <section class="admin-card admin-form-card h-100" aria-labelledby="site-logo-title">
        <h2 id="site-logo-title">Logo toko</h2>
        <div class="admin-logo-preview mb-3">
          <?php if ($site['logo_url'] !== ''): ?>
            <img src="<?= esc($site['logo_url']) ?>" alt="Logo Mulyorejeki saat ini">
          <?php else: ?>
            <span class="admin-brand-mark">MR</span>
            <span>Belum ada logo yang diunggah</span>
          <?php endif ?>
        </div>
        <label for="logo" class="form-label">Pilih logo baru</label>
        <input id="logo" name="logo" type="file" class="form-control" accept="image/png,image/jpeg,image/webp">
        <div class="form-text">PNG atau WebP dengan latar transparan paling cocok. Maksimal 2 MB.</div>
        <?php if ($site['logo_url'] !== ''): ?>
          <button type="submit" form="remove-logo-form" class="btn btn-outline-danger btn-sm mt-3">Hapus logo saat ini</button>
        <?php endif ?>
      </section>
    </div>

    <div class="col-xl-7">
      <section class="admin-card admin-form-card h-100" aria-labelledby="site-home-title">
        <h2 id="site-home-title">Bagian atas beranda</h2>
        <div class="mb-3">
          <label for="hero_title" class="form-label">Judul utama</label>
          <input id="hero_title" name="hero_title" type="text" class="form-control" maxlength="160" value="<?= esc($value('hero_title')) ?>" required>
        </div>
        <div>
          <label for="hero_text" class="form-label">Penjelasan di bawah judul</label>
          <textarea id="hero_text" name="hero_text" class="form-control" rows="3" maxlength="500" required><?= esc($value('hero_text')) ?></textarea>
        </div>
      </section>
    </div>

    <div class="col-xl-6">
      <section class="admin-card admin-form-card h-100" aria-labelledby="site-home-sections-title">
        <h2 id="site-home-sections-title">Judul bagian beranda</h2>
        <div class="mb-3"><label for="categories_title" class="form-label">Bagian kategori</label><input id="categories_title" name="categories_title" class="form-control" maxlength="120" value="<?= esc($value('categories_title')) ?>" required></div>
        <div class="mb-3"><label for="products_title" class="form-label">Bagian produk</label><input id="products_title" name="products_title" class="form-control" maxlength="120" value="<?= esc($value('products_title')) ?>" required></div>
        <div class="mb-3"><label for="brands_title" class="form-label">Bagian merek</label><input id="brands_title" name="brands_title" class="form-control" maxlength="120" value="<?= esc($value('brands_title')) ?>" required></div>
        <div><label for="brands_text" class="form-label">Penjelasan bagian merek</label><textarea id="brands_text" name="brands_text" class="form-control" rows="2" maxlength="500" required><?= esc($value('brands_text')) ?></textarea></div>
      </section>
    </div>

    <div class="col-xl-6">
      <section class="admin-card admin-form-card h-100" aria-labelledby="site-about-title">
        <h2 id="site-about-title">Tentang toko</h2>
        <div class="mb-3"><label for="about_title" class="form-label">Judul</label><input id="about_title" name="about_title" class="form-control" maxlength="160" value="<?= esc($value('about_title')) ?>" required></div>
        <div><label for="about_text" class="form-label">Cerita singkat</label><textarea id="about_text" name="about_text" class="form-control" rows="5" maxlength="1000" required><?= esc($value('about_text')) ?></textarea></div>
      </section>
    </div>

    <div class="col-xl-6">
      <section class="admin-card admin-form-card h-100" aria-labelledby="site-contact-title">
        <h2 id="site-contact-title">Ajakan menghubungi toko</h2>
        <div class="mb-3"><label for="contact_title" class="form-label">Judul</label><input id="contact_title" name="contact_title" class="form-control" maxlength="160" value="<?= esc($value('contact_title')) ?>" required></div>
        <div class="mb-3"><label for="contact_text" class="form-label">Penjelasan</label><textarea id="contact_text" name="contact_text" class="form-control" rows="3" maxlength="500" required><?= esc($value('contact_text')) ?></textarea></div>
        <div><label for="catalog_text" class="form-label">Penjelasan di halaman katalog</label><textarea id="catalog_text" name="catalog_text" class="form-control" rows="3" maxlength="500" required><?= esc($value('catalog_text')) ?></textarea></div>
      </section>
    </div>

    <div class="col-xl-6">
      <section class="admin-card admin-form-card h-100" aria-labelledby="site-address-title">
        <h2 id="site-address-title">Alamat dan jam buka</h2>
        <div class="mb-3"><label for="phone" class="form-label">Nomor WhatsApp toko</label><input id="phone" name="phone" type="tel" class="form-control" maxlength="30" value="<?= esc($value('phone')) ?>" required><div class="form-text">Nomor ini dipakai semua tombol WhatsApp di website.</div></div>
        <div class="mb-3"><label for="address" class="form-label">Alamat toko</label><textarea id="address" name="address" class="form-control" rows="2" maxlength="500" required><?= esc($value('address')) ?></textarea></div>
        <div class="mb-3"><label for="weekday_hours" class="form-label">Senin–Jumat</label><input id="weekday_hours" name="weekday_hours" class="form-control" maxlength="120" value="<?= esc($value('weekday_hours')) ?>" required></div>
        <div class="mb-3"><label for="saturday_hours" class="form-label">Sabtu</label><input id="saturday_hours" name="saturday_hours" class="form-control" maxlength="120" value="<?= esc($value('saturday_hours')) ?>" required></div>
        <div><label for="sunday_hours" class="form-label">Minggu</label><input id="sunday_hours" name="sunday_hours" class="form-control" maxlength="120" value="<?= esc($value('sunday_hours')) ?>" required></div>
      </section>
    </div>

    <div class="col-12">
      <section class="admin-card admin-form-card" aria-labelledby="site-footer-title">
        <h2 id="site-footer-title">Bagian bawah website</h2>
        <label for="footer_text" class="form-label">Keterangan singkat tentang toko</label>
        <textarea id="footer_text" name="footer_text" class="form-control" rows="2" maxlength="500" required><?= esc($value('footer_text')) ?></textarea>
      </section>
    </div>
  </div>

  <div class="admin-site-actions"><button type="submit" class="btn btn-accent btn-lg">Simpan perubahan</button></div>
</form>

<form id="remove-logo-form" action="<?= site_url('admin/site/logo/delete') ?>" method="post" onsubmit="return confirm('Hapus logo saat ini?')">
  <?= csrf_field() ?>
</form>

<?= $this->endSection() ?>
