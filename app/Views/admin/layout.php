<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <title><?= esc($title ?? 'Admin Mulyorejeki') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    :root { --admin-accent:#f36a10; --admin-ink:#171a1f; --admin-bg:#f4f5f6; }
    body { min-height:100vh; background:var(--admin-bg); color:var(--admin-ink); }
    .admin-shell { min-height:100vh; display:grid; grid-template-columns:240px minmax(0,1fr); }
    .admin-sidebar { background:#15181d; color:#fff; padding:1.5rem 1rem; position:sticky; top:0; height:100vh; }
    .admin-brand { display:flex; gap:.7rem; align-items:center; color:#fff; text-decoration:none; padding:.25rem .5rem 1.5rem; font-weight:800; letter-spacing:.04em; }
    .admin-brand-mark { width:38px; height:38px; display:grid; place-items:center; background:var(--admin-accent); font-size:.75rem; }
    .admin-nav a { display:flex; gap:.75rem; align-items:center; color:#aeb5bd; text-decoration:none; padding:.75rem .8rem; border-radius:4px; margin-bottom:.2rem; }
    .admin-nav a:hover,.admin-nav a.active { background:#242930; color:#fff; }
    .admin-main { min-width:0; }
    .admin-topbar { min-height:70px; background:#fff; border-bottom:1px solid #e1e4e8; display:flex; align-items:center; justify-content:space-between; padding:0 2rem; }
    .admin-content { padding:2rem; }
    .admin-card { background:#fff; border:1px solid #dfe3e8; border-radius:5px; }
    .btn-accent { color:#fff; background:var(--admin-accent); border-color:var(--admin-accent); }
    .btn-accent:hover { color:#fff; background:#d85608; border-color:#d85608; }
    .table > :not(caption) > * > * { padding:.9rem .75rem; vertical-align:middle; }
    .product-thumb { width:54px; height:54px; object-fit:contain; background:#f4f5f6; border:1px solid #e1e4e8; }
    .form-label { font-weight:650; }
    .form-text { color:#737b85; }
    .image-card img { width:100%; aspect-ratio:1/1; object-fit:contain; background:#f7f7f7; }
    @media (max-width: 991.98px) {
      .admin-shell { grid-template-columns:1fr; }
      .admin-sidebar { position:static; height:auto; }
      .admin-nav { display:flex; flex-wrap:wrap; gap:.25rem; }
      .admin-nav a { margin:0; }
      .admin-content,.admin-topbar { padding-left:1rem; padding-right:1rem; }
    }
  </style>
</head>
<body>
<div class="admin-shell">
  <aside class="admin-sidebar">
    <a class="admin-brand" href="<?= site_url('admin') ?>">
      <span class="admin-brand-mark">MR</span>
      <span>MULYOREJEKI</span>
    </a>
    <nav class="admin-nav">
      <a href="<?= site_url('admin') ?>" class="<?= uri_string() === 'admin' ? 'active' : '' ?>"><i class="bi bi-grid"></i> Dashboard</a>
      <a href="<?= site_url('admin/products') ?>" class="<?= str_starts_with(uri_string(), 'admin/products') ? 'active' : '' ?>"><i class="bi bi-box-seam"></i> Produk</a>
      <a href="<?= site_url('/') ?>" target="_blank"><i class="bi bi-box-arrow-up-right"></i> Lihat Website</a>
    </nav>
  </aside>

  <div class="admin-main">
    <header class="admin-topbar">
      <div>
        <strong><?= esc(session('admin_user_name') ?? 'Admin') ?></strong>
        <div class="small text-secondary"><?= esc(session('admin_user_email') ?? '') ?></div>
      </div>
      <form action="<?= site_url('admin/logout') ?>" method="post" class="m-0">
        <?= csrf_field() ?>
        <button class="btn btn-outline-dark btn-sm" type="submit"><i class="bi bi-box-arrow-right me-1"></i> Keluar</button>
      </form>
    </header>

    <main class="admin-content">
      <?php if (session('success')): ?><div class="alert alert-success"><?= esc(session('success')) ?></div><?php endif ?>
      <?php if (session('warning')): ?><div class="alert alert-warning"><?= esc(session('warning')) ?></div><?php endif ?>
      <?php if (session('error')): ?><div class="alert alert-danger"><?= esc(session('error')) ?></div><?php endif ?>
      <?= $this->renderSection('content') ?>
    </main>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
