<?php
session_start();

// Include controllers
require_once(__DIR__ . '/../controllers/product_controller.php');
require_once(__DIR__ . '/../controllers/category_controller.php');
require_once(__DIR__ . '/../controllers/brand_controller.php');

// Get all products
$products = get_all_products_ctr();
$categories = get_all_categories_ctr();
$brands = get_all_brands_ctr();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
            padding: 20px;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        .product-image-container {
            width: 100%;
            height: 250px;
            overflow: hidden;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .product-body {
            padding: 15px;
        }
        .product-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .product-price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #F97316;
            margin-bottom: 10px;
        }
        .product-description {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .no-products {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fa fa-shopping-bag text-primary"></i> Our Products</h2>
            <a href="../index.php" class="btn btn-outline-secondary">
                <i class="fa fa-home"></i> Back to Home
            </a>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label"><i class="fa fa-search"></i> Search</label>
                    <input type="text" class="form-control" id="searchInput" placeholder="Search products...">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label"><i class="fa fa-tags"></i> Category</label>
                    <select class="form-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        <?php if ($categories): ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['cat_id']; ?>">
                                    <?php echo htmlspecialchars($cat['cat_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label"><i class="fa fa-tag"></i> Brand</label>
                    <select class="form-select" id="brandFilter">
                        <option value="">All Brands</option>
                        <?php if ($brands): ?>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?php echo $brand['brand_id']; ?>">
                                    <?php echo htmlspecialchars($brand['brand_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="product-grid" id="productsGrid">
            <?php if ($products && count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card" 
                         data-category="<?php echo $product['product_cat']; ?>"
                         data-brand="<?php echo $product['product_brand']; ?>"
                         data-title="<?php echo strtolower($product['product_title']); ?>"
                         data-keywords="<?php echo strtolower($product['product_keywords']); ?>">
                        <div class="product-image-container">
                            <?php if (!empty($product['product_image'])): ?>
                                <img src="../<?php echo htmlspecialchars($product['product_image']); ?>" 
                                     class="product-image" 
                                     alt="<?php echo htmlspecialchars($product['product_title']); ?>">
                            <?php else: ?>
                                <div class="text-muted">
                                    <i class="fa fa-image fa-3x"></i>
                                    <p>No Image</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="product-body">
                            <h5 class="product-title"><?php echo htmlspecialchars($product['product_title']); ?></h5>
                            <div class="mb-2">
                                <span class="badge bg-primary"><?php echo htmlspecialchars($product['cat_name']); ?></span>
                                <span class="badge bg-secondary"><?php echo htmlspecialchars($product['brand_name']); ?></span>
                            </div>
                            <p class="product-description"><?php echo htmlspecialchars($product['product_desc']); ?></p>
                            <div class="product-price">$<?php echo number_format($product['product_price'], 2); ?></div>
                            <button class="btn btn-primary w-100">
                                <i class="fa fa-shopping-cart"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="no-products">
                        <i class="fa fa-box-open fa-5x text-muted mb-3"></i>
                        <h3>No Products Available</h3>
                        <p class="text-muted">Check back later for new products!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Filter functionality
            function filterProducts() {
                const searchTerm = $('#searchInput').val().toLowerCase();
                const categoryFilter = $('#categoryFilter').val();
                const brandFilter = $('#brandFilter').val();

                $('.product-card').each(function() {
                    const $card = $(this);
                    const title = $card.data('title');
                    const keywords = $card.data('keywords');
                    const category = $card.data('category').toString();
                    const brand = $card.data('brand').toString();

                    let showCard = true;

                    // Search filter
                    if (searchTerm && !title.includes(searchTerm) && !keywords.includes(searchTerm)) {
                        showCard = false;
                    }

                    // Category filter
                    if (categoryFilter && category !== categoryFilter) {
                        showCard = false;
                    }

                    // Brand filter
                    if (brandFilter && brand !== brandFilter) {
                        showCard = false;
                    }

                    if (showCard) {
                        $card.show();
                    } else {
                        $card.hide();
                    }
                });
            }

            $('#searchInput').on('keyup', filterProducts);
            $('#categoryFilter').on('change', filterProducts);
            $('#brandFilter').on('change', filterProducts);
        });
    </script>
</body>
</html>