<?php
// Connect to the database
include('..\includes/config.php');
include('..\includes/header.php');
// Fetch categories from the database


$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Fetch jobs related to selected category
$jobs_query = "SELECT id, job_title, description, company_name, company_image, vacancy, salary FROM jobs WHERE category_id = ?";
$stmt = $conn->prepare($jobs_query);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$jobsResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs by Category</title>
    <style>
        .job-list {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.job-item {
    border: 1px solid #ddd;
    padding: 20px;
    margin: 10px 0;
    width: 80%;
    display: flex;
    align-items: center;
    background-color: #f9f9f9;
}

.company-image {
    max-width: 100px;
    margin-right: 20px;
}

.job-details {
    flex-grow: 1;
}

.apply-button {
    padding: 10px 15px;
    background-color: #28a745;
    color: white;
    border: none;
    cursor: pointer;
}

.apply-button a {
    color: white;
    text-decoration: none;
}

    </style>
</head>
<body>

    <Center><h1>Job Listings by Category</h1></Center>

    <ul class="job-list">
        <!-- Check if there are results and display the jobs -->
        <?php if (isset($jobsResult) && $jobsResult->num_rows > 0): ?>
            <?php while ($row = $jobsResult->fetch_assoc()): ?>
                <li class="job-item">
                    <!-- Company image -->
                    <img src="<?php echo APP_URL ; ?><?php echo htmlspecialchars($row['company_image']); ?>" alt="Company Image" class="company-image">
                    
                    <!-- Job details -->
                    <div class="job-details">
                        <h2><?php echo htmlspecialchars($row['job_title']); ?></h2>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <p><strong>Company:</strong> <?php echo htmlspecialchars($row['company_name']); ?></p>
                        <p><strong>Vacancy:</strong> <?php echo htmlspecialchars($row['vacancy']); ?></p>
                        <p><strong>Salary:</strong> <?php echo htmlspecialchars($row['salary']); ?></p>
                        
                        <!-- Button for job details -->
                        <span class="apply-button">
                            <a href="<?php echo APP_URL ; ?>actions/details.php?id=<?php echo $row['id']; ?>">View Details</a>
                        </span>
                    </div>
                </li>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No results found for this category.</p>
        <?php endif; ?>
    </ul>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>

<?php
include('..\includes/footer.html');
?>
