<?php
//session_start();
require "..\includes/config.php"; // Include your database configuration file
require "..\includes/header.php";

// Check if a company ID is passed in the URL
if (!isset($_GET['id'])) {
    $error = "No company selected.";
} else {
    // Fetch company details from the database using the provided ID
    $company_id = $_GET['id'];

    $sql = "SELECT * FROM users WHERE id = ? AND usertype = 'company'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc(); // Fetch company details
    } else {
        $error = "Company not found.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($row) ? htmlspecialchars($row['username']) : 'Company Profile'; ?></title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
    <div class="container3">
        <h1>Company Profile</h1>
        <?php if (isset($error)): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php else: ?>
            <div class="profile-header">
                <?php if (!empty($row['image'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Profile Image" class="profile-image">
                <?php else: ?>
                    <img src="icons/default.png" alt="Default Profile Image" class="profile-image">
                <?php endif; ?>
                <h2><?php echo htmlspecialchars($row['username']); ?></h2>
            </div>
            <p><?php echo htmlspecialchars($row['Bio']); ?></p>
            <center>
                <div class="social-links">
                    <?php if (!empty($row['facebook'])): ?>
                        <a href="<?php echo htmlspecialchars($row['facebook']); ?>" target="_blank" class="social-icon">
                            <img src="icons/2.png" alt="Facebook" title="Facebook">
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($row['instagram'])): ?>
                        <a href="<?php echo htmlspecialchars($row['instagram']); ?>" target="_blank" class="social-icon">
                            <img src="icons/4.png" alt="Instagram" title="Instagram">
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($row['linkedin'])): ?>
                        <a href="<?php echo htmlspecialchars($row['linkedin']); ?>" target="_blank" class="social-icon">
                            <img src="icons/4.png" alt="LinkedIn" title="LinkedIn">
                        </a>
                    <?php endif; ?>
                </div>
            </center>
        <?php endif; ?>
    </div><br>
</body>
</html>
<?php require "..\includes/footer.html"; ?>
