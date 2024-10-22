<?php
//session_start();
require "../includes/header.php"; // Adjusted the directory separators to Unix-style
require "../includes/config.php"; // Adjusted the directory separators to Unix-style

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    die("You must be logged in to view applied users.");
}

// Get the job ID from the URL
$job_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($job_id == 0) {
    die("Invalid job ID.");
}

// Check if the connection is established
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch job details
$sql_job = "SELECT * FROM jobs WHERE id = ?";
$stmt_job = $conn->prepare($sql_job);

// Check if prepare() succeeded
if (!$stmt_job) {
    die("Prepare failed: " . $conn->error);
}

$stmt_job->bind_param("i", $job_id);
$stmt_job->execute();
$result_job = $stmt_job->get_result();
$job = $result_job->fetch_assoc();
$stmt_job->close();

// Fetch applied users with mail status
$sql_users = "SELECT users.id, users.username, users.email, applied.cv, applied.mail_status 
              FROM applied 
              JOIN users ON applied.user_id = users.id 
              WHERE applied.job_id = ?";
$stmt_users = $conn->prepare($sql_users);

// Check if prepare() succeeded
if (!$stmt_users) {
    die("Prepare failed: " . $conn->error);
}

$stmt_users->bind_param("i", $job_id);
$stmt_users->execute();
$result_users = $stmt_users->get_result();
$stmt_users->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applied Users</title>
    <link rel="stylesheet" href="../css/recruter.css"> <!-- Adjusted the directory separators to Unix-style -->
</head>
<body>
    <div class="container7">
        <h2>Applied Users for <?php echo htmlspecialchars($job['job_title']); ?></h2>
        <?php if ($result_users->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>CV</th>
                        <th>Delete</th>
                        <th>Send Mail</th> <!-- Column for "Send Mail" or "Mail Sended" status -->
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $result_users->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><a href="../<?php echo htmlspecialchars($user['cv']); ?>" target="_blank">View CV</a></td>
                            <td><a href="<?php echo APP_URL; ?>actions/delete.php?id=<?php echo $user['id']; ?>&job_id=<?php echo $job_id; ?>">Delete</a></td>
                            <td>
                                <?php if ($user['mail_status'] === 'sended'): ?>
                                    <span>Mail Sended</span>
                                <?php else: ?>
                                    <a href="<?php echo APP_URL; ?>mailer/send_mail.php?email=<?php echo urlencode($user['email']); ?>&job_title=<?php echo urlencode($job['job_title']); ?>&job_id=<?php echo $job_id ?>" class="btn">Send Mail</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users have applied for this job yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php require "../includes/footer.html"; ?>
