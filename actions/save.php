
?>
<?php
//session_start();
require '..\includes/header.php';
require '..\includes/config.php';

// Check if the user is logged in
// if (!isset($_SESSION['username']name'])) {
//     die("You must be logged in to view saved jobs.");
// }

$user_id = $_SESSION['id'];

// Fetch saved jobs
$sql = "
    SELECT j.id, j.job_title, j.description, j.company_image, j.company_name, j.salary, j.vacancy 
    FROM saved_job sj
    JOIN jobs j ON sj.job_id = j.id
    WHERE sj.user_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Jobs</title>
    <link rel="stylesheet" href="..\css/list.css">
    <style>.job-list {
    display: flex;
    flex-wrap: wrap;
}

.job-item {
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 20px;
    margin: 10px;
    width: calc(33.333% - 20px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    align-items: center;
}

.job-image img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
}

.job-details {
    margin-top: 15px;
    text-align: center;
}

.job-details h3 {
    margin: 10px 0;
    font-size: 1.2em;
}

.job-details p {
    margin: 5px 0;
}

.details-button {
    display: inline-block;
    padding: 10px 20px;
    margin-top: 10px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.details-button:hover {
    background-color: #0056b3;
}

</style>
</head>
<body>
    <div class="container5">
        <h2>Saved Jobs</h2>
        <?php if ($result->num_rows > 0): ?>
            <div class="job-list">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="job-item">
                        <div class="job-image">
                            <img src="jobs/<?php echo htmlspecialchars($row['company_image']); ?>" alt="Company Image">
                        </div>
                        <div class="job-details">
                            <h3><?php echo htmlspecialchars($row['job_title']); ?></h3>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <p><strong>Company:</strong> <?php echo htmlspecialchars($row['company_name']); ?></p>
                            <p><strong>Salary:</strong> <?php echo htmlspecialchars($row['salary']); ?></p>
                            <p><strong>Vacancy:</strong> <?php echo htmlspecialchars($row['vacancy']); ?></p>
                            <a href="<?php echo APP_URL; ?>actions/details.php?id=<?php echo $row['id']; ?>" class="details-button">View Details</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>You have not saved any jobs yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
require '..\includes/footer.html';
?>
