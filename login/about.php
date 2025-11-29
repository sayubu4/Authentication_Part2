<?php
require_once(__DIR__ . '/../settings/core.php');
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - BurundiEats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #F97316;
            --primary-hover: #EA580C;
            --primary-dark: #9A3412;
            --light-bg: #FFF7ED;
            --card-bg: #ffffff;
            --text-color: #1f2937;
            --text-light: #6b7280;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--light-bg);
            color: var(--text-color);
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .nav-link {
            padding: 8px 16px;
            background: white;
            color: var(--primary-color);
            text-decoration: none;
            border-radius: 8px;
            border: 1px solid var(--primary-color);
            transition: all 0.3s;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .nav-link:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
            padding: 60px 40px;
            border-radius: 20px;
            text-align: center;
            margin-bottom: 50px;
            box-shadow: 0 10px 40px rgba(249, 115, 22, 0.2);
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .hero p {
            font-size: 1.2rem;
            opacity: 0.95;
        }

        /* Content Sections */
        .section {
            background: white;
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .section:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .section-title {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
        }

        .section-title i {
            font-size: 1.8rem;
        }

        .section-content {
            font-size: 1.1rem;
            color: var(--text-color);
            line-height: 1.8;
        }

        .section-content p {
            margin-bottom: 15px;
        }

        /* Vision & Mission Cards */
        .vision-mission-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .vm-card {
            background: linear-gradient(135deg, rgba(249, 115, 22, 0.1) 0%, rgba(234, 88, 12, 0.1) 100%);
            padding: 30px;
            border-radius: 15px;
            border-left: 4px solid var(--primary-color);
            transition: all 0.3s;
        }

        .vm-card:hover {
            transform: translateX(5px);
            box-shadow: 0 6px 20px rgba(249, 115, 22, 0.15);
        }

        .vm-card h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .vm-card p {
            color: var(--text-color);
            font-size: 1rem;
            line-height: 1.7;
        }

        /* Contact Section */
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-top: 25px;
        }

        .contact-item {
            background: var(--light-bg);
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .contact-item:hover {
            border-color: var(--primary-color);
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(249, 115, 22, 0.15);
        }

        .contact-item i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .contact-item h4 {
            color: var(--primary-color);
            margin-bottom: 10px;
            font-size: 1.2rem;
        }

        .contact-item p {
            color: var(--text-light);
            margin: 0;
        }

        .contact-item a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .contact-item a:hover {
            text-decoration: underline;
        }

        /* Values List */
        .values-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 25px;
        }

        .value-item {
            background: var(--light-bg);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .value-item:hover {
            border-color: var(--primary-color);
            background: white;
        }

        .value-item i {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .value-item h4 {
            color: var(--primary-color);
            margin-bottom: 8px;
            font-size: 1.1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .section {
                padding: 25px;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .vision-mission-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="brand">
                <i class="fas fa-utensils"></i>
                <span>BurundiEats</span>
            </div>
            <div class="nav-links">
                <a href="../index.php" class="nav-link">
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="cart.php" class="nav-link">
                    <i class="fas fa-heart"></i> Try Later
                </a>
                <a href="logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <!-- Hero Section -->
        <div class="hero">
            <h1>About BurundiEats</h1>
            <p>Discovering the Rich Culinary Heritage of Burundi</p>
        </div>

        <!-- Who We Are Section -->
        <div class="section">
            <h2 class="section-title">
                <i class="fas fa-users"></i>
                Who We Are
            </h2>
            <div class="section-content">
                <p>
                    BurundiEats is a dedicated platform celebrating the vibrant and diverse food culture of Burundi. 
                    We are passionate about connecting food lovers with authentic local restaurants, street food vendors, 
                    and culinary experiences across the beautiful regions of Burundi.
                </p>
                <p>
                    Our mission is to preserve and promote the rich culinary traditions of Burundi while supporting local 
                    food businesses and helping them reach a wider audience. We believe that food is not just sustenance—it's 
                    a way to experience culture, build community, and create lasting memories.
                </p>
                <p>
                    Whether you're a local resident looking to explore new flavors or a visitor wanting to experience authentic 
                    Burundian cuisine, BurundiEats is your gateway to discovering the best food spots in the country.
                </p>
            </div>
        </div>

        <!-- Vision & Mission -->
        <div class="vision-mission-grid">
            <div class="vm-card">
                <h3>
                    <i class="fas fa-eye"></i>
                    Our Vision
                </h3>
                <p>
                    To become the leading platform for discovering and celebrating Burundian cuisine, creating a bridge 
                    between food lovers and local culinary businesses while preserving the rich food heritage of our nation 
                    for future generations.
                </p>
            </div>

            <div class="vm-card">
                <h3>
                    <i class="fas fa-bullseye"></i>
                    Our Mission
                </h3>
                <p>
                    To empower local food businesses by providing them with a digital platform to showcase their offerings, 
                    while helping food enthusiasts discover authentic Burundian culinary experiences through detailed information, 
                    accurate locations, and trusted recommendations.
                </p>
            </div>
        </div>

        <!-- Our Values -->
        <div class="section">
            <h2 class="section-title">
                <i class="fas fa-heart"></i>
                Our Values
            </h2>
            <div class="values-list">
                <div class="value-item">
                    <i class="fas fa-shield-alt"></i>
                    <h4>Authenticity</h4>
                    <p>Promoting genuine local food experiences</p>
                </div>
                <div class="value-item">
                    <i class="fas fa-handshake"></i>
                    <h4>Community</h4>
                    <p>Supporting local food businesses</p>
                </div>
                <div class="value-item">
                    <i class="fas fa-star"></i>
                    <h4>Quality</h4>
                    <p>Curating the best food spots</p>
                </div>
                <div class="value-item">
                    <i class="fas fa-globe"></i>
                    <h4>Heritage</h4>
                    <p>Preserving culinary traditions</p>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="section">
            <h2 class="section-title">
                <i class="fas fa-envelope"></i>
                Contact Us
            </h2>
            <div class="section-content">
                <p>
                    We'd love to hear from you! Whether you have questions, suggestions, or want to list your food business 
                    on our platform, feel free to reach out to us.
                </p>
                <div class="contact-grid">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <h4>Email</h4>
                        <p>
                            <a href="mailto:info@burundieats.bi">info@burundieats.bi</a>
                        </p>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <h4>Phone</h4>
                        <p>
                            <a href="tel:+25779000000">+257 79 000 000</a>
                        </p>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <h4>Location</h4>
                        <p>Bujumbura, Burundi</p>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <h4>Business Hours</h4>
                        <p>Mon - Fri: 9:00 AM - 6:00 PM<br>Sat: 10:00 AM - 4:00 PM</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

