<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register - Taste of Africa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        /* Food animations */
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        
        .food-icon {
            position: absolute;
            font-size: 1.5rem;
            opacity: 0.8;
            z-index: 0;
            animation: float 6s ease-in-out infinite;
        }
        
        .btn-custom {
            background-color: #F97316; /* Orange-600 */
            border: 1px solid #EA580C;
            color: white;
            transition: all 0.3s ease;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
        }

        .btn-custom:hover {
            background-color: #EA580C; /* Orange-700 */
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
        }

        .btn-custom:disabled {
            background-color: #ccc;
            border-color: #ccc;
            cursor: not-allowed;
        }

        .highlight {
            color: #EA580C; /* Orange-700 */
            transition: all 0.3s ease;
            text-decoration: none;
            font-weight: 500;
        }

        .highlight:hover {
            color: #9A3412; /* Orange-800 */
            text-decoration: underline;
        }

        body {
            background-color: #FFF7ED; /* Light orange background */
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            position: relative;
            overflow: hidden;
        }

        .register-container {
            margin-top: 50px;
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(249, 115, 22, 0.15);
            border: 1px solid rgba(249, 115, 22, 0.1);
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            position: relative;
            z-index: 1;
        }

        .card-header {
            background: linear-gradient(135deg, #F97316, #EA580C);
            color: white;
            border: none;
            padding: 1.25rem;
            text-align: center;
        }

        .custom-radio .form-check-input:checked+.form-check-label::before {
            background-color: #F97316;
            border-color: #F97316;
        }

        .form-check-label {
            position: relative;
            padding-left: 2rem;
            cursor: pointer;
        }

        .form-check-label::before {
            content: "";
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 1rem;
            height: 1rem;
            border: 2px solid #F97316;
            border-radius: 50%;
            background-color: #fff;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .form-check-input:focus+.form-check-label::before {
            box-shadow: 0 0 0 0.2rem rgba(249, 115, 22, 0.5);
        }

        .animate-pulse-custom {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .form-control:focus {
            border-color: #F97316;
            box-shadow: 0 0 0 0.2rem rgba(249, 115, 22, 0.25);
        }

        .validation-message {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }
        :root {
            --primary: #FF6B35;
            --primary-dark: #E25A2C;
            --secondary: #F7C59F;
            --accent: #2EC4B6;
            --light: #FDFFFC;
            --dark: #2B2D42;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
            --success: #28a745;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            background: #FFF7ED;
            padding: 2rem;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
        }
        
        .background-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.85);
            z-index: 0;
        }

        .auth-container {
            width: 100%;
            max-width: 32rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
            border: 1px solid rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(8px);
            margin: 2rem 0;
        }

        .auth-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .auth-logo {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            color: white;
        }

        .auth-subtitle {
            font-weight: 300;
            opacity: 0.9;
            font-size: 0.95rem;
            margin-bottom: 0;
        }
            border-right: none;
            color: var(--gray-600);
            height: 100%;
            min-width: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 0.5rem;
            border-radius: 0.5rem 0 0 0.5rem;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 0.5rem 0.5rem 0;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-register {
            background: var(--primary);
            border: none;
            color: white;
            padding: 0.8rem;
            border-radius: 0.5rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            font-size: 1rem;
            margin: 1.5rem 0 1rem;
        }

        .btn-register:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        }

        .form-footer {
            text-align: center;
            color: var(--gray-600);
            font-size: 0.9rem;
            margin-top: 1.5rem;
        }

        .form-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .form-footer a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .password-strength {
            height: 4px;
            background-color: var(--gray-200);
            border-radius: 2px;
            margin: 0.5rem 0 1rem;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            background-color: var(--gray-400);
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        .password-requirements {
            margin: 1rem 0;
            font-size: 0.85rem;
            color: var(--gray-600);
        }

        .requirement {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .requirement.valid {
            color: var(--success);
        }

        .requirement i {
            margin-right: 0.5rem;
            font-size: 0.7rem;
        }

        .requirement.valid i {
            color: var(--success);
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(255, 107, 53, 0.25);
        }

        .form-check-label {
            color: var(--gray-700);
            font-size: 0.9rem;
        }

        .terms-text {
            font-size: 0.85rem;
            color: var(--gray-600);
            margin-left: 1.5rem;
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 2rem;
                max-height: 70vh;
                overflow-y: auto;
            }
            
            .auth-container {
                border-radius: 0.75rem;
            }
            
            .auth-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Animated Food Icons -->
    <i class="fas fa-hamburger food-icon" style="top: 10%; left: 5%; animation-delay: 0s; color: #F97316;"></i>
    <i class="fas fa-pizza-slice food-icon" style="top: 20%; right: 8%; animation-delay: 1s; color: #EA580C;"></i>
    <i class="fas fa-ice-cream food-icon" style="top: 40%; left: 7%; animation-delay: 2s; color: #F97316;"></i>
    <i class="fas fa-drumstick-bite food-icon" style="bottom: 30%; right: 10%; animation-delay: 0.5s; color: #EA580C;"></i>
    <i class="fas fa-apple-alt food-icon" style="bottom: 15%; left: 12%; animation-delay: 1.5s; color: #F97316;"></i>
    <i class="fas fa-fish food-icon" style="top: 15%; left: 20%; animation-delay: 2.5s; color: #EA580C;"></i>
    <i class="fas fa-cheese food-icon" style="bottom: 25%; right: 20%; animation-delay: 1s; color: #F97316;"></i>
    <i class="fas fa-lemon food-icon" style="bottom: 40%; left: 15%; animation-delay: 0.8s; color: #EA580C;"></i>
    <div class="container register-container">
        <div class="row justify-content-center animate__animated animate__fadeInDown">
            <div class="col-md-6">
                <div class="card animate__animated animate__zoomIn">
                    <div class="card-header text-center highlight">
                        <h4>Register</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="mt-4" id="register-form">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name <i class="fa fa-user"></i></label>
                                <input type="text" class="form-control animate__animated animate__fadeInUp" id="name" name="name" required maxlength="100">
                                <div class="validation-message">Letters and spaces only (2-100 characters)</div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <i class="fa fa-envelope"></i></label>
                                <input type="email" class="form-control animate__animated animate__fadeInUp" id="email" name="email" required maxlength="50">
                                <div class="validation-message">Must be a valid email address</div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password <i class="fa fa-lock"></i></label>
                                <input type="password" class="form-control animate__animated animate__fadeInUp" id="password" name="password" required maxlength="150">
                                <div class="validation-message">At least 6 characters with uppercase, lowercase, and number</div>
                            </div>
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Contact Number <i class="fa fa-phone"></i></label>
                                <input type="text" class="form-control animate__animated animate__fadeInUp" id="phone_number" name="phone_number" required maxlength="15">
                                <div class="validation-message">10-15 digits, can include +, spaces, or hyphens</div>
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">Country <i class="fa fa-globe"></i></label>
                                <input type="text" class="form-control animate__animated animate__fadeInUp" id="country" name="country" required maxlength="30">
                                <div class="validation-message">Letters and spaces only (2-30 characters)</div>
                            </div>
                            <div class="mb-3">
                                <label for="city" class="form-label">City <i class="fa fa-building"></i></label>
                                <input type="text" class="form-control animate__animated animate__fadeInUp" id="city" name="city" required maxlength="30">
                                <div class="validation-message">Letters and spaces only (2-30 characters)</div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Register As</label>
                                <div class="d-flex justify-content-start">
                                    <div class="form-check me-3 custom-radio">
                                        <input class="form-check-input" type="radio" name="role" id="customer" value="1" checked>
                                        <label class="form-check-label" for="customer">Customer</label>
                                    </div>
                                    <div class="form-check custom-radio">
                                        <input class="form-check-input" type="radio" name="role" id="admin" value="2">
                                        <label class="form-check-label" for="admin">Administrator</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-custom w-100 animate-pulse-custom">Register</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        Already have an account? <a href="login.php" class="highlight">Login here</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/register.js"></script>
</body>

</html>
