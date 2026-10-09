<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="section-space product-detail">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb small mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('/') ?>" class="text-decoration-none">Beranda</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('products') ?>" class="text-decoration-none">Produk</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('products') . '?category=' . urlencode($product['category']) ?>" class="text-decoration-none"><?= esc($product['category_label']) ?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= esc($product['name']) ?></li>
      </ol>
    </nav>

    <div class="row g-4 g-lg-5 align-items-start">
      <div class="col-lg-6">
        <div class="product-detail-visual">
          <?php if (! empty($product['image_url'])): ?>
            <img src="<?= esc($product['image_url']) ?>" alt="<?= esc($product['name']) ?>">
          <?php else: ?>
            <i class="bi <?= esc($product['icon']) ?>"></i>
          <?php endif ?>
        </div>
      </div>

      <div class="col-lg-6">
        <h1 class="display-5 mb-3"><?= esc($product['name']) ?></h1>
        <p class="lead text-secondary"><?= esc($product['description']) ?></p>

        <div class="d-flex gap-2 flex-wrap my-4">
          <span class="filter-chip active"><?= esc($product['brand']) ?></span>
          <span class="filter-chip"><?= esc($product['category_label']) ?></span>
          <?php if ($product['meta'] !== ''): ?><span class="filter-chip"><?= esc($product['meta']) ?></span><?php endif ?>
        </div>

        <div class="inquiry-box mb-4">
          <div class="d-flex gap-3 align-items-start">
            <i class="bi bi-info-circle text-secondary mt-1"></i>
            <div>
              <strong class="d-block mb-1">Harga dan stok belum tercantum</strong>
              <span class="small text-secondary"><?= ! empty($contactUrl) ? 'Sebutkan nama barang ini saat menghubungi toko agar lebih mudah dicek.' : 'Catat nama dan tipe barang ini untuk memastikan ketersediaannya sebelum membeli.' ?></span>
            </div>
          </div>
        </div>

        <div class="d-grid d-sm-flex gap-2 mb-5">
          <?php if (! empty($contactUrl)): ?><a href="<?= esc($contactUrl) ?>" class="btn btn-accent btn-lg px-4" target="_blank" rel="noopener noreferrer"><i class="bi bi-whatsapp me-2"></i>Tanya lewat WhatsApp</a><?php endif ?>
          <a href="<?= site_url('products') ?>" class="btn btn-outline-dark btn-lg px-4">Kembali ke katalog</a>
        </div>

        <?php if ($product['specs'] !== []): ?>
          <h2 class="h5 fw-bold mb-3">Spesifikasi</h2>
          <div class="spec-table">
            <?php foreach ($product['specs'] as $label => $value): ?>
              <div class="spec-row"><span><?= esc($label) ?></span><span><?= esc($value) ?></span></div>
            <?php endforeach ?>
          </div>
        <?php endif ?>
      </div>
    </div>
  </div>
</main>

<?php if ($relatedProducts !== []): ?>
<section class="section-space section-muted">
  <div class="container">
    <div class="section-heading mb-4"><h2 class="mb-0">Barang lain dalam kategori <?= esc($product['category_label']) ?></h2></div>
    <div class="row g-4">
      <?php foreach ($relatedProducts as $related): ?>
        <div class="col-12 col-sm-6 col-lg-3">
          <article class="product-card h-100">
            <a href="<?= site_url('products/' . $related['slug']) ?>" class="product-visual">
              <?php if (! empty($related['image_url'])): ?>
                <img src="<?= esc($related['image_url']) ?>" alt="<?= esc($related['name']) ?>" loading="lazy">
              <?php else: ?>
                <i class="bi <?= esc($related['icon']) ?>"></i>
              <?php endif ?>
            </a>
            <div class="product-body">
              <span class="product-brand"><?= esc(strtoupper($related['brand'])) ?></span>
              <h3><a href="<?= site_url('products/' . $related['slug']) ?>"><?= esc($related['name']) ?></a></h3>
              <p class="product-meta"><?= esc($related['category_label']) ?><?= $related['meta'] !== '' ? ' · ' . esc($related['meta']) : '' ?></p>
            </div>
          </article>
        </div>
      <?php endforeach ?>
    </div>
  </div>
</section>
<?php endif ?>

<?= $this->endSection() ?>
