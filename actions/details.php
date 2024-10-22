<?php 
//session_start();
require "..\includes/header.php";
require "..\includes/config.php";

// Fetch job ID from the GET parameter
$job_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch job details based on job ID
$sql = "SELECT * FROM jobs WHERE id = $job_id";
$result = $conn->query($sql);

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    die("You must be logged in .");
}

// Handle form submission (saving job)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_job'])) {
    $user_id = $_SESSION['id'];  // Correct session variable for user ID
    $job_id = isset($_POST['job_id']) ? intval($_POST['job_id']) : 0;

    if ($job_id > 0) {
        // Insert into saved_job table
        $stmt = $conn->prepare("INSERT INTO saved_job (user_id, job_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $job_id);  // Bind user_id and job_id

        if ($stmt->execute()) {
            echo "<script>alert('Job saved successfully');</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: Invalid job ID.";
    }
}

// Handle form submission for comments
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_comment'])) {
    $user_id = $_SESSION['id'];  // Correct session variable for user ID
    $comment = htmlspecialchars($_POST['comment']);
    $job_id = isset($_POST['job_id']) ? intval($_POST['job_id']) : 0;

    if ($job_id > 0) {
        // Insert comment into the comments table
        $stmt = $conn->prepare("INSERT INTO comments (user_id, job_id, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $job_id, $comment);  // Bind user_id, job_id, and comment

        if ($stmt->execute()) {
            echo "<script>alert('Comment posted successfully');</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: Invalid job ID.";
    }
}

// Fetch comments related to the job
$comments_sql = "SELECT c.comment, u.username, c.created_at FROM comments c 
                 JOIN users u ON c.user_id = u.id WHERE c.job_id = $job_id ORDER BY c.created_at DESC";
$comments_result = $conn->query($comments_sql);

$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details</title>
    <link rel="stylesheet" href="..\css/list.css">
    <link rel="stylesheet" href="..\css/com.css">
</head>
<body>

<!-- JOB DATA -->
    <div class="container5">
        <?php if ($result->num_rows > 0): ?>
            <?php $row = $result->fetch_assoc(); ?>
            <div class="job-detail-box">
                <div class="image-section">
                    <img src="<?php echo APP_URL;?><?php echo htmlspecialchars($row['company_image']); ?>" alt="Company Image" class="company-image">
                </div>
                <div class="details-section">
                    <h2><?php echo htmlspecialchars($row['job_title']); ?></h2>
                    <p><strong>Company:</strong> <?php echo htmlspecialchars($row['company_name']); ?></p>
                    <p><strong>Company Email:</strong> <?php echo htmlspecialchars($row['company_email']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($row['state']) . ', ' . htmlspecialchars($row['region']); ?></p>
                    <p><strong>Job Time:</strong> <?php echo htmlspecialchars($row['job_time']); ?></p>
                    <p><strong>Vacancy:</strong> <?php echo htmlspecialchars($row['vacancy']); ?></p>
                    <p><strong>Salary:</strong> <?php echo htmlspecialchars($row['salary']); ?></p>
                    <p><strong>Experience:</strong> <?php echo htmlspecialchars($row['experience']); ?></p>
                    <p><strong>Responsibilities:</strong> <?php echo htmlspecialchars($row['responsibility']); ?></p>
                    <p><strong>Education Required:</strong> <?php echo htmlspecialchars($row['education_required']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
                    <div class="buttons">
                        <!-- Apply Now Form -->
                        <form action="<?php echo APP_URL; ?>actions/apply.php" method="post">
                            <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                            <button type="submit" class="apply-button">Apply Now</button>
                        </form>
                        <!-- Save Job Form -->
                        <form action="" method="post" class="save-job-form">
                            <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                            <input type="hidden" name="save_job" value="1">
                            <button type="submit" class="save-button">Save Job</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <p>No job details found.</p>
        <?php endif; ?>
    </div>

<hr>
<h3>Comments:</h3>
<!-- Comment Form -->
<div class="comment-form">
    <form action="" method="POST">
        <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
        <input type="hidden" name="post_comment" value="1">
        <textarea name="comment" rows="4" placeholder="Write a comment..." required></textarea>
        <button type="submit">Post Comment</button>
    </form>
</div>

<!-- Display Comments -->
<div class="comments-section">
    <?php if ($comments_result->num_rows > 0): ?>
        <?php while ($comment = $comments_result->fetch_assoc()): ?>
            <div class="comment-box">
                <span class="username"><?php echo htmlspecialchars($comment['username']); ?></span>
                <span class="timestamp">(<?php echo htmlspecialchars($comment['created_at']); ?>)</span>
                <p><?php echo htmlspecialchars($comment['comment']); ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No comments yet. Be the first to comment!</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php require "..\includes/footer.html"; ?>
