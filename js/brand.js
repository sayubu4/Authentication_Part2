$(document).ready(function() {
    // Load brands and categories on page load
    loadBrands();
    loadCategories();

    // Add brand form submission
    $('#add-brand-form').on('submit', function(e) {
        e.preventDefault();
        addBrand();
    });

    // Update brand button click
    $('#update-brand-btn').on('click', function() {
        updateBrand();
    });

    // Confirm delete button click
    $('#confirm-delete-btn').on('click', function() {
        deleteBrand();
    });

    // Load brands function
    function loadBrands() {
        $.ajax({
            url: '../actions/fetch_brand_action.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    displayBrands(response.brands);
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function() {
                showAlert('error', 'Failed to load brands. Please try again.');
            }
        });
    }

    // Load categories for dropdown
    function loadCategories() {
        $.ajax({
            url: '../actions/fetch_category_action.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    populateCategoryDropdown(response.categories);
                } else {
                    showAlert('error', 'Failed to load categories: ' + response.message);
                }
            },
            error: function() {
                showAlert('error', 'Failed to load categories. Please try again.');
            }
        });
    }

    // Populate category dropdown
    function populateCategoryDropdown(categories) {
        const addDropdown = $('#add_category_id');
        const editDropdown = $('#edit_category_id');
        
        addDropdown.empty();
        editDropdown.empty();
        
        addDropdown.append('<option value="">Select a category...</option>');
        editDropdown.append('<option value="">Select a category...</option>');
        
        categories.forEach(function(category) {
            addDropdown.append(`<option value="${category.cat_id}">${escapeHtml(category.cat_name)}</option>`);
            editDropdown.append(`<option value="${category.cat_id}">${escapeHtml(category.cat_name)}</option>`);
        });
    }

    // Display brands in table
    function displayBrands(brands) {
        const tbody = $('#brands-table-body');
        tbody.empty();

        if (brands.length === 0) {
            tbody.html(`
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">
                        <i class="fas fa-inbox me-2"></i>No brands found. Add your first brand above!
                    </td>
                </tr>
            `);
            return;
        }

        brands.forEach((brand, index) => {
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        <span class="fw-medium">${escapeHtml(brand.brand_name)}</span>
                    </td>
                    <td>
                        <span class="badge bg-secondary">${escapeHtml(brand.cat_name || 'No Category')}</span>
                    </td>
                    <td>
                        <button class="btn btn-outline-custom btn-sm me-2" onclick="editBrand(${brand.brand_id}, '${escapeHtml(brand.brand_name)}', ${brand.category_id || 0})">
                            <i class="fas fa-edit me-1"></i>Edit
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="confirmDelete(${brand.brand_id}, '${escapeHtml(brand.brand_name)}')">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Add brand function
    function addBrand() {
        const brandName = $('#brand_name').val().trim();
        const categoryId = $('#add_category_id').val();
        
        if (!brandName) {
            showAlert('error', 'Please enter a brand name');
            return;
        }

        if (!categoryId) {
            showAlert('error', 'Please select a category');
            return;
        }

        setLoading('#add-brand-btn', true);

        $.ajax({
            url: '../actions/add_brand_action.php',
            type: 'POST',
            data: {
                brand_name: brandName,
                category_id: categoryId
            },
            dataType: 'json',
            success: function(response) {
                setLoading('#add-brand-btn', false);
                
                if (response.status === 'success') {
                    showAlert('success', response.message);
                    $('#brand_name').val('');
                    $('#add_category_id').val('');
                    loadBrands();
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function() {
                setLoading('#add-brand-btn', false);
                showAlert('error', 'Failed to add brand. Please try again.');
            }
        });
    }

    // Edit brand function (opens modal)
    window.editBrand = function(brandId, brandName, categoryId) {
        $('#edit_brand_id').val(brandId);
        $('#edit_brand_name').val(brandName);
        $('#edit_category_id').val(categoryId);
        $('#editBrandModal').modal('show');
    };

    // Update brand function
    function updateBrand() {
        const brandId = $('#edit_brand_id').val();
        const brandName = $('#edit_brand_name').val().trim();
        const categoryId = $('#edit_category_id').val();
        
        if (!brandName) {
            showAlert('error', 'Please enter a brand name');
            return;
        }

        if (!categoryId) {
            showAlert('error', 'Please select a category');
            return;
        }

        setLoading('#update-brand-btn', true);

        $.ajax({
            url: '../actions/update_brand_action.php',
            type: 'POST',
            data: {
                brand_id: brandId,
                brand_name: brandName,
                category_id: categoryId
            },
            dataType: 'json',
            success: function(response) {
                setLoading('#update-brand-btn', false);
                
                if (response.status === 'success') {
                    showAlert('success', response.message);
                    $('#editBrandModal').modal('hide');
                    loadBrands();
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function() {
                setLoading('#update-brand-btn', false);
                showAlert('error', 'Failed to update brand. Please try again.');
            }
        });
    }

    // Confirm delete function (opens modal)
    window.confirmDelete = function(brandId, brandName) {
        $('#delete_brand_id').val(brandId);
        $('#deleteBrandModal .modal-body p').first().text(`Are you sure you want to delete the brand "${brandName}"?`);
        $('#deleteBrandModal').modal('show');
    };

    // Delete brand function
    function deleteBrand() {
        const brandId = $('#delete_brand_id').val();

        setLoading('#confirm-delete-btn', true);

        $.ajax({
            url: '../actions/delete_brand_action.php',
            type: 'POST',
            data: {
                brand_id: brandId
            },
            dataType: 'json',
            success: function(response) {
                setLoading('#confirm-delete-btn', false);
                
                if (response.status === 'success') {
                    showAlert('success', response.message);
                    $('#deleteBrandModal').modal('hide');
                    loadBrands();
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function() {
                setLoading('#confirm-delete-btn', false);
                showAlert('error', 'Failed to delete brand. Please try again.');
            }
        });
    }

    // Set loading state for buttons
    function setLoading(selector, isLoading) {
        const $btn = $(selector);
        const $btnText = $btn.find('.btn-text');
        const $loading = $btn.find('.loading');
        
        if (isLoading) {
            $btn.prop('disabled', true);
            $btnText.hide();
            $loading.addClass('show');
        } else {
            $btn.prop('disabled', false);
            $btnText.show();
            $loading.removeClass('show');
        }
    }

    // Show alert message
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas ${icon} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('#alert-container').html(alertHtml);
        
        // Auto-hide success messages after 3 seconds
        if (type === 'success') {
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 3000);
        }
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
});
