<?php
require_once(__DIR__ . '/../settings/core.php');
if (!isLoggedIn()) { header('Location: login.php'); exit; }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>All Products - Food Platform</title>

  <!-- Basic styling (clean, responsive grid) -->
  <style>
    :root{
      --accent: #111827; /* deep gray/black accent */
      --muted: #6b7280;
      --card-bg: #ffffff;
      --page-bg: #f8fafc;
      --radius: 12px;
      --gap: 1rem;
      --max-width: 1200px;
    }
    *{box-sizing:border-box}
    body{
      margin:0;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      background:var(--page-bg);
      color:#111827;
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
      padding:24px;
      display:flex;
      justify-content:center;
    }
    .container{
      width:100%;
      max-width:var(--max-width);
    }

    /* header / controls */
    .header {
      display:flex;
      gap:12px;
      align-items:center;
      margin-bottom:18px;
      flex-wrap:wrap;
    }
    .brand { font-weight:700; font-size:1.25rem; color:var(--accent); }
    .search-box {
      flex:1 1 320px;
      display:flex;
      gap:8px;
    }
    input[type="search"], select {
      padding:10px 12px;
      border-radius:10px;
      border:1px solid #e6e7eb;
      background:#fff;
      min-width:0;
      font-size:0.95rem;
    }
    button.primary {
      background:var(--accent);
      color:#fff;
      padding:10px 14px;
      border-radius:10px;
      border:0;
      cursor:pointer;
      font-weight:600;
    }
    button.secondary {
      background:transparent;
      border:1px solid #e6e7eb;
      padding:8px 12px;
      border-radius:10px;
      cursor:pointer;
      color:var(--accent);
    }

    /* grid */
    .grid {
      display:grid;
      grid-template-columns: repeat(4, 1fr);
      gap:var(--gap);
    }
    @media (max-width:1100px){ .grid{grid-template-columns:repeat(3,1fr);} }
    @media (max-width:760px){ .grid{grid-template-columns:repeat(2,1fr);} }
    @media (max-width:460px){ .grid{grid-template-columns:repeat(1,1fr);} }

    .card {
      background:var(--card-bg);
      border-radius:var(--radius);
      padding:12px;
      box-shadow: 0 6px 14px rgba(17,24,39,0.06);
      display:flex;
      gap:12px;
      align-items:flex-start;
      transition:transform .12s ease, box-shadow .12s ease;
    }
    .card:hover{ transform: translateY(-4px); box-shadow:0 12px 30px rgba(17,24,39,0.08); }

    .thumb {
      width:120px;
      height:90px;
      border-radius:10px;
      object-fit:cover;
      background:#f3f4f6;
      flex: 0 0 120px;
    }
    .card-info { flex:1; }
    .meta { font-size:0.82rem; color:var(--muted); margin-bottom:6px; }
    .title { font-size:1rem; font-weight:700; margin-bottom:6px; color:var(--accent); }
    .price { font-weight:700; color:#0b8043; margin-bottom:8px; } /* green price */
    .actions { display:flex; gap:8px; align-items:center; margin-top:8px; }
    .btn-cart { background:#ef4444; color:#fff; border:0; padding:8px 12px; border-radius:8px; cursor:pointer; font-weight:600; }
    .small { font-size:0.85rem; color:var(--muted); }

    /* pagination */
    .pagination {
      display:flex;
      gap:8px;
      justify-content:center;
      align-items:center;
      margin-top:18px;
      flex-wrap:wrap;
    }
    .page-btn {
      background:#fff;
      padding:8px 10px;
      border-radius:8px;
      border:1px solid #e6e7eb;
      cursor:pointer;
    }
    .page-btn.active { background:var(--accent); color:#fff; border-color:var(--accent); }

    /* empty / loader */
    .empty {
      padding:36px;
      text-align:center;
      background:#fff;
      border-radius:var(--radius);
      box-shadow:0 6px 18px rgba(17,24,39,0.04);
      margin-top:12px;
    }

    /* top filters area */
    .filters {
      display:flex;
      gap:8px;
      align-items:center;
      margin-left:auto;
      flex-wrap:wrap;
    }

    /* product count */
    .count { font-size:0.9rem; color:var(--muted); margin-left:8px; }

  </style>
</head>
<body>
  <div class="container" role="main">
    <div class="header" aria-hidden="false">
      <div class="brand"> Foods</div>

      <div class="search-box" role="search">
        <input id="searchInput" type="search" placeholder="Search products, e.g., chapati" aria-label="Search products" />
        <button id="searchBtn" class="primary" title="Search">Search</button>
        <button id="clearBtn" class="secondary" title="Clear">Clear</button>
      </div>

      <div class="filters" aria-label="Filters">
        <select id="categoryFilter" aria-label="Filter by category">
          <option value="">All Categories</option>
        </select>

        <select id="brandFilter" aria-label="Filter by brand">
          <option value="">All Brands</option>
        </select>

        <select id="sortSelect" aria-label="Sort by">
          <option value="newest">Newest</option>
          <option value="price_asc">Price: Low → High</option>
          <option value="price_desc">Price: High → Low</option>
        </select>

        <div class="count" id="resultCount" aria-live="polite"></div>
      </div>
    </div>

    <!-- Product grid -->
    <div id="productGrid" class="grid" aria-live="polite"></div>

    <!-- Empty / no results -->
    <div id="emptyState" class="empty" style="display:none;">
      <strong>No products found</strong>
      <p class="small">Try a different search, category, or brand.</p>
    </div>

    <!-- Pagination -->
    <div id="pagination" class="pagination" aria-label="Pagination"></div>
  </div>

  <!-- Scripts: AJAX + rendering + pagination -->
  <script>
  // CONFIG: update if the actions file is at a different path
  const ACTIONS_URL = '../actions/product_actions.php';

  const state = {
    products: [],      // all currently loaded products (after last fetch)
    filtered: [],      // products after applying client-side sort/filter (for pagination)
    page: 1,
    perPage: 10
  };

  // DOM refs
  const productGrid = document.getElementById('productGrid');
  const categoryFilter = document.getElementById('categoryFilter');
  const brandFilter = document.getElementById('brandFilter');
  const searchInput = document.getElementById('searchInput');
  const searchBtn = document.getElementById('searchBtn');
  const clearBtn = document.getElementById('clearBtn');
  const paginationEl = document.getElementById('pagination');
  const emptyState = document.getElementById('emptyState');
  const resultCount = document.getElementById('resultCount');
  const sortSelect = document.getElementById('sortSelect');

  /* ---------------------------
     Helpers
  --------------------------- */
  function safeText(t){ return String(t ?? '').replaceAll('<','&lt;').replaceAll('>','&gt;'); }

  function showLoader() {
    productGrid.innerHTML = '<div style="grid-column:1/-1;padding:18px;text-align:center;">Loading…</div>';
  }

  function fetchJSON(url) {
    return fetch(url, {credentials:'same-origin'}).then(res => res.json());
  }

  /* ---------------------------
     Initial load: get all products
  --------------------------- */
  async function loadAllProducts() {
    showLoader();
    try {
      const res = await fetchJSON(`${ACTIONS_URL}?action=view_all`);
      if (res.status === 'success' && Array.isArray(res.data)) {
        state.products = res.data;
        populateFiltersFromProducts(state.products);
        applyClientFiltersAndRender(); // initial render
      } else {
        state.products = [];
        applyClientFiltersAndRender();
      }
    } catch (err) {
      productGrid.innerHTML = '<div style="grid-column:1/-1;padding:18px;text-align:center;color:#ef4444;">Failed to load products.</div>';
      console.error(err);
    }
  }

  /* ---------------------------
     Populate category & brand dropdowns from product list
     This keeps things simple and avoids creating separate endpoints.
  --------------------------- */
  function populateFiltersFromProducts(products) {
    const cats = new Map(); // cat_id => cat_name
    const brands = new Map();

    products.forEach(p => {
      if (p.cat_name && p.product_cat) cats.set(p.product_cat, p.cat_name);
      if (p.brand_name && p.product_brand) brands.set(p.product_brand, p.brand_name);
    });

    // clear existing (except the "All" option at index 0)
    categoryFilter.querySelectorAll('option:not([value=""])').forEach(n => n.remove());
    brandFilter.querySelectorAll('option:not([value=""])').forEach(n => n.remove());

    // append categories
    Array.from(cats.entries()).sort((a,b)=>a[1].localeCompare(b[1])).forEach(([id,name])=>{
      const opt = document.createElement('option');
      opt.value = id;
      opt.textContent = name;
      categoryFilter.appendChild(opt);
    });

    // append brands
    Array.from(brands.entries()).sort((a,b)=>a[1].localeCompare(b[1])).forEach(([id,name])=>{
      const opt = document.createElement('option');
      opt.value = id;
      opt.textContent = name;
      brandFilter.appendChild(opt);
    });
  }

  /* ---------------------------
     Apply client-side filters/sorting and render
  --------------------------- */
  function applyClientFiltersAndRender() {
    const q = (searchInput.value || '').trim().toLowerCase();
    const cat = categoryFilter.value;
    const brand = brandFilter.value;
    const sort = sortSelect.value;

    // filter
    let filtered = state.products.filter(p => {
      // basic search on title and keywords (case-insensitive)
      let matchesQuery = true;
      if (q) {
        const title = String(p.product_title || '').toLowerCase();
        const keywords = String(p.product_keywords || '').toLowerCase();
        matchesQuery = title.includes(q) || keywords.includes(q);
      }
      let matchesCat = true;
      if (cat) matchesCat = String(p.product_cat) === String(cat);
      let matchesBrand = true;
      if (brand) matchesBrand = String(p.product_brand) === String(brand);
      return matchesQuery && matchesCat && matchesBrand;
    });

    // sort
    if (sort === 'price_asc') {
      filtered.sort((a,b)=> Number(a.product_price || 0) - Number(b.product_price || 0));
    } else if (sort === 'price_desc') {
      filtered.sort((a,b)=> Number(b.product_price || 0) - Number(a.product_price || 0));
    } else { // newest default — sort by product_id desc if available
      filtered.sort((a,b)=> Number(b.product_id || 0) - Number(a.product_id || 0));
    }

    state.filtered = filtered;
    state.page = 1; // reset to first page when filters change
    renderPage();
  }

  /* ---------------------------
     Render specific page from state.filtered
  --------------------------- */
  function renderPage() {
    const items = state.filtered;
    const total = items.length;
    const per = state.perPage;
    const pages = Math.max(1, Math.ceil(total / per));
    if (state.page > pages) state.page = pages;

    // page slice
    const start = (state.page - 1) * per;
    const pageItems = items.slice(start, start + per);

    // render
    if (pageItems.length === 0) {
      productGrid.innerHTML = '';
      emptyState.style.display = 'block';
    } else {
      emptyState.style.display = 'none';
      productGrid.innerHTML = pageItems.map(renderCardHtml).join('');
    }

    // result count
    resultCount.textContent = `${total} item${total!==1 ? 's':''}`;

    renderPagination(pages);
  }

  function renderCardHtml(p) {
    const imgPath = p.product_image ? `../${p.product_image}` : 'data:image/svg+xml;charset=utf-8,<svg xmlns="http://www.w3.org/2000/svg" width="160" height="120"></svg>';
    const title = safeText(p.product_title || 'Untitled');
    const price = typeof p.product_price !== 'undefined' && p.product_price !== null ? `${p.product_price} FBu` : 'Price N/A';
    const cat = safeText(p.cat_name || 'Uncategorized');
    const brand = safeText(p.brand_name || 'Unknown');

    // product URL for single view — append product_id as query param
    const url = `single_product.php?id=${encodeURIComponent(p.product_id)}`;

    return `
      <article class="card" aria-label="${title}">
        <img class="thumb" src="${imgPath}" alt="${title} image" onerror="this.onerror=null;this.src='data:image/svg+xml;charset=utf-8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22160%22 height=%2290%22></svg>'" />
        <div class="card-info">
          <div class="meta small">#${safeText(p.product_id)} · ${cat} · ${brand}</div>
          <div class="title"><a href="${url}" style="text-decoration:none;color:inherit;">${title}</a></div>
          <div class="price">${price}</div>
          <div class="small">${safeText(String(p.product_desc || '').slice(0,120))}${(p.product_desc||'').length>120 ? '…':''}</div>
          <div class="actions">
            <a href="${url}" class="page-btn" style="text-decoration:none;">View</a>
            <button class="btn-cart" onclick="handleAddToCart(${encodeURIComponent(p.product_id)})">Add to Cart</button>
          </div>
        </div>
      </article>
    `;
  }

  /* ---------------------------
     Render pagination buttons
  --------------------------- */
  function renderPagination(totalPages) {
    paginationEl.innerHTML = '';
    if (totalPages <= 1) return;

    // prev
    const prev = document.createElement('button');
    prev.className = 'page-btn';
    prev.textContent = 'Prev';
    prev.disabled = state.page === 1;
    prev.onclick = () => { if(state.page>1){ state.page--; renderPage(); } };
    paginationEl.appendChild(prev);

    // page numbers (small window)
    const maxButtons = 7;
    let start = Math.max(1, state.page - Math.floor(maxButtons/2));
    let end = Math.min(totalPages, start + maxButtons - 1);
    if (end - start + 1 < maxButtons) {
      start = Math.max(1, end - maxButtons + 1);
    }

    for (let i=start;i<=end;i++){
      const btn = document.createElement('button');
      btn.className = 'page-btn' + (i===state.page ? ' active' : '');
      btn.textContent = i;
      btn.onclick = () => { state.page = i; renderPage(); };
      paginationEl.appendChild(btn);
    }

    // next
    const next = document.createElement('button');
    next.className = 'page-btn';
    next.textContent = 'Next';
    next.disabled = state.page === totalPages;
    next.onclick = () => { if(state.page<totalPages){ state.page++; renderPage(); } };
    paginationEl.appendChild(next);
  }

  /* ---------------------------
     Add to wishlist (cart)
  --------------------------- */
  async function handleAddToCart(productId) {
    try{
      const res = await Cart.add(productId, 1);
      if(res.status==='success'){
        alert('Added to wishlist');
      }else if(res.message){
        alert(res.message);
      }else{
        alert('Failed to add');
      }
    }catch(e){
      alert('Failed to add');
      console.error(e);
    }
  }

  /* ---------------------------
     Event bindings
  --------------------------- */
  searchBtn.addEventListener('click', () => {
    // We'll do client-side filtering on the products we already loaded.
    applyClientFiltersAndRender();
  });

  clearBtn.addEventListener('click', () => {
    searchInput.value = '';
    categoryFilter.value = '';
    brandFilter.value = '';
    sortSelect.value = 'newest';
    applyClientFiltersAndRender();
  });

  // apply filters when changed
  [categoryFilter, brandFilter, sortSelect].forEach(el => {
    el.addEventListener('change', () => {
      applyClientFiltersAndRender();
    });
  });

  // live search (debounced)
  let debounceTimer;
  searchInput.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(()=> applyClientFiltersAndRender(), 300);
  });

  /* ---------------------------
     Initial call
  --------------------------- */
  loadAllProducts();
  </script>
  <script src="../js/cart.js"></script>
</body>
</html>
