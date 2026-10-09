<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <title><?= esc($title ?? 'Admin Mulyorejeki') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="<?= base_url('assets/css/admin.css') ?>" rel="stylesheet">
</head>
<body>
<?php $currentPage = trim(uri_string(), '/'); ?>
<div class="admin-shell">
  <aside class="admin-sidebar offcanvas-lg offcanvas-start" id="adminSidebar" tabindex="-1" aria-labelledby="adminSidebarTitle">
    <div class="offcanvas-body admin-sidebar-body">
      <div class="admin-sidebar-top">
        <a class="admin-brand" href="<?= site_url('admin') ?>" id="adminSidebarTitle">
          <span class="admin-brand-mark">MR</span>
          <span><strong>MULYOREJEKI</strong><small>ADMIN PANEL</small></span>
        </a>
        <button type="button" class="btn-close btn-close-white d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebar" aria-label="Tutup navigasi"></button>
      </div>

      <div class="admin-nav-label">MENU UTAMA</div>
      <nav class="admin-nav" aria-label="Navigasi admin">
        <a href="<?= site_url('admin') ?>" class="<?= $currentPage === 'admin' ? 'active' : '' ?>" <?= $currentPage === 'admin' ? 'aria-current="page"' : '' ?>><i class="bi bi-grid-1x2"></i><span>Dashboard</span></a>
        <a href="<?= site_url('admin/products') ?>" class="<?= str_starts_with($currentPage, 'admin/products') ? 'active' : '' ?>" <?= str_starts_with($currentPage, 'admin/products') ? 'aria-current="page"' : '' ?>><i class="bi bi-box-seam"></i><span>Produk</span></a>
        <a href="<?= site_url('admin/categories') ?>" class="<?= str_starts_with($currentPage, 'admin/categories') ? 'active' : '' ?>" <?= str_starts_with($currentPage, 'admin/categories') ? 'aria-current="page"' : '' ?>><i class="bi bi-grid"></i><span>Kategori</span></a>
        <a href="<?= site_url('admin/brands') ?>" class="<?= str_starts_with($currentPage, 'admin/brands') ? 'active' : '' ?>" <?= str_starts_with($currentPage, 'admin/brands') ? 'aria-current="page"' : '' ?>><i class="bi bi-tags"></i><span>Merek</span></a>
      </nav>

      <div class="admin-sidebar-bottom">
        <a href="<?= site_url('admin/profile') ?>" class="<?= $currentPage === 'admin/profile' ? 'active' : '' ?>" <?= $currentPage === 'admin/profile' ? 'aria-current="page"' : '' ?>><i class="bi bi-person-gear"></i><span>Profil &amp; Keamanan</span></a>
        <a href="<?= site_url('/') ?>" target="_blank" rel="noopener noreferrer"><i class="bi bi-box-arrow-up-right"></i><span>Lihat Website</span></a>
      </div>
    </div>
  </aside>

  <div class="admin-main">
    <header class="admin-topbar">
      <div class="admin-topbar-start">
        <button type="button" class="btn admin-menu-button d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar" aria-label="Buka navigasi"><i class="bi bi-list"></i></button>
        <span class="admin-topbar-title">Panel Admin</span>
      </div>
      <div class="admin-topbar-end">
        <div class="admin-user">
          <strong><?= esc(session('admin_user_name') ?? 'Admin') ?></strong>
          <span><?= esc(session('admin_user_email') ?? '') ?></span>
        </div>
        <form action="<?= site_url('admin/logout') ?>" method="post" class="m-0">
          <?= csrf_field() ?>
          <button class="btn btn-outline-dark btn-sm admin-logout" type="submit"><i class="bi bi-box-arrow-right"></i><span>Keluar</span></button>
        </form>
      </div>
    </header>

    <main class="admin-content" id="main-content">
      <?php if (session('success')): ?><div class="alert alert-success" role="status"><?= esc(session('success')) ?></div><?php endif ?>
      <?php if (session('warning')): ?><div class="alert alert-warning" role="alert"><?= esc(session('warning')) ?></div><?php endif ?>
      <?php if (session('error')): ?><div class="alert alert-danger" role="alert"><?= esc(session('error')) ?></div><?php endif ?>
      <?= $this->renderSection('content') ?>
    </main>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
