<?php
session_start();
require 'db.php';

// Debugging: Log session data to check if session is maintained
error_log("Session Data in proposals.php: " . print_r($_SESSION, true));

// Check if user is logged in and is a freelancer
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'freelancer') {
    error_log("Redirecting to login.php: user_id=" . ($_SESSION['user_id'] ?? 'not set') . ", user_type=" . ($_SESSION['user_type'] ?? 'not set'));
    echo "<script>alert('Please log in as a freelancer to submit a proposal.'); window.location.href = 'login.php';</script>";
    exit;
}

$job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bid_amount = floatval($_POST['bid_amount']);
    $proposal_text = htmlspecialchars($_POST['proposal_text']);

    try {
        $stmt = $pdo->prepare("INSERT INTO Proposals (job_id, freelancer_id, bid_amount, proposal_text) VALUES (?, ?, ?, ?)");
        $stmt->execute([$job_id, $_SESSION['user_id'], $bid_amount, $proposal_text]);
        echo "<script>alert('Proposal submitted successfully!'); window.location.href = 'jobs.php';</script>";
    } catch (PDOException $e) {
        error_log("Error in proposals.php: " . $e->getMessage());
        echo "<script>alert('Error submitting proposal: " . htmlspecialchars($e->getMessage()) . "');</script>";
    }
}

try {
    $stmt = $pdo->prepare("SELECT * FROM Jobs WHERE id = ?");
    $stmt->execute([$job_id]);
    $job = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$job) {
        error_log("Invalid job ID: " . $job_id);
        echo "<script>alert('Invalid job ID'); window.location.href = 'jobs.php';</script>";
        exit;
    }
} catch (PDOException $e) {
    error_log("Database error in proposals.php: " . $e->getMessage());
    echo "<script>alert('Error fetching job details: " . htmlspecialchars($e->getMessage()) . "');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Proposal</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f4f7fa; }
        .container { max-width: 800px; margin: 20px auto; padding: 20px; }
        .proposal-form { background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px; }
        h2 { margin-bottom: 20px; color: #2c3e50; }
        .job-details { margin-bottom: 20px; padding: 15px; background: #ecf0f1; border-radius: 5px; }
        .job-details h3 { color: #34495e; margin-bottom: 10px; }
        .job-details p { color: #7f8c8d; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #34495e; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #dcdcdc; border-radius: 5px; font-size: 16px; }
        button { padding: 12px 20px; background: #3498db; border: none; color: white; font-size: 16px; border-radius: 5px; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #2980b9; }
        a { color: #3498db; text-decoration: none; }
        a:hover { text-decoration: underline; }
        @media (max-width: 768px) { .container { padding: 10px; } .proposal-form { padding: 15px; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="proposal-form">
            <h2>Submit Proposal for "<?php echo htmlspecialchars($job['title']); ?>"</h2>
            <div class="job-details">
                <h3>Job Details</h3>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($job['description']); ?></p>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($job['category']); ?></p>
                <p><strong>Budget:</strong> $<?php echo htmlspecialchars($job['budget']); ?></p>
                <p><strong>Deadline:</strong> <?php echo htmlspecialchars($job['deadline']); ?></p>
            </div>
            <form method="POST">
                <div class="form-group">
                    <label for="bid_amount">Bid Amount ($)</label>
                    <input type="number" id="bid_amount" name="bid_amount" min="0" step="0.01" required>
                </div>
                sforzo class="form-group">
                    <label for="proposal_text">Proposal</label>
                    <textarea id="proposal_text" name="proposal_text" rows="6" required></textarea>
                </div>
                <button type="submit">Submit Proposal</button>
            </form>
            <a href="javascript:void(0)" onclick="redirectToJobs()">Back to Jobs</a>
        </div>
    </div>
    <script>
        function redirectToJobs() { window.location.href = 'jobs.php'; }
    </script>
</body>
</html>
