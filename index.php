<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenPlate Catering | Premium Vegetarian Services</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* --- CSS VARIABLES --- */
        :root {
            --primary-color: #d63031; /* Deep Red */
            --primary-dark: #b71540;
            --accent-green: #00b894; /* Fresh Green */
            --text-dark: #3e3e3a;
            --text-light: #42484a;
            --bg-off-white: #fdfbf7;
            --hero: #c9d6d7af;
            --white: #ffffff;
            --shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        /* --- GLOBAL RESET --- */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-off-white); color: var(--text-dark); line-height: 1.6; overflow-x: hidden; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; color: var(--text-dark); }
        a { text-decoration: none; color: inherit; transition: 0.3s; }
        ul { list-style: none; }

        /* --- NAVIGATION (UPDATED FOR ALIGNMENT) --- */
        nav {
            display: flex; 
            justify-content: space-between; /* Pushes logo to left, links to right */
            align-items: center; /* Vertically centers everything in the middle */
            padding: 20px 5%; 
            background: var(--white); 
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky; 
            top: 0; 
            z-index: 1000;
            
            /* CRITICAL: Forces items to stay on one line */
            flex-wrap: nowrap; 
        }
        .logo { 
            font-size: 1.6rem; 
            font-weight: 700; 
            color: var(--primary-color); 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            white-space: nowrap; /* Prevents logo wrapping */
        }
        .nav-links { 
            display: flex; 
            align-items: center; /* Vertically centers links relative to logo */
            gap: 20px; /* Reduced gap slightly to fit better */
            white-space: nowrap; /* Prevents links wrapping onto new lines */
            flex-wrap: nowrap; /* Force one line */
        }
        .nav-links a { font-weight: 500; color: var(--text-dark); font-size: 0.9rem; }
        .nav-links a:hover { color: var(--primary-color); }
        .btn-nav { 
            padding: 8px 20px; /* Slightly smaller padding */
            background: var(--primary-color); 
            color: var(--white); 
            border-radius: 50px; 
            font-weight: 500; 
        }
        .btn-nav:hover { background: var(--primary-dark); color: var(--white); transform: translateY(-2px); }

        /* --- HERO SECTION --- */
        .hero {
            position: relative; height: 90vh; display: flex; align-items: center; justify-content: center;
            text-align: center; background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('./images/hero.avif');
            background-size: cover; background-position: center; color: var(--white);
        }
        .hero-content { max-width: 800px; padding: 20px; animation: fadeIn 1.5s ease; }
        .hero h1 { font-size: 3.5rem; margin-bottom: 20px; line-height: 1.2; color: var(--hero);}
        .hero p { font-size: 1.2rem; margin-bottom: 30px; opacity: 0.9; }
        .cta-button {
            display: inline-block; padding: 15px 40px; background: var(--primary-color); color: var(--white);
            font-size: 1.1rem; border-radius: 5px; font-weight: 600; letter-spacing: 1px; border: 2px solid var(--primary-color);
        }
        .cta-button:hover { background: transparent; color: var(--white); }

        /* --- FEATURES SECTION --- */
        .features { padding: 80px 5%; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; }
        .feature-card { background: var(--white); padding: 40px 30px; border-radius: 10px; box-shadow: var(--shadow); text-align: center; transition: 0.3s; }
        .feature-card:hover { transform: translateY(-10px); }
        .icon { font-size: 3rem; margin-bottom: 20px; display: block; }
        .feature-card h3 { margin-bottom: 15px; font-size: 1.4rem; }
        .feature-card p { color: var(--text-light); font-size: 0.95rem; }

        /* --- ABOUT / TEXT SECTION --- */
        .about { padding: 80px 5%; background: var(--white); display: flex; align-items: center; gap: 50px; flex-wrap: wrap; }
        .about-text { flex: 1; min-width: 300px; }
        .about-text h2 { font-size: 2.5rem; margin-bottom: 20px; }
        .about-text p { margin-bottom: 20px; color: var(--text-light); font-size: 1.05rem; }
        .about-image { flex: 1; min-width: 300px; border-radius: 10px; overflow: hidden; box-shadow: var(--shadow); }
        .about-image img { width: 100%; height: auto; display: block; transition: 0.5s; }
        .about-image img:hover { transform: scale(1.05); }

        /* --- CTA BAR --- */
        .cta-bar { background: var(--primary-color); padding: 60px 5%; text-align: center; color: var(--white); }
        .cta-bar h2 { color: var(--white); margin-bottom: 15px; }
        .cta-bar .btn-light { background: var(--white); color: var(--primary-color); padding: 12px 35px; border-radius: 50px; font-weight: 600; }
        .cta-bar .btn-light:hover { background: #f1f1f1; }

        /* --- FOOTER --- */
        footer { background: var(--text-dark); color: #b2bec3; padding: 50px 5% 20px; }
        .footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; margin-bottom: 40px; }
        .footer-col h4 { color: var(--white); margin-bottom: 20px; font-size: 1.2rem; }
        .footer-col ul li { margin-bottom: 10px; }
        .footer-col ul li a:hover { color: var(--primary-color); }
        .admin-link { font-size: 0.8rem; color: #636e72; margin-top: 30px; display: inline-block; border-top: 1px solid #636e72; padding-top: 10px; }
        
        .copyright { text-align: center; border-top: 1px solid #636e72; padding-top: 20px; font-size: 0.9rem; }

        /* --- RESPONSIVE (UPDATED) --- */
        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
            .about { flex-direction: column; }
            
            /* REMOVED flex-direction: column from nav to keep it on one line */
            nav { padding: 15px 20px; } 
            .logo { font-size: 1.2rem; } /* Shrink logo */
            .nav-links { gap: 10px; } /* Reduce gap */
            .nav-links a { font-size: 0.8rem; } /* Shrink text */
            .btn-nav { padding: 6px 12px; font-size: 0.8rem; } /* Shrink button */
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* --- HOW IT WORKS SECTION (OPTIMIZED) --- */
        .how-it-works {
            background: var(--white);
            padding: 80px 5%;
            text-align: center;
        }

        /* Grid Container for 3 Cards */
        .how-cards-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* Forces exactly 3 columns */
            gap: 40px; /* Space between cards */
            max-width: 1200px; /* Limits width so cards don't get too wide */
            margin: 40px auto 0; /* Centers the whole block */
            justify-items: center; /* Ensures cards are centered horizontally in their slots */
        }

        .work-card {
            background: white;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            width: 100%; /* Fill the grid cell */
            height: 100%; /* Ensure equal height */
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .work-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .work-icon {
            font-size: 3.5rem;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        .work-card h3 {
            font-size: 1.4rem;
            margin-bottom: 15px;
            color: var(--text-dark);
        }

        .work-card p {
            color: var(--text-light);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* Responsive: Stack cards on smaller screens/tablets */
        @media (max-width: 900px) {
            .how-cards-container {
                grid-template-columns: 1fr; /* Stack vertically */
            }
        }
    </style>
</head>
<body>

    <!-- NAVIGATION -->
    <nav>
        <div class="logo">🌿 GreenPlate</div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="#how">How it works</a>
            <a href="check_status.php">Track Request</a>
            <a href="client.php" class="btn-nav">Book Now</a>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero-content">
            <h1>Exquisite Vegetarian Catering <br>For Your Special Moments</h1>
            <p>Experience the perfect blend of tradition and taste. We craft memorable vegetarian menus for weddings, corporate events, and private parties.</p>
            <a href="client.php" class="cta-button">Plan Your Event</a>
        </div>
    </section>

    <!-- SERVICES / FEATURES -->
    <section id="services" class="features">
        <div class="feature-card">
            <span class="icon">🥗</span>
            <h3>Fresh Ingredients</h3>
            <p>We source organic, farm-fresh vegetables daily. Our commitment to quality ensures every bite is healthy and delicious.</p>
        </div>
        <div class="feature-card">
            <span class="icon">👨‍🍳</span>
            <h3>Expert Chefs</h3>
            <p>Our culinary team brings decades of experience in vegetarian cuisine, crafting authentic flavors with a modern twist.</p>
        </div>
        <div class="feature-card">
            <span class="icon">🕒</span>
            <h3>Timely Service</h3>
            <p>We respect your time. From setup to serving, our professional staff ensures your event runs smoothly.</p>
        </div>
    </section>


        <!-- HOW IT WORKS SECTION -->
    <section class="how-it-works" id="how">
        <div class="section-header">
            <h2 style="font-size: 2.5rem; margin-bottom: 10px;">How It Works</h2>
            <p style="color: var(--text-light);">Booking your event has never been easier.</p>
        </div>

        <div class="how-cards-container">
            <!-- Step 1 -->
            <div class="work-card">
                <div class="work-icon">📝</div>
                <h3>1. Submit Request</h3>
                <p>Fill out our online form with your event details, date, and guest count. It takes less than 2 minutes.</p>
            </div>

            <!-- Step 2 -->
            <div class="work-card">
                <div class="work-icon">👀</div>
                <h3>2. We Review</h3>
                <p>Our team reviews your request and confirms availability. You can check your status online instantly.</p>
            </div>

            <!-- Step 3 -->
            <div class="work-card">
                <div class="work-icon">🍽️</div>
                <h3>3. We Serve</h3>
                <p>Our team arrives on time to set up and serve an unforgettable meal to your guests.</p>
            </div>
        </div>
    </section>

    <!-- CTA BAR -->
    <section class="cta-bar">
        <h2>Ready to make your event unforgettable?</h2>
        <p style="margin-bottom: 20px; opacity: 0.9;">Limited slots available for this season.</p>
        <a href="client.php" class="btn-light">Book Catering Now</a>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="footer-grid">
            <div class="footer-col">
                <div class="logo" style="margin-bottom: 20px;">🌿 GreenPlate</div>
                <p>Premium vegetarian catering services for all occasions.</p>
            </div>
            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="client.php">Book Now</a></li>
                    <li><a href="check_status.php">Track Order</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact Us</h4>
                <ul>
                    <li>📍 123 Flavor Street, Food City</li>
                    <li>📞 +1 (555) 123-4567</li>
                    <li>✉️ hello@greenplate.com</li>
                </ul>
            </div>
        </div>
        
        <div class="copyright">
            &copy; 2023 GreenPlate Catering Services. All rights reserved.
            <br>
            <a href="admin.php" class="admin-link">Admin Staff Login</a>
        </div>
    </footer>

</body>
</html>