<?php
//session_start(); // Start the session

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    // Redirect to the dashboard or another page
    header('Location: index.php');
    exit;
}

include('includes/header.php');
include('includes/config.php');

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error); // Debugging: Check if statement preparation failed
    }
    $stmt->bind_param("ss", $user, $pass);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error); // Debugging: Check if statement execution failed
    }

    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['usertype'] = $row['usertype'];
        $_SESSION['cv'] = $row['cv'];
        
        header('Location: index.php');
        exit();
    } else {
        $error = "Invalid username or password";
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
    <title>UniBlend Login</title>
    <link rel="stylesheet" href="style/login.css" />
    <!-- <script src="https://kit.fontawesome.com/a076d05399.js"></script> -->
</head>
<body>
    <div class="wrapper">
        <div class="title"><span>Login Form</span></div>
        <form method="POST" action="login.php">
            <div class="row">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Username" required />
            </div>
            <div class="row">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required />
            </div>
            <div class="pass"><a href="#">Forgot password?</a></div>
            <div class="row button">
                <input type="submit" value="Login" />
            </div>
            <div class="signup-link">Not a member? <a href="register.php">Signup now</a></div>
        </form>
        <?php if (isset($error)) { echo "<div class='error'>$error</div>"; } ?>
    </div>
</body>
</html>

<?php include('includes/footer.html'); ?>
