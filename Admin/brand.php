<?php
// Start session with secure settings
if (session_status() === PHP_SESSION_NONE) {
    session_name('taste_of_africa');
    session_start();
}

// Include core functions
require_once '../settings/core.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['customer_id'], $_SESSION['user_role']) || $_SESSION['user_role'] != 2) {
    // Store the current URL for redirecting back after login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: ../login/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brand Management - Taste of Africa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .btn-custom {
            background-color: #F97316;
            border-color: #F97316;
            color: #fff;
        }

        .btn-custom:hover {
            background-color: #EA580C;
            border-color: #EA580C;
            color: #fff;
        }

        .btn-outline-custom {
            border-color: #F97316;
            color: #F97316;
        }

        .btn-outline-custom:hover {
            background-color: #F97316;
            border-color: #F97316;
            color: #fff;
        }

        .highlight {
            color: #F97316;
        }

        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #F97316;
            color: #fff;
        }

        .loading {
            display: none;
        }

        .loading.show {
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="highlight">
                        <i class="fas fa-star me-2"></i>Brand Management
                    </h2>
                    <div>
                        <span class="text-muted">Welcome, <?php echo htmlspecialchars($_SESSION['customer_name']); ?>!</span>
                        <a href="../login/logout.php" class="btn btn-outline-danger btn-sm ms-2">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    </div>
                </div>

                <!-- Alert Messages -->
                <div id="alert-container"></div>

                <!-- Add Brand Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-plus me-2"></i>Add New Brand
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="add-brand-form">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="brand_name" class="form-label">Brand Name</label>
                                        <input type="text" class="form-control" id="brand_name" name="brand_name" 
                                               placeholder="e.g., Nike, Adidas, Puma" required>
                                        <div class="form-text">Enter a unique brand name</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="add_category_id" class="form-label">Category</label>
                                        <select class="form-select" id="add_category_id" name="category_id" required>
                                            <option value="">Select a category...</option>
                                        </select>
                                        <div class="form-text">Choose the category this brand belongs to</div>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-custom w-100" id="add-brand-btn">
                                        <span class="btn-text">
                                            <i class="fas fa-plus me-1"></i>Add Brand
                                        </span>
                                        <span class="loading">
                                            <i class="fas fa-spinner fa-spin me-1"></i>Adding...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Brands List -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Your Brands
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="40%">Brand Name</th>
                                        <th width="25%">Category</th>
                                        <th width="30%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="brands-table-body">
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="loading">
                                                <i class="fas fa-spinner fa-spin me-2"></i>Loading brands...
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Brand Modal -->
    <div class="modal fade" id="editBrandModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>Edit Brand
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-brand-form">
                        <input type="hidden" id="edit_brand_id" name="brand_id">
                        <div class="mb-3">
                            <label for="edit_brand_name" class="form-label">Brand Name</label>
                            <input type="text" class="form-control" id="edit_brand_name" name="brand_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_category_id" class="form-label">Category</label>
                            <select class="form-select" id="edit_category_id" name="category_id" required>
                                <option value="">Select a category...</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-custom" id="update-brand-btn">
                        <span class="btn-text">
                            <i class="fas fa-save me-1"></i>Update Brand
                        </span>
                        <span class="loading">
                            <i class="fas fa-spinner fa-spin me-1"></i>Updating...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteBrandModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this brand?</p>
                    <p class="text-muted">This action cannot be undone.</p>
                    <input type="hidden" id="delete_brand_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete-btn">
                        <span class="btn-text">
                            <i class="fas fa-trash me-1"></i>Delete Brand
                        </span>
                        <span class="loading">
                            <i class="fas fa-spinner fa-spin me-1"></i>Deleting...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/brand.js"></script>
</body>

</html>
