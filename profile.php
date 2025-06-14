<?php
session_start();
require 'db.php';

// Debugging: Log session data
error_log("Session Data in profile.php: " . print_r($_SESSION, true));

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    error_log("Redirecting to login.php: user_id=" . ($_SESSION['user_id'] ?? 'not set') . ", user_type=" . ($_SESSION['user_type'] ?? 'not set'));
    echo "<script>alert('Please log in to access your profile.'); window.location.href = 'login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Fetch user data
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        error_log("User not found for user_id: $user_id");
        echo "<script>alert('User not found.'); window.location.href = 'login.php';</script>";
        exit;
    }

    // Fetch freelancer profile if applicable
    $freelancer_profile = null;
    if ($_SESSION['user_type'] == 'freelancer') {
        $profile = $pdo->prepare("SELECT * FROM FreelancerProfiles WHERE user_id = ?");
        $profile->execute([$user_id]);
        $freelancer_profile = $profile->fetch(PDO::FETCH_ASSOC);
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_SESSION['user_type'] == 'freelancer') {
            $skills = htmlspecialchars($_POST['skills'] ?? '');
            $experience = htmlspecialchars($_POST['experience'] ?? '');
            $portfolio = htmlspecialchars($_POST['portfolio'] ?? '');
            $hourly_rate = floatval($_POST['hourly_rate'] ?? 0);

            $stmt = $pdo->prepare(
                "INSERT INTO FreelancerProfiles (user_id, skills, experience, portfolio, hourly_rate) 
                VALUES (?, ?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE skills = ?, experience = ?, portfolio = ?, hourly_rate = ?"
            );
            $stmt->execute([$user_id, $skills, $experience, $portfolio, $hourly_rate, $skills, $experience, $portfolio, $hourly_rate]);
            echo "<script>alert('Profile updated successfully!');</script>";
        } else {
            $business_name = htmlspecialchars($_POST['business_name'] ?? '');
            $business_description = htmlspecialchars($_POST['business_description'] ?? '');
            // Note: Client profile table not defined in schema; update logic as needed
            echo "<script>alert('Client profile updated (placeholder logic).');</script>";
        }
    }
} catch (PDOException $e) {
    error_log("Database error in profile.php: " . $e->getMessage());
    echo "<script>alert('Database error: " . htmlspecialchars($e->getMessage()) . "');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f4f7fa; }
        .container { max-width: 800px; margin: 20px auto; padding: 20px; }
        .profile-card { background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px; }
        h2 { margin-bottom: 20px; color: #2c3e50; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #34495e; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #dcdcdc; border-radius: 5px; font-size: 16px; }
        button { padding: 12px 20px; background: #3498db; border: none; color: white; font-size: 16px; border-radius: 5px; cursor: pointer; }
        button:hover { background: #2980b9; }
        @media (max-width: 768px) { .container { padding: 10px; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-card">
            <h2><?php echo htmlspecialchars($user['username']); ?>'s Profile</h2>
            <form method="POST">
                <?php if ($_SESSION['user_type'] == 'freelancer'): ?>
                    <div class="form-group">
                        <label for="skills">Skills</label>
                        <textarea id="skills" name="skills"><?php echo htmlspecialchars($freelancer_profile['skills'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="experience">Experience</label>
                        <textarea id="experience" name="experience"><?php echo htmlspecialchars($freelancer_profile['experience'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="portfolio">Portfolio</label>
                        <textarea id="portfolio" name="portfolio"><?php echo htmlspecialchars($freelancer_profile['portfolio'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="hourly_rate">Hourly Rate ($)</label>
                        <input type="number" id="hourly_rate" name="hourly_rate" step="0.01" value="<?php echo htmlspecialchars($freelancer_profile['hourly_rate'] ?? '0.00'); ?>">
                    </div>
                <?php else: ?>
                    <div class="form-group">
                        <label for="business_name">Business Name</label>
                        <input type="text" id="business_name" name="business_name" value="<?php echo htmlspecialchars($business_name ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="business_description">Business Description</label>
                        <textarea id="business_description" name="business_description"><?php echo htmlspecialchars($business_description ?? ''); ?></textarea>
                    </div>
                <?php endif; ?>
                <button type="submit">Save Profile</button>
            </form>
            <a href="javascript:void(0)" onclick="redirectToHome()">Back to Home</a>
        </div>
    </div>
    <script>
        function redirectToHome() { window.location.href = 'index.php'; }
    </script>
</body>
</html>
