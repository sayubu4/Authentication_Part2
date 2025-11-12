<?php
require_once(__DIR__ . '/../settings/core.php');
require_once(__DIR__ . '/../controllers/cart_controller.php');

if (!isLoggedIn()) { header('Location: login.php'); exit; }
$customer_id = $_SESSION['customer_id'];
$items = get_user_cart_ctr($customer_id) ?: [];

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
$total = 0;
foreach ($items as $it) { $total += ((float)$it['product_price'] * (int)$it['qty']); }
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Your Wishlist</title>
  <style>
    body{font-family:system-ui,Segoe UI,Arial;padding:24px;background:#f8fafc}
    table{width:100%;border-collapse:collapse;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 6px 16px rgba(0,0,0,.06)}
    th,td{padding:12px;border-bottom:1px solid #eef2f7;text-align:left}
    th{background:#f3f4f6}
    .actions button{margin-right:6px}
    .total{margin-top:12px;font-weight:700}
    .toolbar{margin-bottom:12px;display:flex;gap:8px}
    .btn{padding:8px 12px;border-radius:8px;border:1px solid #e5e7eb;background:#fff;cursor:pointer}
    .btn.primary{background:#111827;color:#fff;border-color:#111827}
  </style>
</head>
<body>
  <div class="toolbar">
    <a class="btn" href="all_product.php">Continue Browsing</a>
    <button class="btn" id="emptyBtn">Empty Wishlist</button>
    <a class="btn primary" href="checkout.php">Proceed to Checkout</a>
  </div>

  <table>
    <thead>
      <tr>
        <th>Item</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Subtotal</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="cartBody">
      <?php if (count($items)===0): ?>
        <tr><td colspan="5" style="text-align:center;color:#6b7280">Your wishlist is empty.</td></tr>
      <?php else: ?>
        <?php foreach ($items as $it): $sub = (float)$it['product_price'] * (int)$it['qty']; ?>
          <tr data-id="<?= (int)$it['product_id'] ?>">
            <td>
              <div style="display:flex;align-items:center;gap:10px;">
                <img src="../<?= h($it['product_image']) ?>" alt="" style="width:80px;height:60px;object-fit:cover;border-radius:8px;background:#f3f4f6" onerror="this.style.display='none'" />
                <div><?= h($it['product_title']) ?></div>
              </div>
            </td>
            <td><?= number_format((float)$it['product_price'], 2) ?></td>
            <td>
              <input type="number" min="1" value="<?= (int)$it['qty'] ?>" style="width:70px" onchange="updateQty(<?= (int)$it['product_id'] ?>, this.value)" />
            </td>
            <td><?= number_format($sub, 2) ?></td>
            <td class="actions">
              <button onclick="removeItem(<?= (int)$it['product_id'] ?>)" class="btn">Remove</button>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
  <div class="total">Total: <?= number_format($total, 2) ?> FBu</div>

  <script src="../js/cart.js"></script>
  <script>
    async function updateQty(pid, qty){
      const r = await Cart.update(pid, qty);
      if(r.status==='success') location.reload(); else alert(r.message||'Failed');
    }
    async function removeItem(pid){
      const r = await Cart.remove(pid);
      if(r.status==='success') location.reload(); else alert(r.message||'Failed');
    }
    document.getElementById('emptyBtn').addEventListener('click', async ()=>{
      if(!confirm('Empty wishlist?')) return;
      const r = await Cart.empty();
      if(r.status==='success') location.reload(); else alert(r.message||'Failed');
    });
  </script>
</body>
</html>
