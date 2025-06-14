<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f4f7fa; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .logout-container { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center; max-width: 400px; width: 100%; }
        h2 { color: #2c3e50; margin-bottom: 20px; }
        p { color: #34495e; margin-bottom: 20px; }
        .btn { padding: 12px 20px; background: #3498db; border: none; color: white; font-size: 16px; border-radius: 5px; text-decoration: none; display: inline-block; }
        .btn:hover { background: #2980b9; }
        @media (max-width: 480px) { .logout-container { padding: 20px; } }
    </style>
</head>
<body>
    <div class="logout-container">
        <h2>Logged Out</h2>
        <p>You have been successfully logged out.</p>
        <a href="javascript:void(0)" onclick="redirectToLogin()" class="btn">Go to Login</a>
    </div>
    <script>
        function redirectToLogin() { window.location.href = 'login.php'; }
        setTimeout(redirectToLogin, 2000); // Auto-redirect after 2 seconds
    </script>
</body>
</html>
