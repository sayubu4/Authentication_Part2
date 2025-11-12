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
        .menu-tray {
            position: fixed;
            top: 16px;
            right: 16px;
            background: rgba(255,255,255,0.95);
            border: 1px solid #e6e6e6;
            border-radius: 8px;
            padding: 6px 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
            z-index: 1000;
        }
        .menu-tray a { margin-left: 8px; }
        .hero {
            background: linear-gradient(135deg, #D19C97, #b77a7a);
            color: #fff;
            border-radius: 16px;
            padding: 28px;
            margin: 24px 0;
        }
        .grid { display:grid; grid-template-columns: repeat( auto-fill, minmax(220px,1fr) ); gap:16px; }
        .card-food { background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 6px 16px rgba(0,0,0,.06); }
        .card-food img { width:100%; height:150px; object-fit:cover; background:#f3f4f6; }
        .card-food .body { padding:12px; }
        .muted{ color:#6b7280; font-size:.92rem; }
    </style>
</head>
<body class="bg-light">
    <div class="menu-tray">
        <?php if ($isAdmin): ?>
            <a class="btn btn-sm btn-outline-primary" href="Admin/category.php"><i class="fa fa-folder"></i> Categories</a>
            <a class="btn btn-sm btn-outline-primary" href="Admin/brand.php"><i class="fa fa-tags"></i> Brands</a>
            <a class="btn btn-sm btn-outline-primary" href="Admin/product.php"><i class="fa fa-box"></i> Manage Products</a>
            <a class="btn btn-sm btn-outline-secondary" href="login/all_product.php"><i class="fa fa-store"></i> View Site</a>
            <a class="btn btn-sm btn-outline-danger" href="login/logout.php"><i class="fa fa-sign-out"></i> Logout</a>
        <?php else: ?>
            <a class="btn btn-sm btn-outline-secondary" href="login/all_product.php"><i class="fa fa-list"></i> All Products</a>
            <a class="btn btn-sm btn-outline-secondary" href="login/cart.php"><i class="fa fa-heart"></i> Wishlist</a>
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
                    <a class="btn btn-outline-light btn-sm" href="login/cart.php"><i class="fa fa-heart"></i> View Wishlist</a>
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
                            <div class="text-success fw-bold mb-1"><?php echo h($p['product_price']); ?> FBu</div>
                            <a class="btn btn-sm btn-outline-dark" href="login/all_product.php">Explore</a>
                        </div>
                    </article>
                <?php endforeach; endif; ?>
            </div>
        <?php endif; ?>
    </div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
