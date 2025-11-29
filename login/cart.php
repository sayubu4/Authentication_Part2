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
    :root {
        --primary-color: #F97316;
        --primary-hover: #EA580C;
        --primary-dark: #9A3412;
        --light-bg: #FFF7ED;
        --card-bg: rgba(255, 255, 255, 0.95);
        --text-color: #333;
        --text-light: #6c757d;
    }
    
    body {
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        padding: 24px;
        background: var(--light-bg);
        color: var(--text-color);
        line-height: 1.6;
    }
    
    h1 {
        color: var(--primary-dark);
        margin-bottom: 24px;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 6px 16px rgba(0,0,0,0.06);
        margin-bottom: 24px;
    }
    
    th, td {
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
        text-align: left;
    }
    
    th {
        background: var(--primary-color);
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85em;
        letter-spacing: 0.5px;
    }
    
    .actions button {
        margin-right: 8px;
    }
    
    .total {
        margin: 24px 0;
        font-size: 1.5em;
        font-weight: 700;
        color: var(--primary-color);
        text-align: right;
    }
    
    .toolbar {
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    
    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: var(--text-color);
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn.primary {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-hover);
    }
    
    .btn.primary:hover {
        background: var(--primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
    }
    
    .btn.secondary {
        background: white;
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
    }
    
    .btn.secondary:hover {
        background: #fff7ed;
    }
  </style>
</head>
<body>
  <div class="toolbar">
    <a href="all_product.php" class="btn secondary">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
      </svg>
      Continue Shopping
    </a>
    <button class="btn" id="emptyBtn">Empty Wishlist</button>
    <a href="checkout.php" class="btn primary">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
        <line x1="3" y1="6" x2="21" y2="6"></line>
        <path d="M16 10a4 4 0 0 1-8 0"></path>
      </svg>
      Proceed to Checkout
    </a>
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
        <tr><td colspan="5" style="text-align:center;padding:40px 20px;color:var(--text-light)"><div style="max-width:400px;margin:0 auto;padding:20px;"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:16px;color:var(--primary-color)"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg><h3 style="color:var(--primary-dark);margin:12px 0 8px">Your wishlist is empty</h3><p style="color:var(--text-light);margin:0">Browse our products and add some items to your wishlist</p></div></td></tr>
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
