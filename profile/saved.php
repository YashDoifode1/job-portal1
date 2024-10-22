<?php
//session_start();
require "..\includes/header.php";
require "..\includes/config.php";

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    die("You must be logged in to view saved jobs.");
}

$user_id = $_SESSION['id'];

// Fetch saved jobs for the logged-in user
$sql = "SELECT saved_job.job_id, jobs.company_image, jobs.company_name, jobs.job_title, jobs.description, jobs.vacancy, jobs.salary 
        FROM saved_job 
        JOIN jobs ON saved_job.job_id = jobs.id 
        WHERE saved_job.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
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
    <title>Saved Jobs</title>
    <style>body {
    font-family: Arial, sans-serif;
    background-color: #f8f8f8;
    margin: 0;
    padding: 0;
}

.container5 {
    
    /* margin: 50px auto; */
    background-color: #fff;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

.job-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
}

.image-section {
    flex: 1;
    max-width: 150px;
    margin-right: 20px;
}

.image-section img {
    width: 100%;
    border-radius: 8px;
}

.details-section {
    flex: 3;
}

.details-section h3 {
    margin-top: 0;
    color: #4CAF50;
}

.details-section p {
    margin: 5px 0;
}

.buttons {
    display: flex;
    justify-content: flex-start;
}

.details-button, .delete-button {
    padding: 8px 12px;
    margin-right: 10px;
    text-decoration: none;
    color: #fff;
    border-radius: 4px;
}

.details-button {
    background-color: #4CAF50;
}

.delete-button {
    background-color: #f44336;
}

.details-button:hover {
    background-color: #45a049;
}

.delete-button:hover {
    background-color: #d32f2f;
}
</style>
</head>
<body>
    <div class="container5">
        <h2>Saved Jobs</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="job-item">
                    <div class="image-section">
                        <img src="<?php echo APP_URL; ?><?php echo htmlspecialchars($row['company_image']); ?>" alt="Company Image" class="company-image">
                    </div>
                    <div class="details-section">
                        <h3><?php echo htmlspecialchars($row['job_title']); ?></h3>
                        <p><strong>Company:</strong> <?php echo htmlspecialchars($row['company_name']); ?></p>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
                        <p><strong>Vacancy:</strong> <?php echo htmlspecialchars($row['vacancy']); ?></p>
                        <p><strong>Salary:</strong> <?php echo htmlspecialchars($row['salary']); ?></p>
                        <div class="buttons">
                            <a href="<?php echo APP_URL; ?>details.php?id=<?php echo $row['job_id']; ?>" class="details-button">View Details</a>
                            <a href="<?php echo APP_URL; ?>actions/sdelete.php?id=<?php echo $row['job_id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this saved job?');">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No saved jobs found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php require "..\includes/footer.html"; ?>
