<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<header class="page-hero">
  <div class="container">
    <div class="row align-items-end g-3">
      <div class="col-lg-7"><h1>Katalog produk</h1></div>
      <div class="col-lg-5"><p class="text-secondary mb-0">Cari berdasarkan nama produk, merek, atau kategori. Harga dan stok dikonfirmasi langsung ke toko.</p></div>
    </div>
  </div>
</header>

<div class="catalog-toolbar py-3">
  <div class="container">
    <div class="row g-2 align-items-center">
      <div class="col-lg-5">
        <div class="search-box m-0">
          <i class="bi bi-search"></i>
          <input id="catalogSearch" type="search" class="form-control" placeholder="Cari nama produk atau merek..." aria-label="Cari produk">
        </div>
      </div>
      <div class="col-lg-7">
        <div class="d-flex gap-2 flex-wrap justify-content-lg-end" id="categoryFilters">
          <a href="#" class="filter-chip active" data-filter="all">Semua</a>
          <?php foreach ($categories as $category): ?>
            <a href="#" class="filter-chip" data-filter="<?= esc($category['slug']) ?>"><?= esc($category['name']) ?></a>
          <?php endforeach ?>
        </div>
      </div>
    </div>
  </div>
</div>

<main class="section-space">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <span class="text-secondary small"><strong id="resultCount"><?= count($products) ?></strong> produk ditemukan</span>
    </div>

    <div class="row g-4" id="productGrid">
      <?php foreach ($products as $product): ?>
        <?php $searchText = strtolower($product['brand'] . ' ' . $product['name'] . ' ' . $product['category_label'] . ' ' . $product['meta']); ?>
        <div class="col-6 col-lg-3 catalog-item" data-category="<?= esc($product['category']) ?>" data-search="<?= esc($searchText) ?>">
          <article class="product-card catalog-card h-100">
            <a href="<?= site_url('products/' . $product['slug']) ?>" class="product-visual">
              <?php if (! empty($product['badge'])): ?><span class="product-badge"><?= esc($product['badge']) ?></span><?php endif ?>
              <i class="bi <?= esc($product['icon']) ?>"></i>
            </a>
            <div class="product-body">
              <span class="product-brand"><?= esc(strtoupper($product['brand'])) ?></span>
              <h3><a href="<?= site_url('products/' . $product['slug']) ?>"><?= esc($product['name']) ?></a></h3>
              <p class="product-meta"><?= esc($product['category_label']) ?> · <?= esc($product['meta']) ?></p>
              <a href="<?= site_url('products/' . $product['slug']) ?>" class="product-link">Lihat detail <i class="bi bi-arrow-right"></i></a>
            </div>
          </article>
        </div>
      <?php endforeach ?>
    </div>

    <div id="emptyState" class="empty-state d-none mt-4">
      <i class="bi bi-search fs-2 text-secondary"></i>
      <h3 class="h5 mt-3">Produk tidak ditemukan</h3>
      <p class="text-secondary mb-0">Coba kata kunci atau kategori lain.</p>
    </div>
  </div>
</main>

<?= $this->endSection() ?>
