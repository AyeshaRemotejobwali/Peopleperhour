<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'client') {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $category = htmlspecialchars($_POST['category']);
    $budget = floatval($_POST['budget']);
    $deadline = $_POST['deadline'];

    $stmt = $pdo->prepare("INSERT INTO Jobs (client_id, title, description, category, budget, deadline) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $title, $description, $category, $budget, $deadline]);
    echo "<script>alert('Job posted!'); window.location.href = 'jobs.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f4f7fa; }
        .container { max-width: 800px; margin: 20px auto; padding: 20px; }
        .job-form { background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px; }
        h2 { margin-bottom: 20px; color: #2c3e50; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 10px; color: #34495e; }
        input, textarea, select { width: 100%; padding: 10px; border: 1px solid #dcdcdc; border-radius: 5px; font-size: 16px; }
        button { padding: 12px 20px; background: #3498db; border: none; color: white; font-size: 16px; border-radius: 5px; cursor: pointer; }
        button:hover { background: #2980b9; }
        @media (max-width: 768px) { .container { padding: 10px; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="job-form">
            <h2>Post a Job</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="title">Job Title</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="web">Web Development</option>
                        <option value="graphic">Graphic Design</option>
                        <option value="writing">Writing</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="budget">Budget ($)</label>
                    <input type="number" id="budget" name="budget" required>
                </div>
                <div class="form-group">
                    <label for="deadline">Deadline</label>
                    <input type="date" id="deadline" name="deadline" required>
                </div>
                <button type="submit">Post Job</button>
            </form>
        </div>
    </div>
    <script>
        function redirectToJobs() { window.location.href = 'jobs.php'; }
    </script>
</body>
</html>
