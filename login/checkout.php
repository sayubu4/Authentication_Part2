<?php
require_once(__DIR__ . '/../settings/core.php');
require_once(__DIR__ . '/../controllers/cart_controller.php');
if (!isLoggedIn()) { header('Location: login.php'); exit; }
$customer_id = $_SESSION['customer_id'];
$items = get_user_cart_ctr($customer_id) ?: [];
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
$total = 0; foreach ($items as $it){ $total += ((float)$it['product_price'] * (int)$it['qty']); }
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Checkout</title>
  <style>
    body{font-family:system-ui,Segoe UI,Arial;padding:24px;background:#f8fafc}
    .card{background:#fff;border-radius:12px;padding:16px;box-shadow:0 6px 18px rgba(0,0,0,.06);max-width:760px;margin:auto}
    .row{display:flex;justify-content:space-between;margin:6px 0}
    .btn{padding:10px 14px;border-radius:8px;border:1px solid #e5e7eb;background:#fff;cursor:pointer}
    .btn.primary{background:#111827;color:#fff;border-color:#111827}
    .list{margin:0;padding:0;list-style:none}
    .list li{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f1f5f9}
  </style>
</head>
<body>
  <div class="card">
    <h2>Confirm Reservation</h2>
    <?php if (count($items)===0): ?>
      <p>Your wishlist is empty.</p>
      <a class="btn" href="cart.php">Back to Wishlist</a>
    <?php else: ?>
      <ul class="list">
        <?php foreach ($items as $it): ?>
          <li>
            <span><?= h($it['product_title']) ?> × <?= (int)$it['qty'] ?></span>
            <span><?= number_format((float)$it['product_price'] * (int)$it['qty'], 2) ?> FBu</span>
          </li>
        <?php endforeach; ?>
      </ul>
      <div class="row"><strong>Total</strong><strong><?= number_format($total, 2) ?> FBu</strong></div>
      <div style="margin-top:12px;display:flex;gap:8px">
        <a class="btn" href="cart.php">Back</a>
        <button class="btn primary" onclick="Checkout.process()">Simulate Payment</button>
      </div>
    <?php endif; ?>
  </div>

  <script src="../js/checkout.js"></script>
</body>
</html>
