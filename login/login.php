<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Taste of Africa</title>
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

        .login-container {
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

        /* Additional Styling for Enhanced Appearance */
        .form-label i {
            margin-left: 5px;
            color: #F97316;
        }

        .alert-info {
            animation: fadeIn 1s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
        :root {
            --primary: #FF6B35;     /* Vibrant orange */
            --primary-dark: #E25A2C;
            --secondary: #F7C59F;   /* Light peach */
            --accent: #2EC4B6;      /* Teal accent */
            --light: #FDFFFC;       /* Off-white */
            --dark: #2B2D42;        /* Dark blue-gray */
            --gray-200: #E9ECEF;
            --gray-400: #CED4DA;
            --gray-600: #6C757D;
            --gray-800: #343A40;
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
            align-items: center;
            justify-content: center;
            background: url('https://images.unsplash.com/photo-1504674900247-0877039340c1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') no-repeat center center/cover;
            padding: 1rem;
            position: relative;
            overflow: hidden;
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
            max-width: 28rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(8px);
        }

        .auth-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .auth-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            transform: rotate(30deg);
        }

        .auth-logo {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            color: white;
        }

        .auth-logo i {
            color: var(--yellow-50);
        }

        .auth-subtitle {
            font-weight: 300;
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .auth-body {
            padding: 2rem;
        }

        .form-control {
            border: 1px solid #FED7AA; /* Orange-200 */
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus {
            border-color: #F97316;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.2);
        }

        .form-label {
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            display: block;
        }

        .btn-login {
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
        
        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, var(--orange-600), var(--red-600));
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
        }

        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--gray-600);
            font-size: 0.9rem;
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

        .form-footer a:hover {
            color: var(--red-600);
            text-decoration: underline;
        }

        .input-group-text {
            background: var(--gray-100);
            border: 1px solid var(--gray-300);
            border-right: none;
            color: var(--gray-600);
            height: 100%;
            min-width: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 0.5rem;
        }

        .input-group .form-control {
            border-left: none;
            padding: 0.75rem 1rem;
            height: auto;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--primary);
            color: var(--primary);
        }

        .input-group:focus-within .form-control {
            border-left: 1px solid var(--orange-500);
        }

        .floating-food {
            position: absolute;
            opacity: 0.6;
            z-index: 0;
            animation: float 8s ease-in-out infinite;
        }

        .floating-food:nth-child(1) {
            top: 10%;
            left: 5%;
            width: 80px;
            animation-delay: 0s;
        }

        .floating-food:nth-child(2) {
            bottom: 15%;
            right: 5%;
            width: 100px;
            animation-delay: 1s;
        }

        .floating-food:nth-child(3) {
            top: 50%;
            right: 10%;
            width: 60px;
            animation-delay: 2s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        @media (max-width: 640px) {
            .auth-container {
                margin: 1rem;
            }
            
            .auth-header, .auth-body {
                padding: 1.5rem;
            }
        }

        .alert-info {
            animation: fadeIn 1s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
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
    <div class="container login-container">
        <div class="row justify-content-center animate__animated animate__fadeInDown">
            <div class="col-md-6">
                <div class="card animate__animated animate__zoomIn">
                    <div class="card-header text-center highlight">
                        <h4>Login</h4>
                    </div>
                    <div class="card-body">
                        <!-- Alert Messages (To be handled by backend) -->
                        <!-- Example:
                        <div class="alert alert-info text-center">Login successful!</div>
                        -->

                        <form method="POST" action="" class="mt-4" id="login-form">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <i class="fa fa-envelope"></i></label>
                                <input type="email" class="form-control animate__animated animate__fadeInUp" id="email" name="email" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Password <i class="fa fa-lock"></i></label>
                                <input type="password" class="form-control animate__animated animate__fadeInUp" id="password" name="password" required>
                            </div>
                            <button type="submit" id="login-btn" class="btn btn-custom w-100 animate-pulse-custom">Login</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        Don't have an account? <a href="register.php" class="highlight">Register here</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/login.js"></script>
</body>

</html>