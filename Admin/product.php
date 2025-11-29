<?php
// Start session with consistent settings
require_once(__DIR__ . '/../settings/core.php');

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    // Store the current URL for redirecting back after login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: ../login/login.php');
    exit();
}

// Include controllers
require_once(__DIR__ . '/../controllers/category_controller.php');
require_once(__DIR__ . '/../controllers/brand_controller.php');
require_once(__DIR__ . '/../controllers/product_controller.php');
require_once(__DIR__ . '/../settings/db_class.php');

// Initialize variables
$categories = $brands = $products = [];
$error = '';

try {
    // Get all categories, brands, and products
    $categories = get_all_categories_ctr() ?: [];
    $brands = get_all_brands_ctr() ?: [];
    $products = get_all_products_ctr() ?: [];
    
    // Get regions for dropdown
    $regions = [];
    $db = new db_connection();
} catch (Exception $e) {
    error_log("Error in product.php: " . $e->getMessage());
    $error = 'An error occurred while loading page data. Please try again.';
}
$regions = $db->db_fetch_all("SELECT region_id, region_name FROM regions ORDER BY region_name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #F97316 0%, #EA580C 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(249, 115, 22, 0.2);
            max-width: 1200px;
        }
        .product-card {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        .product-card:hover {
            box-shadow: 0 5px 15px rgba(249, 115, 22, 0.15);
            transform: translateY(-2px);
        }
        .product-image {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .btn-primary {
            background-color: #F97316;
            border-color: #F97316;
        }
        .btn-primary:hover {
            background-color: #EA580C;
            border-color: #EA580C;
        }
        .text-primary {
            color: #F97316 !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fa fa-box text-primary"></i> Product Management</h2>
            <a href="../index.php" class="btn btn-outline-secondary">
                <i class="fa fa-home"></i> Back to Home
            </a>
        </div>

        <!-- Add/Edit Product Form -->
        <div class="form-section">
            <h4 id="formTitle"><i class="fa fa-plus-circle"></i> Add New Product</h4>
            <form id="productForm">
                <input type="hidden" id="productId" name="product_id">
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Category *</label>
                        <select class="form-select" id="productCategory" name="product_cat" required>
                            <option value="">Select Category</option>
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
                        <label class="form-label">Brand *</label>
                        <select class="form-select" id="productBrand" name="product_brand" required>
                            <option value="">Select Brand</option>
                            <?php if ($brands): ?>
                                <?php foreach ($brands as $brand): ?>
                                    <option value="<?php echo $brand['brand_id']; ?>">
                                        <?php echo htmlspecialchars($brand['brand_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Region *</label>
                        <select class="form-select" id="productRegion" name="region_id" required>
                            <option value="">Select Region</option>
                            <?php if ($regions): ?>
                                <?php foreach ($regions as $region): ?>
                                    <option value="<?php echo $region['region_id']; ?>">
                                        <?php echo htmlspecialchars($region['region_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Product Title *</label>
                        <input type="text" class="form-control" id="productTitle" name="product_title" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Price *</label>
                        <input type="number" step="0.01" class="form-control" id="productPrice" name="product_price" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description *</label>
                    <textarea class="form-control" id="productDesc" name="product_desc" rows="3" required></textarea>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Opening Hours</label>
                        <input type="text" class="form-control" id="openingHours" name="opening_hours" placeholder="e.g., Mon-Sun 9:00 - 21:00">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" class="form-control" id="contactPhone" name="contact_phone" placeholder="e.g., +257 79 00 00 00">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Exact Location</label>
                        <input type="text" class="form-control" id="exactLocation" name="exact_location" placeholder="e.g., Bujumbura, city center, near ...">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Image *</label>
                        <input type="file" class="form-control" id="productImage" name="product_image" accept="image/*" required>
                        <small class="text-muted">Image will be uploaded automatically when selected</small>
                        <input type="hidden" id="imagePath" name="image_path" value="">
                        <div id="currentImagePreview" class="mt-2"></div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Keywords *</label>
                        <input type="text" class="form-control" id="productKeywords" name="product_keywords" placeholder="e.g., electronics, phone, samsung" required>
                        <small class="text-muted">Comma-separated keywords</small>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fa fa-save"></i> Save Product
                    </button>
                    <button type="button" class="btn btn-secondary" id="resetBtn">
                        <i class="fa fa-times"></i> Reset Form
                    </button>
                </div>
            </form>
        </div>

        <!-- Products List -->
        <div>
            <h4><i class="fa fa-list"></i> All Products</h4>
            <div id="productsList">
                <?php if ($products && count($products) > 0): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-card" data-product-id="<?php echo $product['product_id']; ?>">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <?php if (!empty($product['product_image'])): ?>
                                        <img src="../<?php echo htmlspecialchars($product['product_image']); ?>" 
                                             class="product-image" 
                                             alt="<?php echo htmlspecialchars($product['product_title']); ?>">
                                    <?php else: ?>
                                        <div class="product-image bg-secondary d-flex align-items-center justify-content-center text-white">
                                            No Image
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-7">
                                    <h5 class="mb-1"><?php echo htmlspecialchars($product['product_title']); ?></h5>
                                    <p class="mb-1 text-muted">
                                        <span class="badge bg-primary"><?php echo htmlspecialchars($product['cat_name']); ?></span>
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($product['brand_name']); ?></span>
                                    </p>
                                    <p class="mb-1"><strong>Price:</strong> $<?php echo number_format($product['product_price'], 2); ?></p>
                                    <p class="mb-0"><small><?php echo htmlspecialchars(substr($product['product_desc'], 0, 100)); ?>...</small></p>
                                </div>
                                <div class="col-md-3 text-end">
                                    <button class="btn btn-sm btn-warning editBtn" 
                                            data-id="<?php echo $product['product_id']; ?>">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger deleteBtn" 
                                            data-id="<?php echo $product['product_id']; ?>">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> No products found. Add your first product above!
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/product.js"></script>
</body>
</html>