<?php
require_once(__DIR__ . '/../settings/core.php');
require_once(__DIR__ . '/../settings/paystack_config.php');

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$customer_id = $_SESSION['customer_id'];
$subscriptionAmount = 10; // subscription price in the same currency used by your Paystack config (e.g., 10 GHS)
$currencySymbol = get_currency_symbol('GHS');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribe - Premium Food Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto;background:#e5e7eb;color:#111827;padding:32px 16px;display:flex;justify-content:center;}
        .container{width:100%;max-width:600px;background:#f9fafb;border-radius:18px;padding:24px 22px 26px;box-shadow:0 14px 35px rgba(15,23,42,0.15);}        
        h1{font-size:1.7rem;margin:0 0 10px;font-weight:700;display:flex;align-items:center;gap:10px;color:#111827;}
        h1 i{color:#22c55e;}
        .subtitle{color:#6b7280;font-size:0.95rem;margin-bottom:18px;}
        .card{background:#ffffff;border-radius:14px;padding:18px 16px;border:1px solid #e5e7eb;box-shadow:0 8px 20px rgba(15,23,42,0.05);}
        .feature-list{margin:12px 0 16px;padding-left:18px;color:#4b5563;font-size:0.95rem;}
        .feature-list li{margin-bottom:4px;}
        .price-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;}
        .price-label{font-size:0.9rem;color:#6b7280;}
        .price-value{font-size:1.2rem;font-weight:700;color:#111827;}
        .input-group{margin-bottom:14px;}
        .input-label{font-size:0.85rem;color:#6b7280;margin-bottom:4px;}
        .input-field{width:100%;padding:9px 11px;border-radius:10px;border:1px solid #d1d5db;font-size:0.95rem;}
        .btn{width:100%;margin-top:10px;padding:11px 14px;border-radius:999px;border:none;background:#111827;color:#f9fafb;font-size:0.98rem;font-weight:600;display:inline-flex;align-items:center;justify-content:center;gap:8px;cursor:pointer;}
        .btn:disabled{opacity:0.6;cursor:not-allowed;}
        .btn-secondary{margin-top:10px;background:#e5e7eb;color:#111827;}
        .note{margin-top:10px;font-size:0.8rem;color:#9ca3af;}
        .status{margin-top:10px;font-size:0.9rem;}
        .status.error{color:#b91c1c;}
        .status.success{color:#15803d;}
    </style>
</head>
<body>
<div class="container">
    <h1><i class="fa fa-crown"></i><span>Premium Access</span></h1>
    <p class="subtitle">Subscribe once to unlock exact locations, opening hours and full details for all premium food spots.</p>

    <div class="card">
        <div class="price-row">
            <div>
                <div class="price-label">Subscription</div>
                <div class="price-value"><?php echo $currencySymbol . ' ' . number_format($subscriptionAmount, 2); ?> (one-time)</div>
            </div>
            <i class="fa fa-shield-halved" style="color:#22c55e;font-size:1.3rem;"></i>
        </div>

        <ul class="feature-list">
            <li>See exact restaurant / food spot locations</li>
            <li>View working hours and contact details (where available)</li>
            <li>Access full descriptions for all premium spots</li>
        </ul>

        <div class="input-group">
            <div class="input-label">Email for Paystack payment</div>
            <input id="emailInput" type="email" class="input-field" placeholder="you@example.com" />
        </div>

        <button id="subscribeBtn" class="btn">
            <i class="fa fa-credit-card"></i>
            <span>Subscribe with Paystack</span>
        </button>

        <button class="btn btn-secondary" onclick="window.history.back();return false;">Back</button>

        <div id="status" class="status"></div>
        <p class="note">Subscription is processed securely by Paystack. Once payment is successful, premium content will unlock automatically.</p>
    </div>
</div>

<script>
const SUBSCRIPTION_AMOUNT = <?php echo json_encode($subscriptionAmount); ?>;
const INIT_URL = '../actions/paystack_init_transaction.php';

const emailInput = document.getElementById('emailInput');
const subscribeBtn = document.getElementById('subscribeBtn');
const statusEl = document.getElementById('status');

subscribeBtn.addEventListener('click', async () => {
    const email = (emailInput.value || '').trim();
    if (!email) {
        statusEl.textContent = 'Please enter your email to continue.';
        statusEl.className = 'status error';
        return;
    }

    subscribeBtn.disabled = true;
    statusEl.textContent = 'Initializing payment...';
    statusEl.className = 'status';

    try {
        const res = await fetch(INIT_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'same-origin',
            body: JSON.stringify({ amount: SUBSCRIPTION_AMOUNT, email })
        });
        const data = await res.json();
        if (data.status === 'success' && data.authorization_url) {
            statusEl.textContent = 'Redirecting to Paystack...';
            statusEl.className = 'status success';
            window.location.href = data.authorization_url;
        } else {
            subscribeBtn.disabled = false;
            statusEl.textContent = data.message || 'Failed to start payment.';
            statusEl.className = 'status error';
        }
    } catch (e) {
        subscribeBtn.disabled = false;
        statusEl.textContent = 'Network error while starting payment.';
        statusEl.className = 'status error';
    }
});
</script>
</body>
</html>
