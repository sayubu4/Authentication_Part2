<?php
// Start session
session_start();

// Include product controller
require_once('../controllers/product_controller.php');

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    header('Location: all_product.php');
    exit;
}

// Get single product
$product = view_single_product_ctr($product_id);

if (!$product) {
    header('Location: all_product.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_title']); ?> - Burundi Eats</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .back-link {
            display: inline-block;
            padding: 12px 25px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin-bottom: 30px;
            transition: all 0.3s;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .back-link:hover {
            background: #667eea;
            color: white;
            transform: translateX(-5px);
        }

        .product-detail {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
        }

        .product-image-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .product-info {
            padding: 50px;
        }

        .badge-container {
            margin-bottom: 20px;
        }

        .badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.9em;
            font-weight: 600;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .badge-category {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .badge-brand {
            background: #e3f2fd;
            color: #1565c0;
        }

        .product-title {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 15px;
            font-weight: 700;
            line-height: 1.2;
        }

        .product-price {
            font-size: 3em;
            color: #667eea;
            font-weight: 700;
            margin: 20px 0;
        }

        .product-description {
            font-size: 1.1em;
            color: #666;
            line-height: 1.8;
            margin: 25px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }

        .product-meta {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 30px 0;
        }

        .meta-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .meta-label {
            font-size: 0.85em;
            color: #999;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .meta-value {
            font-size: 1.1em;
            color: #333;
            font-weight: 600;
        }

        .keywords {
            margin: 25px 0;
        }

        .keywords-title {
            font-size: 1em;
            color: #999;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .keyword-tag {
            display: inline-block;
            padding: 8px 15px;
            background: #fff3e0;
            color: #e65100;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 600;
            margin-right: 8px;
            margin-bottom: 8px;
        }

        .btn-add-cart {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 1.2em;
            font-weight: 700;
            transition: all 0.3s;
            margin-top: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-add-cart:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        @media (max-width: 768px) {
            .product-detail {
                grid-template-columns: 1fr;
            }

            .product-info {
                padding: 30px;
            }

            .product-title {
                font-size: 2em;
            }

            .product-price {
                font-size: 2.5em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="all_product.php" class="back-link">← Back to All Food Spots</a>

        <div class="product-detail">
            <!-- Image Section -->
            <div class="product-image-section">
                <?php 
                $image_path = "../images/product/" . $product['product_image'];
                if (!file_exists($image_path) || empty($product['product_image'])): 
                    $image_path = "https://via.placeholder.com/500/667eea/ffffff?text=" . urlencode($product['product_title']);
                endif;
                ?>
                <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($product['product_title']); ?>" class="product-image">
            </div>

            <!-- Info Section -->
            <div class="product-info">
                <div class="badge-container">
                    <span class="badge badge-category"><?php echo htmlspecialchars($product['cat_name']); ?></span>
                    <span class="badge badge-brand"><?php echo htmlspecialchars($product['brand_name']); ?></span>
                </div>

                <h1 class="product-title"><?php echo htmlspecialchars($product['product_title']); ?></h1>

                <div class="product-price">FBu <?php echo number_format($product['product_price'], 0); ?></div>

                <div class="product-description">
                    <?php echo nl2br(htmlspecialchars($product['product_desc'])); ?>
                </div>

                <div class="product-meta">
                    <div class="meta-item">
                        <div class="meta-label">Product ID</div>
                        <div class="meta-value">#<?php echo $product['product_id']; ?></div>
                    </div>

                    <div class="meta-item">
                        <div class="meta-label">Category</div>
                        <div class="meta-value"><?php echo htmlspecialchars($product['cat_name']); ?></div>
                    </div>

                    <div class="meta-item">
                        <div class="meta-label">Restaurant/Brand</div>
                        <div class="meta-value"><?php echo htmlspecialchars($product['brand_name']); ?></div>
                    </div>

                    <div class="meta-item">
                        <div class="meta-label">Price</div>
                        <div class="meta-value">FBu <?php echo number_format($product['product_price'], 0); ?></div>
                    </div>
                </div>

                <?php if (!empty($product['product_keywords'])): ?>
                    <div class="keywords">
                        <div class="keywords-title">Tags & Keywords</div>
                        <?php 
                        $keywords = explode(',', $product['product_keywords']);
                        foreach ($keywords as $keyword): 
                            $keyword = trim($keyword);
                            if (!empty($keyword)):
                        ?>
                            <span class="keyword-tag"><?php echo htmlspecialchars($keyword); ?></span>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                <?php endif; ?>

                <button class="btn-add-cart" onclick="addToWishlist(<?php echo $product['product_id']; ?>)">
                    ❤ Add to Wishlist
                </button>
            </div>
        </div>
    </div>

    <script src="../js/cart.js"></script>
    <script>
        async function addToWishlist(productId){
            try{
                const res = await Cart.add(productId, 1);
                if(res.status==='success') alert('Added to wishlist');
                else alert(res.message||'Failed');
            }catch(e){ alert('Failed'); }
        }
    </script>
</body>
</html>