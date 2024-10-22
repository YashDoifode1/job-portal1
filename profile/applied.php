
<?php
//session_start();
require "..\includes/header.php";
require "..\includes/config.php";

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    die("You must be logged in to view applied jobs.");
}

// Get the logged-in user's ID
$user_id = $_SESSION['username'];

// Fetch applied jobs for the logged-in user
$sql = "SELECT jobs.*, applied.applied_at 
        FROM applied 
        JOIN jobs ON applied.job_id = jobs.id 
        WHERE applied.user_id = ?";
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
    <title>Applied Jobs</title>
    <!-- <link rel="stylesheet" href="css/applied_jobs.css"> -->
      <style>
      
      /* body {
    font-family: Arial, sans-serif;
    background-color: #f8f8f8;
    margin: 0;
    padding: 0;
} */

.container5 {
    
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

table {
    width: 100%;
    border-collapse: collapse;
}

table thead tr {
    background-color: #4CAF50;
    color: white;
    text-align: left;
}

table th, table td {
    padding: 12px 15px;
    border: 1px solid #ddd;
}

table tbody tr:nth-child(even) {
    background-color: #f2f2f2;
}

table tbody tr:hover {
    background-color: #ddd;
}

.company-image {
    width: 50px;
    height: 50px;
    border-radius: 50%;
}

.details-link {
    display: inline-block;
    padding: 8px 12px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 4px;
}

.details-link:hover {
    background-color: #45a049;
}
</style>
</head>
<body>
    <div class="container5">
        <h2>Applied Jobs</h2>
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
                        <th>Applied At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><img src="<?php echo APP_URL; ?><?php echo htmlspecialchars($row['company_image']); ?>" alt="Company Image" class="company-image"></td>
                            <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                            <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['vacancy']); ?></td>
                            <td><?php echo htmlspecialchars($row['salary']); ?></td>
                            <td><?php echo htmlspecialchars($row['applied_at']); ?></td>
                            <td><a href="<?php echo APP_URL; ?>actions/details.php?id=<?php echo $row['id']; ?>" class="details-link">View Details</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No jobs applied yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php require "..\includes/footer.html"; ?>
