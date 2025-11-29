$(document).ready(function() {
    // Load categories on page load
    loadCategories();

    // Add category form submission
    $('#add-category-form').on('submit', function(e) {
        e.preventDefault();
        addCategory();
    });

    // Update category button click
    $('#update-category-btn').on('click', function() {
        updateCategory();
    });

    // Confirm delete button click
    $('#confirm-delete-btn').on('click', function() {
        deleteCategory();
    });

    // Load categories function
    function loadCategories() {
        $.ajax({
            url: '../actions/fetch_category_action.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    displayCategories(response.categories);
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function() {
                showAlert('error', 'Failed to load categories. Please try again.');
            }
        });
    }

    // Display categories in table
    function displayCategories(categories) {
        const tbody = $('#categories-table-body');
        tbody.empty();

        if (categories.length === 0) {
            tbody.html(`
                <tr>
                    <td colspan="3" class="text-center py-4 text-muted">
                        <i class="fas fa-inbox me-2"></i>No categories found. Add your first category above!
                    </td>
                </tr>
            `);
            return;
        }

        categories.forEach((category, index) => {
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        <span class="fw-medium">${escapeHtml(category.cat_name)}</span>
                    </td>
                    <td>
                        <button class="btn btn-outline-custom btn-sm me-2" onclick="editCategory(${category.cat_id}, '${escapeHtml(category.cat_name)}')">
                            <i class="fas fa-edit me-1"></i>Edit
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="confirmDelete(${category.cat_id}, '${escapeHtml(category.cat_name)}')">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Add category function
    function addCategory() {
        const categoryName = $('#category_name').val().trim();
        
        if (!categoryName) {
            showAlert('error', 'Please enter a category name');
            return;
        }

        setLoading('#add-category-btn', true);

        $.ajax({
            url: '../actions/add_category_action.php',
            type: 'POST',
            data: {
                category_name: categoryName
            },
            dataType: 'json',
            success: function(response) {
                setLoading('#add-category-btn', false);
                
                if (response.status === 'success') {
                    showAlert('success', response.message);
                    $('#category_name').val('');
                    loadCategories();
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function() {
                setLoading('#add-category-btn', false);
                showAlert('error', 'Failed to add category. Please try again.');
            }
        });
    }

    // Edit category function (opens modal)
    window.editCategory = function(categoryId, categoryName) {
        $('#edit_category_id').val(categoryId);
        $('#edit_category_name').val(categoryName);
        $('#editCategoryModal').modal('show');
    };

    // Update category function
    function updateCategory() {
        const categoryId = $('#edit_category_id').val();
        const categoryName = $('#edit_category_name').val().trim();
        
        if (!categoryName) {
            showAlert('error', 'Please enter a category name');
            return;
        }

        setLoading('#update-category-btn', true);

        $.ajax({
            url: '../actions/update_category_action.php',
            type: 'POST',
            data: {
                category_id: categoryId,
                category_name: categoryName
            },
            dataType: 'json',
            success: function(response) {
                setLoading('#update-category-btn', false);
                
                if (response.status === 'success') {
                    showAlert('success', response.message);
                    $('#editCategoryModal').modal('hide');
                    loadCategories();
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function() {
                setLoading('#update-category-btn', false);
                showAlert('error', 'Failed to update category. Please try again.');
            }
        });
    }

    // Confirm delete function (opens modal)
    window.confirmDelete = function(categoryId, categoryName) {
        $('#delete_category_id').val(categoryId);
        $('#deleteCategoryModal .modal-body p').first().text(`Are you sure you want to delete the category "${categoryName}"?`);
        $('#deleteCategoryModal').modal('show');
    };

    // Delete category function
    function deleteCategory() {
        const categoryId = $('#delete_category_id').val();

        setLoading('#confirm-delete-btn', true);

        $.ajax({
            url: '../actions/delete_category_action.php',
            type: 'POST',
            data: {
                category_id: categoryId
            },
            dataType: 'json',
            success: function(response) {
                setLoading('#confirm-delete-btn', false);
                
                if (response.status === 'success') {
                    showAlert('success', response.message);
                    $('#deleteCategoryModal').modal('hide');
                    loadCategories();
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function() {
                setLoading('#confirm-delete-btn', false);
                showAlert('error', 'Failed to delete category. Please try again.');
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