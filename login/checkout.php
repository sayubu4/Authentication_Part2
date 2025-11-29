<?php
require_once '../settings/core.php';

// Check if user is logged in using existing isLoggedIn() function
if (!isLoggedIn()) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: ../login/login.php');
    exit();
}

// Check if cart is not empty
require_once '../controllers/cart_controller.php';
$customer_id = $_SESSION['customer_id'];
$cart_items = get_user_cart_ctr($customer_id);

if (!$cart_items || count($cart_items) == 0) {
    header('Location: cart.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #FFF7ED;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        .success-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .success-icon {
            font-size: 72px;
            color: #28a745;
            margin-bottom: 20px;
            line-height: 1;
        }
        .btn-primary {
            background-color: #F97316;
            border: none;
            padding: 10px 25px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: #EA580C;
        }
        h2 {
            color: #1f2937;
            margin-bottom: 15px;
        }
        p {
            color: #4b5563;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        
        .btn-outline-secondary {
            color: #6b7280;
            border-color: #d1d5db;
        }
        .btn-outline-secondary:hover {
            background-color: #f3f4f6;
            color: #1f2937;
        }
        
        .btn { 
            padding: 12px 30px; 
            border-radius: 8px; 
            font-size: 16px; 
            font-weight: 600; 
            cursor: pointer; 
            transition: all 0.3s ease; 
            text-decoration: none; 
            display: inline-block;
        }
        .btn-primary { background: var(--primary-color); color: white; box-shadow: 0 8px 25px rgba(249, 115, 22, 0.3); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 35px rgba(249, 115, 22, 0.4); background: var(--primary-hover); }
        .btn-secondary { background: white; color: #374151; border: 2px solid #e5e7eb; }
        
        /* Modal Styles */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 1000; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease; }
        .modal-content { background: white; max-width: 500px; width: 90%; padding: 40px; border-radius: 20px; position: relative; transform: scale(0.9); transition: transform 0.3s ease; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .modal-content::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 6px; background: linear-gradient(90deg, var(--primary-color) 0%, var(--primary-hover) 100%); border-radius: 20px 20px 0 0; }
        .modal-close { position: absolute; top: 15px; right: 20px; font-size: 28px; cursor: pointer; color: #6b7280; }
        .modal-close:hover { color: #dc2626; }
        .modal-title { font-family: 'Cormorant Garamond', serif; font-size: 28px; color: #1a1a1a; margin-bottom: 20px; text-align: center; }
        .modal-buttons { display: flex; gap: 12px; margin-top: 30px; }
        .modal-buttons button { flex: 1; }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-container">
            <div class="success-icon">✓</div>
            <h2>Experience Complete!!</h2>
            <p class="lead">Thank you for visiting us. We hope you had a wonderful time and enjoyed the delicious food.</p>
            
            <div class="mt-4">
                <a href="../index.php" class="btn btn-primary">Back to Home</a>
               
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
                        <span id="successDate"></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 14px; color: #065f46;">
            
                        <span id="successItems"></span>
                    </div>
                </div>
            </div>
            
            <div class="modal-buttons">
                <a href="all_product.php" class="btn btn-primary">Continue Shopping</a>
                
        </div>
    </div>

    <script src="../js/checkout.js"></script>
</body>
</html>