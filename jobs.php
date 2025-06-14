<?php
session_start();
require 'db.php';

try {
    // Initialize query and parameters
    $sql = "SELECT * FROM Jobs WHERE status = 'open'";
    $params = [];

    // Handle filters
    $category = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : '';
    $budget = isset($_GET['budget']) ? floatval($_GET['budget']) : 0;

    if ($category) {
        $sql .= " AND category = ?";
        $params[] = $category;
    }
    if ($budget > 0) {
        $sql .= " AND budget <= ?";
        $params[] = $budget;
    }

    // Prepare and execute query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Log error for debugging (in production, log to file instead of displaying)
    error_log("Database error in jobs.php: " . $e->getMessage());
    die("<div style='text-align: center; padding: 20px; font-family: Arial, sans-serif;'>Error: Unable to fetch jobs. Please try again later.</div>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Jobs</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f4f7fa; }
        .container { max-width: 1200px; margin: 20px auto; padding: 20px; }
        .filter { margin-bottom: 20px; display: flex; gap: 20px; }
        .filter input, .filter select, .filter button { padding: 10px; border: 1px solid #dcdcdc; border-radius: 5px; font-size: 16px; }
        .filter button { background: #3498db; color: white; border: none; cursor: pointer; }
        .filter button:hover { background: #2980b9; }
        .job-list { background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 20px; }
        .job-card { border-bottom: 1px solid #ecf0f1; padding: 15px 0; }
        .job-card:last-child { border-bottom: none; }
        .job-card h3 { margin-bottom: 10px; color: #2c3e50; }
        .job-card p { margin-bottom: 10px; color: #34495e; }
        .job-link { color: #3498db; text-decoration: none; font-weight: bold; }
        .job-link:hover { text-decoration: underline; }
        .no-jobs { text-align: center; color: #7f8c8d; padding: 20px; }
        @media (max-width: 768px) { .filter { flex-direction: column; } .container { padding: 10px; } }
    </style>
</head>
<body>
    <div class="container">
        <h2>Find Jobs</h2>
        <form method="GET" class="filter">
            <select name="category">
                <option value="">All Categories</option>
                <option value="web" <?php echo $category == 'web' ? 'selected' : ''; ?>>Web Development</option>
                <option value="graphic" <?php echo $category == 'graphic' ? 'selected' : ''; ?>>Graphic Design</option>
                <option value="writing" <?php echo $category == 'writing' ? 'selected' : ''; ?>>Writing</option>
            </select>
            <input type="number" name="budget" placeholder="Max Budget" value="<?php echo $budget ? htmlspecialchars($budget) : ''; ?>">
            <button type="submit">Filter</button>
        </form>
        <div class="job-list">
            <?php if (empty($jobs)): ?>
                <p class="no-jobs">No jobs found matching your criteria.</p>
            <?php else: ?>
                <?php foreach ($jobs as $job): ?>
                    <div class="job-card">
                        <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                        <p><?php echo htmlspecialchars($job['description']); ?></p>
                        <p>Budget: $<?php echo htmlspecialchars($job['budget']); ?></p>
                        <p>Deadline: <?php echo htmlspecialchars($job['deadline']); ?></p>
                        <a href="javascript:void(0)" onclick="redirectToProposal(<?php echo $job['id']; ?>)" class="job-link">Apply Now</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function redirectToProposal(job_id) { window.location.href = 'proposals.php?job_id=' + job_id; }
    </script>
</body>
</html>
