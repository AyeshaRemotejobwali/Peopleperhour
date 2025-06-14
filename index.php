<?php
session_start();
require 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelance Marketplace</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f4f7fa; }
        header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        nav { display: flex; justify-content: space-between; align-items: center; }
        nav a { color: white; text-decoration: none; margin: 0 15px; font-size: 18px; }
        .hero { background: linear-gradient(135deg, #3498db, #8e44ad); color: white; padding: 50px; text-align: center; }
        .hero h1 { font-size: 48px; margin-bottom: 20px; }
        .hero p { font-size: 20px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .freelancers, .categories { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 40px 0; }
        .card { background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px; text-align: center; transition: transform 0.3s; }
        .card:hover { transform: translateY(-5px); }
        .card img { width: 100px; height: 100px; border-radius: 50%; margin-bottom: 15px; }
        .card h3 { font-size: 22px; margin-bottom: 10px; }
        .card p { color: #7f8c8d; }
        footer { background: #2c3e50; color: white; text-align: center; padding: 20px; }
        @media (max-width: 768px) { .hero h1 { font-size: 32px; } .hero p { font-size: 16px; } }
    </style>
</head>
<body>
    <header>
        <nav>
            <div>
                <a href="index.php">Home</a>
                <a href="jobs.php">Find Jobs</a>
                <a href="post_job.php">Post Job</a>
            </div>
            <div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="profile.php">Profile</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="signup.php">Sign Up</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <section class="hero">
        <h1>Hire Top Freelancers for Any Job</h1>
        <p>Connect with professionals for your projects, big or small.</p>
    </section>
    <div class="container">
        <h2>Featured Freelancers</h2>
        <div class="freelancers">
            <div class="card">
                <img src="https://via.placeholder.com/100" alt="Freelancer">
                <h3>John Doe</h3>
                <p>Web Developer</p>
            </div>
            <div class="card">
                <img src="https://via.placeholder.com/100" alt="Freelancer">
                <h3>Jane Smith</h3>
                <p>Graphic Designer</p>
            </div>
        </div>
        <h2>Job Categories</h2>
        <div class="categories">
            <div class="card">
                <h3>Web Development</h3>
                <p>Build stunning websites</p>
            </div>
            <div class="card">
                <h3>Graphic Design</h3>
                <p>Create amazing visuals</p>
            </div>
        </div>
    </div>
    <footer>
        <script>
            function redirectToLogin() { window.location.href = 'login.php'; }
            function redirectToSignup() { window.location.href = 'signup.php'; }
        </script>
    </footer>
</body>
</html>
