<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <title><?= esc($title) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root { --accent:#f36a10; --ink:#15181d; }
    body { min-height:100vh; display:grid; place-items:center; background:#f3f4f6; color:var(--ink); }
    .login-card { width:min(430px, calc(100% - 2rem)); background:#fff; border:1px solid #dfe3e8; box-shadow:12px 12px 0 #e4e6e9; padding:2rem; }
    .brand { display:flex; align-items:center; gap:.8rem; margin-bottom:2rem; }
    .brand-mark { width:44px; height:44px; display:grid; place-items:center; background:var(--accent); color:#fff; font-weight:900; }
    .brand strong { display:block; letter-spacing:.08em; }
    .brand small { display:block; color:#727a84; font-size:.7rem; letter-spacing:.16em; }
    .btn-accent { color:#fff; background:var(--accent); border-color:var(--accent); font-weight:700; }
    .btn-accent:hover { color:#fff; background:#d85608; border-color:#d85608; }
  </style>
</head>
<body>
  <main class="login-card">
    <div class="brand">
      <span class="brand-mark">MR</span>
      <span><strong>MULYOREJEKI</strong><small>KELOLA KATALOG</small></span>
    </div>

    <h1 class="h3 fw-bold mb-2">Masuk ke admin</h1>
    <p class="text-secondary mb-4">Masuk untuk mengubah produk, kategori, merek, dan foto.</p>

    <?php if (session('error')): ?><div class="alert alert-danger"><?= esc(session('error')) ?></div><?php endif ?>
    <?php if ($errors = session('errors')): ?>
      <div class="alert alert-danger"><ul class="mb-0 ps-3"><?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach ?></ul></div>
    <?php endif ?>

    <form action="<?= site_url('admin/login') ?>" method="post">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label for="email" class="form-label fw-semibold">Email</label>
        <input id="email" type="email" name="email" class="form-control form-control-lg" value="<?= esc(old('email')) ?>" autocomplete="username" required autofocus>
      </div>
      <div class="mb-4">
        <label for="password" class="form-label fw-semibold">Password</label>
        <input id="password" type="password" name="password" class="form-control form-control-lg" autocomplete="current-password" required>
      </div>
      <button type="submit" class="btn btn-accent btn-lg w-100">Masuk</button>
    </form>
  </main>
</body>
</html>
