$(document).ready(function() {
    $('#login-form').submit(function(e) {
        e.preventDefault();

        email = $('#email').val();
        password = $('#password').val();

        // Clear previous error messages
        $('.error-message').remove();

        // Validate email
        if (email == '') {
            showError('email', 'Email is required');
            return;
        } else if (!isValidEmail(email)) {
            showError('email', 'Please enter a valid email address');
            return;
        }

        // Validate password
        if (password == '') {
            showError('password', 'Password is required');
            return;
        } else if (password.length < 6) {
            showError('password', 'Password must be at least 6 characters long');
            return;
        }

        console.log('Sending login request to: ../actions/login_customer_action.php');
        console.log('Data being sent:', {
            email: email,
            password: password
        });

        // Show loading state
        const submitBtn = $('#login-btn');
        const originalText = submitBtn.text();
        submitBtn.prop('disabled', true).text('Logging in...');

        $.ajax({
            url: '../actions/login_customer_action.php',
            type: 'POST',
            dataType: 'json',
            data: {
                email: email,
                password: password
            },
            success: function(response) {
                console.log('Login response:', response);
                
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Welcome back!',
                        text: 'Login successful. Redirecting...',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '../index.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: response.message,
                    });
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', xhr.responseText);
                console.log('Status:', status);
                console.log('Error:', error);
                
                let errorMessage = 'An error occurred! Please try again later.';
                
                // Try to parse error response
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.message) {
                        errorMessage = errorResponse.message;
                    }
                } catch (e) {
                    // If response is not JSON, show the raw response (truncated)
                    if (xhr.responseText) {
                        errorMessage = 'Server Error: ' + xhr.responseText.substring(0, 100) + '...';
                    }
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: errorMessage,
                });
            },
            complete: function() {
                // Reset button state
                submitBtn.prop('disabled', false).text(originalText);
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
