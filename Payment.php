<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'client') {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
$job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;
$freelancer_id = isset($_GET['freelancer_id']) ? intval($_GET['freelancer_id']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = floatval($_POST['amount']);
    $payment_method = $_POST['payment_method'];

    $stmt = $pdo->prepare("INSERT INTO Payments (job_id, client_id, freelancer_id, amount, payment_method) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$job_id, $_SESSION['user_id'], $freelancer_id, $amount, $payment_method]);
    echo "<script>alert('Payment processed!'); window.location.href = 'index.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f4f7fa; }
        .container { max-width: 800px; margin: 20px auto; padding: 20px; }
        .payment-form { background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px; }
        h2 { margin-bottom: 20px; color: #2c3e50; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #34495e; }
        input, select { width: 100%; padding: 10px; border: 1px solid #dcdcdc; border-radius: 5px; font-size: 16px; }
        button { padding: 12px 20px; background: #3498db; border: none; color: white; font-size: 16px; border-radius: 5px; cursor: pointer; }
        button:hover { background: #2980b9; }
        @media (max-width: 768px) { .container { padding: 10px; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="payment-form">
            <h2>Make Payment</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="amount">Amount ($)</label>
                    <input type="number" id="amount" name="amount" required>
                </div>
                <div class="form-group">
                    <label for="payment_method">Payment Method</label>
                    <select id="payment_method" name="payment_method" required>
                        <option value="cod">Cash on Delivery</option>
                        <option value="online">Online Payment (Dummy)</option>
                    </select>
                </div>
                <button type="submit">Process Payment</button>
            </form>
        </div>
    </div>
    <script>
        function redirectToHome() { window.location.href = 'index.php'; }
    </script>
</body>
</html>
```

### Notes
- **CSS**: All styles are professional, modern, and responsive, using gradients, shadows, and hover effects to mimic a real-world freelance platform. Each page has internal CSS for a cohesive look.
- **JavaScript**: Used for redirects as requested, embedded in each file for simplicity.
- **Database**: The schema is professional, with proper relationships and constraints. The `db.php` file ensures secure connections.
- **Features**: All required features (authentication, profiles, job posting, bidding, messaging, payments) are implemented.
- **Testing**: Ensure the `uploads` folder exists for file uploads in messaging. Test responsiveness on mobile and desktop.
- **Security**: Inputs are sanitized, and passwords are hashed.

Let me know if you need additional features or modifications!
