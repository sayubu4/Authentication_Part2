<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Home</title>
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
		.welcome-message {
			background: linear-gradient(135deg, #D19C97, #b77a7a);
			color: white;
			padding: 20px;
			border-radius: 10px;
			margin-bottom: 20px;
		}
		.user-info {
			background-color: #f8f9fa;
			padding: 15px;
			border-radius: 8px;
			border-left: 4px solid #D19C97;
		}
	</style>
</head>
<body>

	<div class="menu-tray">
		<?php if (isset($_SESSION['customer_id'])): ?>
			<span class="me-2">Welcome, <?php echo htmlspecialchars($_SESSION['customer_name']); ?>!</span>
			<?php if ($_SESSION['user_role'] == 2): ?>
				<a href="Admin/category.php" class="btn btn-sm btn-outline-primary me-2">
					<i class="fa fa-tags"></i> Category
				</a>
				<a href="Admin/brand.php" class="btn btn-sm btn-outline-primary me-2">
					<i class="fa fa-tags"></i> Brand
				</a>
				<a href="Admin/product.php" class="btn btn-sm btn-outline-primary me-2">
					<i class="fa fa-tags"></i> Product
				</a>
				<a href="login/all_product.php" class="btn btn-sm btn-outline-primary me-2">
					<i class="fa fa-tags"></i> All Product
				</a>
			<?php endif; ?>
			<a href="login/logout.php" class="btn btn-sm btn-outline-danger">
				<i class="fa fa-sign-out-alt"></i> Logout
			</a>
		<?php else: ?>
			<span class="me-2">Menu:</span>
			<a href="login/register.php" class="btn btn-sm btn-outline-primary">
				<i class="fa fa-user-plus"></i> Register
			</a>
			<a href="login/login.php" class="btn btn-sm btn-outline-secondary">
				<i class="fa fa-sign-in-alt"></i> Login
			</a>
		<?php endif; ?>
	</div>

	<div class="container" style="padding-top:120px;">
		<div class="text-center">
			<?php if (isset($_SESSION['customer_id'])): ?>
				<div class="welcome-message">
					<h1><i class="fa fa-home"></i> Welcome Home!</h1>
					<p>You are successfully logged in.</p>
				</div>
				
				<div class="row justify-content-center">
					<div class="col-md-8">
						<div class="user-info">
							<h4><i class="fa fa-user"></i> Your Profile Information</h4>
							<div class="row">
								<div class="col-md-6">
									<p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['customer_name']); ?></p>
									<p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['customer_email']); ?></p>
								</div>
								<div class="col-md-6">
									<p><strong>Location:</strong> <?php echo htmlspecialchars($_SESSION['customer_city'] . ', ' . $_SESSION['customer_country']); ?></p>
									<p><strong>Role:</strong> 
										<?php 
										if ($_SESSION['user_role'] == 2) {
											echo '<span class="badge bg-danger">Admin</span>';
										} else {
											echo '<span class="badge bg-primary">Customer</span>';
										}
										?>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php else: ?>
				<h1>Welcome</h1>
				<p class="text-muted">Use the menu in the top-right to Register or Login.</p>
			<?php endif; ?>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
