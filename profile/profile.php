<?php 

?>
<?php
require "..\includes/header.php";
require "..\includes/config.php";
// Determine if fetching data for the logged-in user or a specific user by GET parameter
$user = isset($_SESSION['username']) ? $_SESSION['username'] : (isset($_GET['id']) ? $_GET['id'] : null);

if ($user) {
    $sql = "SELECT title, bio, facebook, instagram, linkedin, image FROM users WHERE username='$user'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $error = "No user found.";
    }
} else {
    $error = "No user specified.";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User </title>
    <link rel="stylesheet" href="..\css/profile.css">
</head>
<body>
    <div class="container3">
        <h1>Profile</h1>
        <?php if (isset($error)): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php else: ?>
            <div class="profile-header">
                <?php if (!empty($row['image'])): ?>
                    <img src="..\uploads/<?php echo $row['image']; ?>" alt="Profile Image" class="profile-image">
                <?php else: ?>
                    <img src="..\icons/default.png" alt="Default Profile Image" class="profile-image">
                <?php endif; ?>
                <h2><?php echo htmlspecialchars($row['title']); ?></h2>
            </div>
            <p><?php echo htmlspecialchars($row['bio']); ?></p>
            <center><div class="social-links">
                <?php if (!empty($row['facebook'])): ?>
                    <a href="..\<?php echo htmlspecialchars($row['facebook']); ?>" target="_blank" class="social-icon">
                        <img src="..\icons/2.png" alt="Facebook" title="Facebook">
                    </a>
                <?php endif; ?>
                <?php if (!empty($row['instagram'])): ?>
                    <a href="<?php echo htmlspecialchars($row['instagram']); ?>" target="_blank" class="social-icon">
                        <img src="..\icons/4.png" alt="Instagram" title="Instagram">
                    </a>
                <?php endif; ?>
                <?php if (!empty($row['linkedin'])): ?>
                    <a href="<?php echo htmlspecialchars($row['linkedin']); ?>" target="_blank" class="social-icon">
                        <img src="..\icons/4.png" alt="LinkedIn" title="LinkedIn">
                    </a>
                <?php endif; ?>
            </div></center>
        <?php endif; ?>
    </div><br>
</body>
</html>
<?php require "..\includes/footer.html"; ?>
