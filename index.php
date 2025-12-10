DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventSync - AUI Event Management</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-familrg -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Segoe UI', Roboto, sans-serif; 
            background: linear-gradient(135deg, #f0f8ff 0%, #e0f2fe 100%); 
            color: #1f2937; 
            line-height: 1.6; 
            overflow-x: hidden;
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; position: relative; z-index: 1; }
        header { 
            position: fixed; 
            top: 0; 
            left: 0; 
            right: 0; 
            background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(20px); 
            border-bottom: 1px solid rgba(255, 255, 255, 0.2); 
            z-index: 100; 
            transition: all 0.3s ease; 
        }
        nav { display: flex; justify-content: space-between; align-items: center; padding: 1rem 2rem; }
        .logo { display: flex; align-items: center; gap: 0.5rem; }
        .logo img { width: 32px; filter: drop-shadow(0 1px 2px rgba(0,0,0,0.1)); }
        .logo h1 { color: #1d4ed8; font-size: 1.5rem; font-weight: 600; letter-spacing: -0.025em; }
        .nav-links { display: flex; gap: 2rem; }
        .nav-links a { 
            color: #374151; 
            text-decoration: none; 
            font-weight: 500; 
            transition: all 0.2s ease; 
            border-radius: 8px; 
            padding: 0.5rem 1rem; 
        }
        .nav-links a:hover { background: rgba(59, 130, 246, 0.1); color: #1d4ed8; }
        .hero { 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            text-align: center; 
            position: relative; 
            background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 50%, #60a5fa 100%); 
            color: white; 
        }
        .hero::before { 
            content: ''; 
            position: absolute; 
            top: 0; 
            left: 0; 
            right: 0; 
            bottom: 0; 
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>'); 
            opacity: 0.05; 
        }
        .hero-content { position: relative; z-index: 1; max-width: 800px; padding: 0 2rem; }
        .hero h2 { font-size: clamp(2.5rem, 5vw, 4rem); margin-bottom: 1rem; font-weight: 700; letter-spacing: -0.02em; }
        .hero p { font-size: clamp(1rem, 3vw, 1.25rem); margin-bottom: 2rem; opacity: 0.95; }
        .cta { 
            background: rgba(255, 255, 255, 0.95); 
            backdrop-filter: blur(10px); 
            color: #1d4ed8; 
            padding: 1rem 2.5rem; 
            border-radius: 50px; 
            text-decoration: none; 
            font-weight: 600; 
            transition: all 0.3s ease; 
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); 
            display: inline-block; 
        }
        .cta:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15); background: white; }
        .features { padding: 6rem 0; }
        .features h3 { text-align: center; font-size: clamp(2rem, 4vw, 3rem); margin-bottom: 4rem; color: #1d4ed8; font-weight: 600; letter-spacing: -0.025em; }
        .feature-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; }
        .feature { 
            background: rgba(255, 255, 255, 0.7); 
            backdrop-filter: blur(10px); 
            border: 1px solid rgba(255, 255, 255, 0.2); 
            border-radius: 20px; 
            padding: 2.5rem; 
            text-align: center; 
            transition: all 0.3s ease; 
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05); 
        }
        .feature:hover { transform: translateY(-5px); box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1); }
        .feature-icon { font-size: 4rem; margin-bottom: 1rem; opacity: 0.8; }
        .feature h4 { font-size: 1.5rem; margin-bottom: 1rem; color: #1d4ed8; font-weight: 600; }
        .feature p { opacity: 0.9; }
        footer { 
            background: rgba(30, 64, 175, 0.95); 
            backdrop-filter: blur(10px); 
            color: white; 
            text-align: center; 
            padding: 3rem 1rem; 
            border-top: 1px solid rgba(255, 255, 255, 0.1); 
        }
        footer a { color: white; opacity: 0.8; text-decoration: none; transition: opacity 0.2s; }
        footer a:hover { opacity: 1; }
        @media (max-width: 768px) { 
            .hero h2 { font-size: 2.5rem; } 
            .nav-links { gap: 1rem; } 
            .features { padding: 3rem 1rem; } 
            .feature { padding: 1.5rem; } 
        }
    </style>
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">
                <h1>EventSync</h1>
            </div>
            <div class="nav-links">
                <a href="#features">Features</a>
                <a href="login.php">Login</a>
                <a href="sign-up.php">Sign Up</a>
            </div>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h2>Sync Your Campus Life</h2>
            <p>Discover, reserve, and stay connected with AUI events—effortlessly. From lectures to socials, never miss a moment.</p>
            <a href="sign-up.php" class="cta">Join Now – It's Free</a>
        </div>
    </section>

    <section id="features" class="features">
        <div class="container">
            <h3>Designed for AUI</h3>
            <div class="feature-grid">
                <div class="feature">
                    <div class="feature-icon">📅</div>
                    <h4>Seamless Discovery</h4>
                    <p>Browse categorized events with real-time availability. Filter by date, type, or location for your perfect fit.</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">🎫</div>
                    <h4>One-Click Reservations</h4>
                    <p>Secure your spot instantly. Get smart reminders and easy unreserve options to keep things flexible.</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">👥</div>
                    <h4>Community Tools</h4>
                    <p>Staff create and manage events with ease. Students rate, comment, and connect—building a vibrant campus.</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">⭐</div>
                    <h4>Rated & Trusted</h4>
                    <p>Feedback shapes better events. Share experiences and discover top-rated gatherings tailored for you.</p>
                </div>
            </div>
        </div>
    </section>

   <footer>
    <div class="container">
        <p>&copy; 2025 EventSync. Empowering AUI Connections.</p>
    </div>
</footer>
