$(document).ready(function() {
    let isEditMode = false;

    // Form submission
    $('#productForm').on('submit', function(e) {
        e.preventDefault();

        // Validation
        if (!validateForm()) {
            return;
        }

        const formData = new FormData(this);
        const productId = $('#productId').val();
        
        // Determine which action to call
        const actionUrl = productId ? '../actions/update_product_action.php' : '../actions/add_product_action.php';

        // Show loading
        Swal.fire({
            title: 'Processing...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                Swal.close();
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: true
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred: ' + error
                });
            }
        });
    });

    // Edit button click
    $(document).on('click', '.editBtn', function() {
        const productId = $(this).data('id');
        
        // Show loading
        Swal.fire({
            title: 'Loading...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '../actions/get_product_action.php',
            type: 'GET',
            data: { product_id: productId },
            dataType: 'json',
            success: function(response) {
                Swal.close();
                if (response.success) {
                    const product = response.product;
                    
                    // Fill form with product data
                    $('#productId').val(product.product_id);
                    $('#productCategory').val(product.product_cat);
                    $('#productBrand').val(product.product_brand);
                    $('#productTitle').val(product.product_title);
                    $('#productPrice').val(product.product_price);
                    $('#productDesc').val(product.product_desc);
                    $('#productKeywords').val(product.product_keywords);
                    
                    // Show current image
                    if (product.product_image) {
                        $('#currentImagePreview').html(`
                            <img src="../${product.product_image}" class="img-thumbnail" style="max-width: 150px;">
                            <p class="text-muted small mt-1">Current image</p>
                        `);
                    }
                    
                    // Update form title and button
                    $('#formTitle').html('<i class="fa fa-edit"></i> Edit Product');
                    $('#submitBtn').html('<i class="fa fa-save"></i> Update Product');
                    
                    // Remove required from image input
                    $('#productImage').removeAttr('required');
                    
                    isEditMode = true;
                    
                    // Scroll to form
                    $('html, body').animate({
                        scrollTop: $('#productForm').offset().top - 100
                    }, 500);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load product data'
                });
            }
        });
    });

    // Delete button click
    $(document).on('click', '.deleteBtn', function() {
        const productId = $(this).data('id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../actions/delete_product_action.php',
                    type: 'POST',
                    data: { product_id: productId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to delete product'
                        });
                    }
                });
            }
        });
    });

    // Reset button
    $('#resetBtn').on('click', function() {
        resetForm();
    });

    // Reset form function
    function resetForm() {
        $('#productForm')[0].reset();
        $('#productId').val('');
        $('#currentImagePreview').html('');
        $('#formTitle').html('<i class="fa fa-plus-circle"></i> Add New Product');
        $('#submitBtn').html('<i class="fa fa-save"></i> Save Product');
        $('#productImage').attr('required', 'required');
        isEditMode = false;
    }

    // Form validation
    function validateForm() {
        const category = $('#productCategory').val();
        const brand = $('#productBrand').val();
        const title = $('#productTitle').val().trim();
        const price = $('#productPrice').val();
        const desc = $('#productDesc').val().trim();
        const keywords = $('#productKeywords').val().trim();
        const image = $('#productImage')[0].files[0];

        // Check required fields
        if (!category) {
            Swal.fire('Error', 'Please select a category', 'error');
            return false;
        }

        if (!brand) {
            Swal.fire('Error', 'Please select a brand', 'error');
            return false;
        }

        if (!title) {
            Swal.fire('Error', 'Please enter product title', 'error');
            return false;
        }

        if (!price || parseFloat(price) <= 0) {
            Swal.fire('Error', 'Please enter a valid price', 'error');
            return false;
        }

        if (!desc) {
            Swal.fire('Error', 'Please enter product description', 'error');
            return false;
        }

        if (!keywords) {
            Swal.fire('Error', 'Please enter product keywords', 'error');
            return false;
        }

        // Check image if in add mode
        if (!isEditMode && !image) {
            Swal.fire('Error', 'Please select a product image', 'error');
            return false;
        }

        // Validate image type if provided
        if (image) {
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validTypes.includes(image.type)) {
                Swal.fire('Error', 'Please select a valid image file (JPEG, PNG, or GIF)', 'error');
                return false;
            }

            // Check file size (max 5MB)
            if (image.size > 5 * 1024 * 1024) {
                Swal.fire('Error', 'Image size should not exceed 5MB', 'error');
                return false;
            }
        }

        return true;
    }
});