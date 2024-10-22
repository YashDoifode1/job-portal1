<?php
//session_start();
require "..\includes/header.php";
require "..\includes/config.php";

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    die("You must be logged in to view your posted jobs.");
}

// Get the logged-in user's username
$username = $_SESSION['username'];

// Fetch jobs posted by the logged-in user
$sql = "SELECT * FROM jobs WHERE company_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username); // 's' for string type
$stmt->execute();
$result = $stmt->get_result();

// Close statement and connection
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posted Jobs</title>
    <link rel="stylesheet" href="..\css/posted.css">
</head>
<body>
    <div class="container6">
        <h2>Posted Jobs</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Company Image</th>
                        <th>Job Title</th>
                        <th>Company Name</th>
                        <th>Description</th>
                        <th>Vacancy</th>
                        <th>Salary</th>
                        <th>Applications</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><img src="<?php echo APP_URL;?><?php echo htmlspecialchars($row['company_image']); ?>" alt="Company Image" class="company-image"></td>
                            <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                            <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['vacancy']); ?></td>
                            <td><?php echo htmlspecialchars($row['salary']); ?></td>
                            <td>N/A</td>
                            <td><a href="<?php echo APP_URL; ?>applications/recruter.php?id=<?php echo $row['id']; ?>" class="details-link">View Details</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No jobs posted yet.</p>
        <?php endif; ?>
    </div>
</body><br><br><br><br><br>
</html>

<?php require "..\includes/footer.html"; ?>
