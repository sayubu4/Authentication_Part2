<?php
// product_search_result.php
require_once('../controllers/product_controller.php');

// Get search query from URL
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Results - reWear</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f9fafb;
      margin: 0;
      padding: 0;
    }
    .container {
      width: 90%;
      max-width: 1200px;
      margin: 30px auto;
    }
    h2 {
      text-align: center;
      color: #111827;
    }
    .filters {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-bottom: 25px;
      flex-wrap: wrap;
    }
    select, input[type="text"] {
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 14px;
    }
    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
    }
    .product-card {
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      padding: 15px;
      text-align: center;
      transition: transform 0.2s ease;
    }
    .product-card:hover {
      transform: scale(1.03);
    }
    .product-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 12px;
    }
    .product-card h3 {
      font-size: 16px;
      color: #111827;
      margin: 10px 0 5px;
    }
    .product-card p {
      color: #6b7280;
      margin: 5px 0;
    }
    .add-cart {
      display: inline-block;
      padding: 8px 16px;
      background: #111827;
      color: #fff;
      border-radius: 25px;
      font-size: 14px;
      text-decoration: none;
      margin-top: 10px;
      transition: 0.3s;
    }
    .add-cart:hover {
      background: #2563eb;
    }
    .pagination {
      text-align: center;
      margin-top: 30px;
    }
    .pagination button {
      padding: 8px 14px;
      margin: 5px;
      border: none;
      border-radius: 8px;
      background: #e5e7eb;
      cursor: pointer;
      transition: 0.3s;
    }
    .pagination button.active {
      background: #111827;
      color: white;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Search Results for “<?php echo htmlspecialchars($query); ?>”</h2>

    <div class="filters">
      <input type="text" id="searchBox" placeholder="Search products..." value="<?php echo htmlspecialchars($query); ?>">
      <select id="categoryFilter">
        <option value="">All Categories</option>
      </select>
      <select id="brandFilter">
        <option value="">All Brands</option>
      </select>
    </div>

    <div id="productContainer" class="product-grid"></div>
    <div class="pagination" id="pagination"></div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const productContainer = document.getElementById('productContainer');
      const pagination = document.getElementById('pagination');
      const searchBox = document.getElementById('searchBox');
      const categoryFilter = document.getElementById('categoryFilter');
      const brandFilter = document.getElementById('brandFilter');

      let currentPage = 1;

      // Fetch filters (categories, brands)
      fetch('../actions/product_actions.php?action=get_filters')
        .then(res => res.json())
        .then(data => {
          data.categories.forEach(cat => {
            const opt = document.createElement('option');
            opt.value = cat.cat_id;
            opt.textContent = cat.cat_name;
            categoryFilter.appendChild(opt);
          });
          data.brands.forEach(brand => {
            const opt = document.createElement('option');
            opt.value = brand.brand_id;
            opt.textContent = brand.brand_name;
            brandFilter.appendChild(opt);
          });
        });

      // Load products dynamically
      function loadProducts(page = 1) {
        const params = new URLSearchParams({
          action: 'search_products',
          query: searchBox.value,
          category: categoryFilter.value,
          brand: brandFilter.value,
          page: page
        });

        fetch('../actions/product_actions.php?' + params.toString())
          .then(res => res.json())
          .then(data => {
            productContainer.innerHTML = '';
            pagination.innerHTML = '';

            if (data.products.length === 0) {
              productContainer.innerHTML = '<p>No results found.</p>';
              return;
            }

            data.products.forEach(p => {
              const card = document.createElement('div');
              card.className = 'product-card';
              const imgSrc = p.product_image ? `../${p.product_image}` : 'https://via.placeholder.com/300x200?text=No+Image';
              card.innerHTML = `
                <img src="${imgSrc}" alt="${p.product_title}">
                <h3>${p.product_title}</h3>
                <p><strong>Price:</strong> $${p.product_price}</p>
                <p><strong>Category:</strong> ${p.cat_name}</p>
                <p><strong>Brand:</strong> ${p.brand_name}</p>
                <a href="single_product.php?id=${p.product_id}" class="add-cart">View Details</a>
              `;
              productContainer.appendChild(card);
            });

            // Pagination
            for (let i = 1; i <= data.total_pages; i++) {
              const btn = document.createElement('button');
              btn.textContent = i;
              if (i === page) btn.classList.add('active');
              btn.addEventListener('click', () => {
                currentPage = i;
                loadProducts(currentPage);
              });
              pagination.appendChild(btn);
            }
          });
      }

      // Event listeners
      searchBox.addEventListener('input', () => loadProducts());
      categoryFilter.addEventListener('change', () => loadProducts());
      brandFilter.addEventListener('change', () => loadProducts());

      // Initial load
      loadProducts();
    });
  </script>
</body>
</html>
