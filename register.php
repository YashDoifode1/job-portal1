<?php
include('includes/header.php');

// Database connection
$host = 'localhost';
$dbname = 'job';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle registration
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $type = $_POST['type'];
    $image = $_FILES['image']['name'];

    // File upload path
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, usertype, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $user, $email, $pass, $type, $image);

    if ($stmt->execute()) {
        header('Location: login.php');
        exit();
    } else {
        $error = "Registration failed. Please try again.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UniBlend Register</title>
    <link rel="stylesheet" href="style/login.css" />
    <!-- <script src="https://kit.fontawesome.com/a076d05399.js"></script> -->
</head>
<body>
    <div class="wrapper">
        <div class="title"><span>Register</span></div>
        <form method="POST" action="register.php" enctype="multipart/form-data">
            <div class="row">
                <i class="fas fa-user"></i>
                <input type="text" id="username" name="username" placeholder="Username" required />
            </div>
            <div class="row">
                <i class="fas fa-envelope"></i>
                <input type="email" id="email" name="email" placeholder="Email" required />
            </div>
            <div class="row">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="Password" required />
            </div>
            <!-- User Type Heading -->
            <div class="row">
                <i class="fas fa-lock"></i>
                <input type="text" id="type" name="type" placeholder="Worker / Organisation / Student/" required />
            </div>

            <!-- drope down  -->
            <!-- <div class="row">
                <i class="fas fa-user-tag"></i>
                <select id="type" name="type" required>
                    <option value="">Select Type</option>
                    <option value="Worker">Worker</option>
                    <option value="Organisation">Organisation</option>
                    <option value="Student">Student</option>
                </select>
            </div> -->
            <div class="row">
                <i class="fas fa-image"></i>
                <input type="file" id="image" name="image" required />
            </div>
            <div class="row button">
                <input type="submit" value="Register" />
            </div>
            <div class="signup-link">
                Already a member? <a href="login.php">Login here</a>
            </div>
        </form>
        <?php if (isset($error)) { echo "<div class='error'>$error</div>"; } ?>
    </div>
</body>
</html>
<?php include('includes/footer.html'); ?>
