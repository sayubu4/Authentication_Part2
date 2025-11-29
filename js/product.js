$(document).ready(function() {
    let isEditMode = false;

    // Form submission
    $('#productForm').on('submit', function(e) {
        e.preventDefault();

        // Validation
        if (!validateForm()) {
            return false;
        }

        const formData = new FormData(this);
        const productId = $('#productId').val();
        const imagePath = $('#imagePath').val();
        const hasNewImage = $('#productImage')[0].files.length > 0;
        
        // Remove the file input from formData since we're using image_path
        formData.delete('product_image');
        
        // Ensure image_path is set for new products
        if (!productId && !imagePath) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please upload a product image first'
            });
            return false;
        }
        
        // Set image_path appropriately
        if (imagePath) {
            // If we have an image path (either new upload or existing), use it
            formData.set('image_path', imagePath);
        } else if (productId && !hasNewImage) {
            // If editing and no new image selected, we need to get the existing image path
            // This should already be set from the edit handler, but if not, we'll need to fetch it
            // For now, we'll let the backend handle it (it won't update the image if empty)
            formData.set('image_path', '');
        }
        
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
                if (response && response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Product saved successfully!',
                        showConfirmButton: true
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'Failed to save product'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                console.error('Error:', xhr.responseText);
                let errorMsg = 'An error occurred while saving the product';
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        errorMsg = response.message;
                    }
                } catch (e) {
                    // Use default error message
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMsg
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
                    if (product.region_id) {
                        $('#productRegion').val(product.region_id);
                    }
                    $('#productTitle').val(product.product_title);
                    $('#productPrice').val(product.product_price);
                    $('#productDesc').val(product.product_desc);
                    $('#productKeywords').val(product.product_keywords);
                    if (product.opening_hours) {
                        $('#openingHours').val(product.opening_hours);
                    } else {
                        $('#openingHours').val('');
                    }
                    if (product.contact_phone) {
                        $('#contactPhone').val(product.contact_phone);
                    } else {
                        $('#contactPhone').val('');
                    }
                    if (product.exact_location) {
                        $('#exactLocation').val(product.exact_location);
                    } else {
                        $('#exactLocation').val('');
                    }
                    
                    // Show current image and set image path
                    if (product.product_image) {
                        $('#imagePath').val(product.product_image);
                        $('#currentImagePreview').html(`
                            <img src="../${product.product_image}" class="img-thumbnail" style="max-width: 150px;">
                            <p class="text-muted small mt-1">Current image</p>
                        `);
                    } else {
                        $('#imagePath').val('');
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
        $('#imagePath').val('');
        isEditMode = false;
    }

    // Form validation
    function validateForm() {
        const category = $('#productCategory').val();
        const brand = $('#productBrand').val();
        const region = $('#productRegion').val();
        const title = $('#productTitle').val().trim();
        const price = $('#productPrice').val();
        const desc = $('#productDesc').val().trim();
        const keywords = $('#productKeywords').val().trim();
        const imagePath = $('#imagePath').val();
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

        if (!region) {
            Swal.fire('Error', 'Please select a region', 'error');
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

        // Check image if in add mode - must have image_path set
        if (!isEditMode && !imagePath) {
            Swal.fire('Error', 'Please select and upload a product image', 'error');
            return false;
        }

        // Validate image type if a new file is selected
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

    // Auto-upload image on selection
    $('#productImage').on('change', function(){
        const file = this.files && this.files[0];
        if(!file){ return; }

        // build form data for upload endpoint
        const fd = new FormData();
        fd.append('product_image', file);
        // include product_id if editing so it stores under p{product_id}
        const pid = $('#productId').val();
        if(pid){ fd.append('product_id', pid); }

        // show lightweight loading
        $('#currentImagePreview').html('<small class="text-muted">Uploading image…</small>');

        $.ajax({
            url: '../actions/upload_product_image_action.php',
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(resp){
                if(resp && resp.success && resp.image_path){
                    // set hidden field for add/update actions
                    $('#imagePath').val(resp.image_path);
                    // preview
                    const src = '../' + resp.image_path;
                    $('#currentImagePreview').html(`<img src="${src}" class="img-thumbnail" style="max-width:150px;">`);
                } else {
                    $('#currentImagePreview').html('');
                    Swal.fire('Error', resp && resp.message ? resp.message : 'Image upload failed', 'error');
                }
            },
            error: function(){
                $('#currentImagePreview').html('');
                Swal.fire('Error', 'Image upload failed', 'error');
            }
        });
    });
});