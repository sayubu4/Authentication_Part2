<?php
require_once(__DIR__ . '/settings/core.php');
require_once(__DIR__ . '/controllers/product_controller.php');
if (!isLoggedIn()) {
    header('Location: login/login.php');
    exit;
}
// Role check
$isAdmin = isAdmin();
// Fetch a few products to showcase
$products = get_all_products_ctr() ?: [];
$showcase = array_slice($products, 0, 6);
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
    :root {
        --primary-color: #F97316;
        --primary-hover: #EA580C;
        --primary-dark: #9A3412;
        --light-bg: #FFF7ED;
        --card-bg: #ffffff;
        --text-color: #1f2937;
        --text-light: #6b7280;
        --border-color: #e5e7eb;
    }
    
    body {
        background-color: var(--light-bg);
        color: var(--text-color);
    }
    
    .menu-tray {
        position: fixed;
        top: 16px;
        right: 16px;
        background: rgba(255, 247, 237, 0.95);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 6px 10px;
        box-shadow: 0 4px 10px rgba(249, 115, 22, 0.1);
        z-index: 1000;
    }
    
    .menu-tray a { 
        margin-left: 8px;
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .menu-tray a:hover {
        background-color: var(--primary-color);
        color: white;
    }
    
    .hero {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
        color: white;
        border-radius: 16px;
        padding: 28px;
        margin: 24px 0;
    }
    
    .grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); 
        gap: 16px; 
    }
    
    .card-food { 
        background: var(--card-bg); 
        border-radius: 12px; 
        overflow: hidden; 
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card-food:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(249, 115, 22, 0.15);
    }
    
    .card-food img { 
        width: 100%; 
        height: 150px; 
        object-fit: cover; 
        background: #f9fafb; 
    }
    
    .card-food .body { 
        padding: 16px; 
    }
    
    .muted { 
        color: var(--text-light); 
        font-size: 0.92rem; 
    }
    
    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-outline-secondary {
        color: var(--text-light);
        border-color: var(--border-color);
    }
    
    .btn-outline-secondary:hover {
        background-color: #f9fafb;
        color: var(--text-color);
    }
    
    .btn-outline-danger {
        color: #dc2626;
        border-color: #dc2626;
    }
    
    .btn-outline-danger:hover {
        background-color: #dc2626;
        color: white;
    }
    
    /* Price styling */
    .price {
        color: var(--primary-color);
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .card-food:hover .price {
        color: var(--primary-hover);
    }

    /* Explore button */
    .btn-explore {
        background-color: var(--primary-color);
        color: white;
        border: 1px solid var(--primary-color);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-explore:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
        color: white;
        transform: translateY(-1px);
    }

    .btn-explore:active {
        transform: translateY(0);
    }
    
    /* Region cards hover effect */
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(249, 115, 22, 0.15) !important;
    }
    
    /* Orange background utility */
    .bg-orange-50 {
        background-color: rgba(249, 115, 22, 0.1);
    }
    
    .text-orange-600 {
        color: #ea580c;
    }
</style>
</head>
<body class="bg-light">
    <div class="menu-tray">
        <?php if ($isAdmin): ?>
            <a class="btn btn-sm btn-outline-primary" href="Admin/category.php"><i class="fa fa-folder"></i> Categories</a>
            <a class="btn btn-sm btn-outline-primary" href="Admin/brand.php"><i class="fa fa-tags"></i> Brands</a>
            <a class="btn btn-sm btn-outline-primary" href="Admin/product.php"><i class="fa fa-box"></i> Manage Products</a>
            <a class="btn btn-sm btn-outline-secondary" href="login/all_product.php"><i class="fa fa-store"></i> View Site</a>
            <a class="btn btn-sm btn-outline-secondary" href="login/about.php"><i class="fa fa-info-circle"></i> About</a>
            <a class="btn btn-sm btn-outline-danger" href="login/logout.php"><i class="fa fa-sign-out"></i> Logout</a>
        <?php else: ?>
            <a class="btn btn-sm btn-outline-secondary" href="login/all_product.php"><i class="fa fa-list"></i> All Products</a>
            <a class="btn btn-sm btn-outline-secondary" href="login/cart.php"><i class="fa fa-heart"></i> Try Later</a>
            <a class="btn btn-sm btn-outline-secondary" href="login/about.php"><i class="fa fa-info-circle"></i> About</a>
            <a class="btn btn-sm btn-outline-danger" href="login/logout.php"><i class="fa fa-sign-out"></i> Logout</a>
        <?php endif; ?>
    </div>

    <div class="container py-4">
        <?php if ($isAdmin): ?>
            <div class="hero">
                <h1 class="h3 mb-1">Admin Dashboard</h1>
                <p class="mb-0">Manage products, categories, and brands.</p>
                <div class="mt-3">
                    <a class="btn btn-outline-light btn-sm" href="Admin/category.php"><i class="fa fa-folder"></i> Categories</a>
                    <a class="btn btn-outline-light btn-sm" href="Admin/brand.php"><i class="fa fa-tags"></i> Brands</a>
                    <a class="btn btn-light btn-sm" href="Admin/product.php"><i class="fa fa-box"></i> Manage Products</a>
                </div>
            </div>

            <h2 class="h5 mb-3">Quick Actions</h2>
            <div class="grid">
                <article class="card-food">
                    <div class="body">
                        <div class="fw-semibold mb-1">Add a new product</div>
                        <a class="btn btn-sm btn-outline-dark" href="Admin/product.php">Open Products</a>
                    </div>
                </article>
                <article class="card-food">
                    <div class="body">
                        <div class="fw-semibold mb-1">Create a category</div>
                        <a class="btn btn-sm btn-outline-dark" href="Admin/category.php">Open Categories</a>
                    </div>
                </article>
                <article class="card-food">
                    <div class="body">
                        <div class="fw-semibold mb-1">Create a brand</div>
                        <a class="btn btn-sm btn-outline-dark" href="Admin/brand.php">Open Brands</a>
                    </div>
                </article>
            </div>
        <?php else: ?>
            <div class="hero">
                <h1 class="h3 mb-1">Discover local foods</h1>
                <p class="mb-0">Browse curated dishes. Subscribe later for full details and insider tips.</p>
                <div class="mt-3">
                    <a class="btn btn-light btn-sm" href="login/all_product.php"><i class="fa fa-list"></i> View All Products</a>
                    <a class="btn btn-outline-light btn-sm" href="login/cart.php"><i class="fa fa-heart"></i> View Try Later</a>
                </div>
            </div>

            <h2 class="h5 mb-3">Featured</h2>
            <div class="grid">
                <?php if (count($showcase)===0): ?>
                    <div class="muted">No items yet. Visit All Products.</div>
                <?php else: foreach ($showcase as $p): ?>
                    <?php $img = !empty($p['product_image']) ? ('./' . ltrim($p['product_image'],'/')) : ''; ?>
                    <article class="card-food">
                        <img src="<?php echo h($img); ?>" alt="<?php echo h($p['product_title']); ?>" onerror="this.style.display='none'">
                        <div class="body">
                            <div class="muted">#<?php echo (int)$p['product_id']; ?> · <?php echo h($p['cat_name'] ?? ''); ?></div>
                            <div class="fw-semibold"><?php echo h($p['product_title']); ?></div>
                            <div class="price fw-bold mb-1"><?php echo h($p['product_price']); ?> FBu</div>
                            <a class="btn btn-sm btn-explore" href="login/all_product.php">
                                <i class="fa fa-eye"></i> Explore
                            </a>
                        </div>
                    </article>
                <?php endforeach; endif; ?>
            </div>

            <!-- Regions Section -->
            <div class="mt-5 pt-4 border-top">
                <h2 class="h5 mb-4">Explore by Region</h2>
                <div class="row g-4">
                    <!-- Buhumuza -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm hover-lift">
                            <div class="card-body text-center p-4">
                                <div class="bg-orange-50 text-orange-600 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; margin: 0 auto;">
                                    <i class="fas fa-mountain fa-2x"></i>
                                </div>
                                <h3 class="h5 mb-2">Buhumuza</h3>
                                <p class="text-muted mb-3">Discover the traditional flavors and local delicacies of Buhumuza region.</p>
                                <a href="login/all_product.php?region_id=1" class="btn btn-outline-primary btn-sm">
                                    View Foods <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Bujumbura -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm hover-lift">
                            <div class="card-body text-center p-4">
                                <div class="bg-orange-50 text-orange-600 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; margin: 0 auto;">
                                    <i class="fas fa-city fa-2x"></i>
                                </div>
                                <h3 class="h5 mb-2">Bujumbura</h3>
                                <p class="text-muted mb-3">Experience the vibrant food scene of Burundi's largest city.</p>
                                <a href="login/all_product.php?region_id=2" class="btn btn-outline-primary btn-sm">
                                    View Foods <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Burunga -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm hover-lift">
                            <div class="card-body text-center p-4">
                                <div class="bg-orange-50 text-orange-600 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; margin: 0 auto;">
                                    <i class="fas fa-water fa-2x"></i>
                                </div>
                                <h3 class="h5 mb-2">Burunga</h3>
                                <p class="text-muted mb-3">Taste the fresh fish and lakeside cuisine of Burunga region.</p>
                                <a href="login/all_product.php?region_id=3" class="btn btn-outline-primary btn-sm">
                                    View Foods <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Butanyerera -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm hover-lift">
                            <div class="card-body text-center p-4">
                                <div class="bg-orange-50 text-orange-600 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; margin: 0 auto;">
                                    <i class="fas fa-seedling fa-2x"></i>
                                </div>
                                <h3 class="h5 mb-2">Butanyerera</h3>
                                <p class="text-muted mb-3">Enjoy the agricultural bounty and farm-fresh flavors.</p>
                                <a href="login/all_product.php?region_id=4" class="btn btn-outline-primary btn-sm">
                                    View Foods <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Gitega -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm hover-lift">
                            <div class="card-body text-center p-4">
                                <div class="bg-orange-50 text-orange-600 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; margin: 0 auto;">
                                    <i class="fas fa-hills fa-2x"></i>
                                </div>
                                <h3 class="h5 mb-2">Gitega</h3>
                                <p class="text-muted mb-3">Explore the highland cuisine and traditional dishes of Gitega.</p>
                                <a href="login/all_product.php?region_id=5" class="btn btn-outline-primary btn-sm">
                                    View Foods <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Regions Section -->
        <?php endif; ?>
    </div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
