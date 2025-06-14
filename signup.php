<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_type = $_POST['user_type'];

    $stmt = $pdo->prepare("INSERT INTO Users (username, email, password, user_type) VALUES (?, ?, ?, ?)");
    try {
        $stmt->execute([$username, $email, $password, $user_type]);
        echo "<script>alert('Registration successful!'); window.location.href = 'login.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f4f7fa; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .signup-container { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; margin-bottom: 20px; color: #2c3e50; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #34495e; }
        input, select { width: 100%; padding: 10px; border: 1px solid #dcdcdc; border-radius: 5px; font-size: 16px; }
        button { width: 100%; padding: 12px; background: #3498db; border: none; color: white; font-size: 18px; border-radius: 5px; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #2980b9; }
        a { display: block; text-align: center; margin-top: 15px; color: #3498db; text-decoration: none; }
        @media (max-width: 480px) { .signup-container { padding: 20px; } }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="user_type">I am a</label>
                <select id="user_type" name="user_type" required>
                    <option value="freelancer">Freelancer</option>
                    <option value="client">Client</option>
                </select>
            </div>
            <button type="submit">Sign Up</button>
        </form>
        <a href="javascript:void(0)" onclick="redirectToLogin()">Already have an account? Login</a>
    </div>
    <script>
        function redirectToLogin() { window.location.href = 'login.php'; }
    </script>
</body>
</html>
