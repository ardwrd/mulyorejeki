<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?= esc($description ?? 'Katalog produk Mulyorejeki') ?>">
  <title><?= esc($title ?? 'Mulyorejeki') ?></title>
  <link rel="preconnect" href="https://cdn.jsdelivr.net">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/styles.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/refinement.css') ?>">
</head>
<body>
  <div class="topbar py-2 d-none d-md-block">
    <div class="container d-flex justify-content-between align-items-center small">
      <span>Mulyorejeki · Toko teknik & kebutuhan workshop</span>
      <a href="<?= site_url('/#contact') ?>" class="topbar-link"><i class="bi bi-whatsapp me-1"></i>Tanya stok & harga</a>
    </div>
  </div>

  <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
    <div class="container py-2">
      <a class="navbar-brand brand-lockup" href="<?= site_url('/') ?>" aria-label="Mulyorejeki">
        <span class="brand-mark">MR</span>
        <span><strong>MULYOREJEKI</strong><small>TOKO TEKNIK</small></span>
      </a>

      <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Buka navigasi">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
          <li class="nav-item"><a class="nav-link <?= ($activePage ?? '') === 'home' ? 'active' : '' ?>" href="<?= site_url('/') ?>">Beranda</a></li>
          <li class="nav-item"><a class="nav-link <?= ($activePage ?? '') === 'products' ? 'active' : '' ?>" href="<?= site_url('products') ?>">Produk</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= site_url('/#categories') ?>">Kategori</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= site_url('/#brands') ?>">Brand</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= site_url('/#about') ?>">Tentang</a></li>
          <li class="nav-item ms-lg-2"><a class="btn btn-dark btn-sm px-3" href="<?= site_url('/#contact') ?>"><i class="bi bi-chat-left-text me-2"></i>Hubungi Kami</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <?= $this->renderSection('content') ?>

  <footer class="footer py-5">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-5">
          <a class="brand-lockup footer-brand" href="<?= site_url('/') ?>">
            <span class="brand-mark">MR</span>
            <span><strong>MULYOREJEKI</strong><small>TOKO TEKNIK</small></span>
          </a>
          <p class="footer-copy mt-3 mb-0">Pompa, perkakas, fastener, dan kebutuhan teknik untuk workshop maupun proyek.</p>
        </div>
        <div class="col-6 col-lg-2">
          <h6>Menu</h6>
          <a href="<?= site_url('products') ?>">Produk</a>
          <a href="<?= site_url('/#categories') ?>">Kategori</a>
          <a href="<?= site_url('/#brands') ?>">Brand</a>
        </div>
        <div class="col-6 col-lg-2">
          <h6>Informasi</h6>
          <a href="<?= site_url('/#about') ?>">Tentang</a>
          <a href="<?= site_url('/#contact') ?>">Kontak</a>
        </div>
        <div class="col-lg-3">
          <h6>Kontak</h6>
          <p class="mb-1">WhatsApp: akan diisi</p>
          <p class="mb-1">Alamat: akan diisi</p>
          <p class="mb-0">Jam operasional: akan diisi</p>
        </div>
      </div>
      <hr>
      <div class="small footer-bottom">© <?= date('Y') ?> Mulyorejeki.</div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url('assets/js/main.js') ?>"></script>
  <?= $this->renderSection('scripts') ?>
</body>
</html>
