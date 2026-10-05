(() => {
  const searchInput = document.getElementById('catalogSearch');
  const filterWrap = document.getElementById('categoryFilters');
  const items = [...document.querySelectorAll('.catalog-item')];
  const countEl = document.getElementById('resultCount');
  const emptyState = document.getElementById('emptyState');

  if (!items.length) return;

  let activeCategory = 'all';

  const params = new URLSearchParams(window.location.search);
  const queryFromUrl = (params.get('q') || '').trim();
  const categoryFromUrl = (params.get('category') || '').trim();

  if (searchInput && queryFromUrl) searchInput.value = queryFromUrl;
  if (categoryFromUrl && filterWrap) {
    const target = filterWrap.querySelector(`[data-filter="${CSS.escape(categoryFromUrl)}"]`);
    if (target) {
      filterWrap.querySelectorAll('.filter-chip').forEach(chip => chip.classList.remove('active'));
      target.classList.add('active');
      activeCategory = categoryFromUrl;
    }
  }

  function applyFilters() {
    const query = (searchInput?.value || '').trim().toLowerCase();
    let visible = 0;

    items.forEach(item => {
      const matchesCategory = activeCategory === 'all' || item.dataset.category === activeCategory;
      const haystack = (item.dataset.search || '').toLowerCase();
      const matchesSearch = !query || haystack.includes(query);
      const show = matchesCategory && matchesSearch;
      item.classList.toggle('d-none', !show);
      if (show) visible += 1;
    });

    if (countEl) countEl.textContent = visible;
    if (emptyState) emptyState.classList.toggle('d-none', visible !== 0);
  }

  searchInput?.addEventListener('input', applyFilters);

  filterWrap?.addEventListener('click', event => {
    const chip = event.target.closest('[data-filter]');
    if (!chip) return;
    event.preventDefault();
    filterWrap.querySelectorAll('.filter-chip').forEach(item => item.classList.remove('active'));
    chip.classList.add('active');
    activeCategory = chip.dataset.filter;
    applyFilters();
  });

  applyFilters();
})();
