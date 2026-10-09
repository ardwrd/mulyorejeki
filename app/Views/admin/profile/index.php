<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="admin-page-heading">
  <div>
    <div class="admin-eyebrow">AKUN ADMIN</div>
    <h1>Profil &amp; Keamanan</h1>
    <p>Ubah nama, email login, atau password akun ini.</p>
  </div>
</div>

<div class="admin-form-layout">
  <section class="admin-card admin-form-card" aria-labelledby="profile-details-title">
    <h2 id="profile-details-title">Informasi akun</h2>
    <?php if ($errors = session('details_errors')): ?>
      <div class="alert alert-danger" role="alert"><ul class="mb-0 ps-3"><?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach ?></ul></div>
    <?php endif ?>
    <form action="<?= site_url('admin/profile') ?>" method="post">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label for="name" class="form-label">Nama admin</label>
        <input id="name" name="name" type="text" class="form-control" value="<?= esc(old('name') ?? $user['name']) ?>" maxlength="120" autocomplete="name" required>
      </div>
      <div class="mb-4">
        <label for="email" class="form-label">Email untuk login</label>
        <input id="email" name="email" type="email" class="form-control" value="<?= esc(old('email') ?? $user['email']) ?>" maxlength="190" autocomplete="email" required>
      </div>
      <button type="submit" class="btn btn-accent">Simpan Profil</button>
    </form>
  </section>

  <section class="admin-card admin-form-card" aria-labelledby="profile-password-title">
    <h2 id="profile-password-title">Ganti password</h2>
    <?php if ($errors = session('password_errors')): ?>
      <div class="alert alert-danger" role="alert"><ul class="mb-0 ps-3"><?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach ?></ul></div>
    <?php endif ?>
    <form action="<?= site_url('admin/profile/password') ?>" method="post">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label for="current_password" class="form-label">Password saat ini</label>
        <input id="current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" required>
      </div>
      <div class="mb-3">
        <label for="new_password" class="form-label">Password baru</label>
        <input id="new_password" name="new_password" type="password" class="form-control" minlength="12" maxlength="255" autocomplete="new-password" required>
        <div class="form-text">Minimal 12 karakter.</div>
      </div>
      <div class="mb-4">
        <label for="password_confirm" class="form-label">Ulangi password baru</label>
        <input id="password_confirm" name="password_confirm" type="password" class="form-control" minlength="12" maxlength="255" autocomplete="new-password" required>
      </div>
      <button type="submit" class="btn btn-outline-dark">Ganti Password</button>
    </form>
  </section>
</div>

<?= $this->endSection() ?>
