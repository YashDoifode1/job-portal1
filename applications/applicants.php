

<?php
//session_start();
require "..\includes/header.php";
require "..\includes/config.php";

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    die("You must be logged in to view applications.");
}

$job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// Fetch application details for the given job ID and user ID
$sql = "SELECT applied.*, jobs.job_title, jobs.company_name, users.username, users.cv, users.email
        FROM applied 
        JOIN jobs ON applied.job_id = jobs.id
        JOIN users ON applied.user_id = users.id
        WHERE applied.job_id = ? AND applied.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $job_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Details</title>
    <link rel="stylesheet" href="..\css/applicants.css">
</head>
<body>
    <div class="container6">
        <h2>Application Details</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php $row = $result->fetch_assoc(); ?>
            <div class="application-detail-box">
                <div class="details-section">
                    <h3>Job Title: <?php echo htmlspecialchars($row['job_title']); ?></h3>
                    <p><strong>Company:</strong> <?php echo htmlspecialchars($row['company_name']); ?></p>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($row['username']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                    <p><strong>CV:</strong> <a href="..\cvs/<?php echo htmlspecialchars($row['cv']); ?>" target="_blank">View CV</a></p>
                </div>
            </div>
        <?php else: ?>
            <p>No application details found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php require "..\includes/footer.html"; ?>
