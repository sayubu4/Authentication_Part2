<?php
// Start session
session_start();

// Include product controller and core/db helpers
require_once('../controllers/product_controller.php');
require_once('../settings/core.php');
require_once('../settings/db_class.php');

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

// Determine if current user is subscribed:
// We consider a user subscribed only if they have at least one Paystack payment
// recorded in the payment table for their customer_id.
$isSubscribed = false;
if (isset($_SESSION['customer_id'])) {
	$customer_id = (int)$_SESSION['customer_id'];
	$db = new db_connection();
	// Check for any Paystack payment (including subscriptions with order_id = 0)
	$row = $db->db_fetch_one("SELECT COUNT(*) AS cnt FROM payment WHERE customer_id = '$customer_id' AND payment_method = 'paystack'");
	$isSubscribed = $row && isset($row['cnt']) && (int)$row['cnt'] > 0;
}

// If user just subscribed, show success message
$justSubscribed = isset($_GET['subscribed']) && $_GET['subscribed'] == '1';
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

        :root {
            --primary-color: #F97316;
            --primary-hover: #EA580C;
            --primary-dark: #9A3412;
            --light-bg: #FFF7ED;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--light-bg);
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .back-link {
            display: inline-block;
            padding: 10px 20px;
            background: white;
            color: var(--primary-color);
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin-bottom: 25px;
            transition: all 0.3s;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            border: 1px solid var(--primary-color);
            font-size: 0.95em;
        }

        .back-link:hover {
            background: var(--primary-color);
            color: white;
            transform: translateX(-3px);
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
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            padding: 30px;
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
            font-size: 1.8em;
            color: #1f2937;
            margin-bottom: 12px;
            font-weight: 700;
            line-height: 1.3;
        }

        .product-price {
            font-size: 1.8em;
            color: var(--primary-color);
            font-weight: 700;
            margin: 15px 0;
        }

        .product-description {
            font-size: 1em;
            color: #4b5563;
            line-height: 1.7;
            margin: 20px 0;
            padding: 15px;
            background: #fff;
            border-radius: 8px;
            border-left: 3px solid var(--primary-color);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
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
            padding: 14px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            transition: all 0.3s;
            margin-top: 25px;
            text-transform: none;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-add-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(249, 115, 22, 0.3);
        }

        @media (max-width: 768px) {
            .product-detail {
                grid-template-columns: 1fr;
            }

            .product-info {
                padding: 25px;
            }

            .product-title {
                font-size: 1.6em;
            }

            .product-price {
                font-size: 1.6em;
            }
            
            .product-image-section {
                padding: 20px;
            }
            
            .product-description {
                font-size: 0.95em;
                padding: 12px;
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
                // Use the stored relative path (e.g., Images/u{user}/p{product}/file.jpg)
                $image_path = '';
                if (!empty($product['product_image'])) {
                    $candidate = "../" . ltrim($product['product_image'], '/');
                    if (file_exists($candidate)) {
                        $image_path = $candidate;
                    }
                }
                if (empty($image_path)) {
                    $image_path = "https://via.placeholder.com/500/667eea/ffffff?text=" . urlencode($product['product_title']);
                }
                ?>
                <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($product['product_title']); ?>" class="product-image">
            </div>

            <!-- Info Section -->
            <div class="product-info">
                <?php if ($justSubscribed && $isSubscribed): ?>
                <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                    <strong>🎉 Subscription Successful!</strong> Premium content is now unlocked.
                </div>
                <?php endif; ?>
                
                <div class="badge-container">
                    <span class="badge badge-category"><?php echo htmlspecialchars($product['cat_name']); ?></span>
                    <span class="badge badge-brand"><?php echo htmlspecialchars($product['brand_name']); ?></span>
                </div>

                <h1 class="product-title"><?php echo htmlspecialchars($product['product_title']); ?></h1>

                <?php if ($isSubscribed): ?>
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

                        <?php if (!empty($product['opening_hours'])): ?>
                        <div class="meta-item">
                            <div class="meta-label">Opening Hours</div>
                            <div class="meta-value"><?php echo htmlspecialchars($product['opening_hours']); ?></div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($product['contact_phone'])): ?>
                        <div class="meta-item">
                            <div class="meta-label">Phone</div>
                            <div class="meta-value"><?php echo htmlspecialchars($product['contact_phone']); ?></div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($product['exact_location'])): ?>
                        <div class="meta-item">
                            <div class="meta-label">Exact Location</div>
                            <div class="meta-value"><?php echo htmlspecialchars($product['exact_location']); ?></div>
                        </div>
                        <?php endif; ?>
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
                <?php else: ?>
                    <div class="product-price" style="opacity:0.6; font-size:1.6em;">
                        Premium price · Subscribe to view
                    </div>

                    <div class="product-description">
                        To protect our partners, full details for this spot (exact location, working hours and full description) are available only to subscribers.
                    </div>

                    <div class="meta-item" style="margin-top:15px;background:#fffbeb; border-radius:8px; padding:12px; border:1px solid #fef3c7;">
                        <div class="meta-label" style="color:#9a3412; font-weight:600; margin-bottom:5px;">🔒 Premium Content</div>
                        <div class="meta-value" style="color:#92400e; font-size:0.9em; line-height:1.5;">
                            Subscribe to unlock all premium details for every food spot.
                        </div>
                    </div>

                    <button class="btn-add-cart" style="margin-top:20px; background:linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-hover) 100%);" onclick="window.location.href='subscribe.php';">
                        🔓 Unlock All Premium Content
                    </button>
                <?php endif; ?>

                <button class="btn-add-cart" onclick="addToWishlist(<?php echo $product['product_id']; ?>)">
                    ❤ Add to Try Later
                </button>
            </div>
        </div>
    </div>

    <script src="../js/cart.js"></script>
    <script>
        async function addToWishlist(productId){
            try{
                const res = await Cart.add(productId, 1);
                if(res.status==='success') alert('Added to Try Later');
                else alert(res.message||'Failed');
            }catch(e){ alert('Failed'); }
        }
    </script>
</body>
</html>