<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main>
  <section class="hero-section">
    <div class="container py-5 py-lg-6">
      <div class="row align-items-center g-4 g-lg-5">
        <div class="col-lg-7">
          <h1 class="display-4 hero-title mb-4">Cari pompa, perkakas, atau baut? Mulai dari sini.</h1>
          <p class="lead hero-copy mb-4">Telusuri barang menurut jenis atau merek. Ukuran dan spesifikasi yang tercatat bisa dilihat di halaman produk.</p>
          <div class="d-flex flex-column flex-sm-row gap-3">
            <a class="btn btn-accent btn-lg px-4" href="<?= site_url('products') ?>">Lihat katalog <i class="bi bi-arrow-right ms-2"></i></a>
            <?php if (! empty($contactUrl)): ?><a class="btn btn-outline-dark btn-lg px-4" href="<?= esc($contactUrl) ?>" target="_blank" rel="noopener noreferrer"><i class="bi bi-whatsapp me-2"></i>Tanya stok</a><?php endif ?>
          </div>

          <div class="hero-trust row g-3 mt-4 pt-3">
            <div class="col-4"><strong>Pompa</strong><span>Air &amp; booster</span></div>
            <div class="col-4"><strong>Perkakas</strong><span>Bor, gerinda, tang</span></div>
            <div class="col-4"><strong>Baut</strong><span>Mur &amp; ring</span></div>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="hero-panel">
            <div class="hero-panel-head d-flex justify-content-between align-items-center">
              <span class="fw-semibold">Cari produk</span>
              <i class="bi bi-tools"></i>
            </div>
            <form class="search-box mt-4" action="<?= site_url('products') ?>" method="get">
              <i class="bi bi-search"></i>
              <input type="search" name="q" class="form-control" placeholder="Misalnya: pompa atau gerinda" aria-label="Cari produk">
              <button class="btn btn-dark" type="submit">Cari</button>
            </form>

            <div class="quick-categories mt-4">
              <?php foreach (array_slice($categories, 0, 4) as $category): ?>
                <a href="<?= site_url('products') . '?category=' . urlencode($category['slug']) ?>">
                  <i class="bi <?= esc($category['icon']) ?>"></i>
                  <span><?= esc($category['name']) ?></span>
                  <i class="bi bi-chevron-right ms-auto"></i>
                </a>
              <?php endforeach ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="categories" class="section-space bg-white">
    <div class="container">
      <div class="section-heading d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
        <h2 class="mb-0">Cari berdasarkan jenis barang</h2>
        <a href="<?= site_url('products') ?>" class="text-link">Lihat semua produk <i class="bi bi-arrow-right ms-1"></i></a>
      </div>

      <div class="row g-3">
        <?php foreach ($categories as $category): ?>
          <div class="col-6 col-lg-4">
            <a class="category-card" href="<?= site_url('products') . '?category=' . urlencode($category['slug']) ?>">
              <div class="category-icon"><i class="bi <?= esc($category['icon']) ?>"></i></div>
              <div>
                <h3><?= esc($category['name']) ?></h3>
                <p><?= esc($category['description']) ?></p>
              </div>
              <i class="bi bi-arrow-up-right category-arrow"></i>
            </a>
          </div>
        <?php endforeach ?>
      </div>
    </div>
  </section>

  <section class="section-space section-muted">
    <div class="container">
      <div class="section-heading d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
        <h2 class="mb-0">Lihat beberapa produk</h2>
        <a href="<?= site_url('products') ?>" class="text-link">Buka katalog <i class="bi bi-arrow-right ms-1"></i></a>
      </div>

      <div class="row g-4">
        <?php foreach ($featuredProducts as $product): ?>
          <div class="col-6 col-lg-3">
            <article class="product-card h-100">
              <a href="<?= site_url('products/' . $product['slug']) ?>" class="product-visual">
                <?php if (! empty($product['badge'])): ?><span class="product-badge"><?= esc($product['badge']) ?></span><?php endif ?>
                <?php if (! empty($product['image_url'])): ?>
                  <img src="<?= esc($product['image_url']) ?>" alt="<?= esc($product['name']) ?>" loading="lazy">
                <?php else: ?>
                  <i class="bi <?= esc($product['icon']) ?>"></i>
                <?php endif ?>
              </a>
              <div class="product-body">
                <span class="product-brand"><?= esc(strtoupper($product['brand'])) ?></span>
                <h3><a href="<?= site_url('products/' . $product['slug']) ?>"><?= esc($product['name']) ?></a></h3>
                <p class="product-meta"><?= esc($product['category_label']) ?><?= $product['meta'] !== '' ? ' · ' . esc($product['meta']) : '' ?></p>
                <a href="<?= site_url('products/' . $product['slug']) ?>" class="product-link">Lihat detail <i class="bi bi-arrow-right"></i></a>
              </div>
            </article>
          </div>
        <?php endforeach ?>
      </div>
    </div>
  </section>

  <section id="brands" class="section-space bg-white">
    <div class="container">
      <div class="row align-items-center g-4">
        <div class="col-lg-4">
          <h2>Merek di katalog</h2>
          <p class="text-secondary mb-0">Merek yang tercantum pada produk Mulyorejeki. Ketersediaan tiap barang bisa berubah.</p>
        </div>
        <div class="col-lg-8">
          <div class="brand-grid">
            <?php foreach ($brands as $brand): ?><div><?= esc(strtoupper($brand)) ?></div><?php endforeach ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="about" class="section-space about-section">
    <div class="container">
      <div class="row g-4 g-lg-5 align-items-center">
        <div class="col-lg-6">
          <div class="about-visual">
            <div class="about-grid"></div>
            <div class="about-stamp"><i class="bi bi-gear-wide-connected"></i><span>TOOLS<br>& SUPPLY</span></div>
          </div>
        </div>
        <div class="col-lg-6">
          <h2 class="text-white">Lihat detail barang sebelum memilih.</h2>
          <p class="about-copy">Di sini Anda bisa melihat nama, merek, ukuran, dan spesifikasi yang sudah dicatat. Harga serta stok perlu dipastikan langsung sebelum membeli.</p>
          <div class="row g-3 mt-3">
            <div class="col-sm-6"><div class="feature-line"><i class="bi bi-check2-circle"></i><span>Nama dan tipe barang</span></div></div>
            <div class="col-sm-6"><div class="feature-line"><i class="bi bi-check2-circle"></i><span>Ukuran dan spesifikasi</span></div></div>
            <div class="col-sm-6"><div class="feature-line"><i class="bi bi-check2-circle"></i><span>Merek produk</span></div></div>
            <div class="col-sm-6"><div class="feature-line"><i class="bi bi-check2-circle"></i><span>Kategori barang</span></div></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php if (! empty($contactUrl)): ?>
  <section id="contact" class="section-space bg-white">
    <div class="container">
      <div class="contact-box">
        <div class="row align-items-center g-4">
          <div class="col-lg-8">
            <h2 class="mb-2">Mencari ukuran atau tipe lain?</h2>
            <p class="text-secondary mb-0">Sebutkan nama barang dan ukurannya lewat WhatsApp. Kami bantu cek stoknya.</p>
          </div>
          <div class="col-lg-4 text-lg-end">
            <a href="<?= esc($contactUrl) ?>" class="btn btn-accent btn-lg px-4" target="_blank" rel="noopener noreferrer"><i class="bi bi-whatsapp me-2"></i>Chat WhatsApp</a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php endif ?>
</main>

<?= $this->endSection() ?>
