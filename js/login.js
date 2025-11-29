$(document).ready(function() {
    console.log('Login script loaded');
    
    // Email validation function
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    // Show error message function
    function showError(field, message) {
        $(`#${field}`).addClass('is-invalid');
        $(`<div class="invalid-feedback">${message}</div>`).insertAfter(`#${field}`);
    }
    
    // Form submission
    $('#login-form').on('submit', function(e) {
        e.preventDefault();
        
        const email = $('#email').val().trim();
        const password = $('#password').val();
        
        // Clear previous errors and feedback
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        $('.alert').remove();
        
        // Validate form
        let isValid = true;
        
        if (!email) {
            showError('email', 'Email is required');
            isValid = false;
        } else if (!isValidEmail(email)) {
            showError('email', 'Please enter a valid email');
            isValid = false;
        }
        
        if (!password) {
            showError('password', 'Password is required');
            isValid = false;
        } else if (password.length < 6) {
            showError('password', 'Password must be at least 6 characters');
            isValid = false;
        }
        
        if (!isValid) return;
        
        // Prepare and send request
        const submitBtn = $('#login-btn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Logging in...');
        
        $.ajax({
            url: '../actions/login_customer_action.php',
            type: 'POST',
            dataType: 'json',
            data: {
                email: email,
                password: password
            },
            // Ensure cookies are sent with the request
            xhrFields: {
                withCredentials: true
            },
            success: function(response, status, xhr) {
                console.log('Login response:', response);
                
                // Reset button state
                submitBtn.prop('disabled', false).html(originalText);
                
                if (response && response.status === 'success') {
                    console.log('Login successful, redirecting...');
                    
                    // Show success message
                    const successHtml = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> Login successful. Redirecting...
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                    $('.card-body').prepend(successHtml);
                    
                    // Clear any existing timeout to prevent multiple redirects
                    if (window.loginRedirectTimeout) {
                        clearTimeout(window.loginRedirectTimeout);
                    }
                    
                    // Get redirect URL from session or use default
                    const redirectUrl = response.redirect_url || '../index.php';
                    
                    // Redirect after a short delay
                    window.loginRedirectTimeout = setTimeout(() => {
                        // Force a hard redirect to prevent caching issues
                        window.location.href = redirectUrl + (redirectUrl.includes('?') ? '&' : '?') + 'logged_in=1&t=' + new Date().getTime();
                    }, 1000);
                } else {
                    // Show error message from server or default message
                    const errorMessage = (response && response.message) || 'Login failed. Please try again.';
                    
                    const errorHtml = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> ${errorMessage}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                    $('.card-body').prepend(errorHtml);
                    
                    console.error('Login failed:', errorMessage);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', {
                    status: status,
                    error: error,
                    response: xhr.responseText,
                    statusCode: xhr.status,
                    statusText: xhr.statusText,
                    readyState: xhr.readyState
                });
                
                let errorMessage = 'An error occurred while processing your request. Please try again later.';
                
                // Try to parse error response
                try {
                    if (xhr.responseText) {
                        const errorResponse = JSON.parse(xhr.responseText);
                        if (errorResponse && errorResponse.message) {
                            errorMessage = errorResponse.message;
                        } else {
                            errorMessage = xhr.responseText.substring(0, 200);
                        }
                    }
                } catch (e) {
                    console.error('Error parsing error response:', e);
                    if (xhr.responseText) {
                        errorMessage = 'Error: ' + xhr.responseText.substring(0, 200);
                    }
                }
                
                // Show error message in a more user-friendly way
                const errorHtml = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> ${errorMessage}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
                $('.card-body').prepend(errorHtml);
            },
            complete: function(xhr, status) {
                console.log('Request complete. Status:', status);
                // Reset button state with a slight delay for better UX
                setTimeout(() => {
                    submitBtn.prop('disabled', false).html(originalText);
                }, 500);
            }
        });
    });

    // Email validation function
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Show error message function
    function showError(fieldId, message) {
        const field = $('#' + fieldId);
        field.addClass('is-invalid');
        
        // Remove existing error message
        field.siblings('.error-message').remove();
        
        // Add new error message
        field.after('<div class="error-message text-danger small mt-1">' + message + '</div>');
        
        // Focus on the field
        field.focus();
    }

    // Clear error styling on input
    $('input').on('input', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.error-message').remove();
    });
});
