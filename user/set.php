<?php
require "..\includes/header.php";
// Start session
//session_start();

// Assuming you have a database connection established already
$connection = mysqli_connect("localhost", "root", "", "job");

// Check if user is logged in
if(!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// Fetch user data based on session ID
$id = $_SESSION['id'];
$query = "SELECT email, username FROM users WHERE id = '$id'";
$result = mysqli_query($connection, $query);

// Check if user data is fetched
if(mysqli_num_rows($result) > 0) {
    $userData = mysqli_fetch_assoc($result);
} else {
    // Handle error if user data not found
    echo "User data not found";
    exit;
}

// Handle form submission
if(isset($_POST['update'])) {
    // Retrieve updated data from form
    $newEmail = $_POST['email'];
    $newUsername = $_POST['username'];
    $newPassword = $_POST['password'];

    // Update user data in the database
    $updateQuery = "UPDATE users SET email = '$newEmail', username = '$newUsername', password = '$newPassword' WHERE id = '$id'";
    if(mysqli_query($connection, $updateQuery)) {
        echo "<script>alert('User data updated successfully');</script>";
        // Refresh the page to reflect the updated information
        header("Refresh:0");
        exit;
    } else {
        echo "Error updating user data: " . mysqli_error($connection);
    }
}

// Handle profile image upload

// Handle profile image upload
if(isset($_FILES['profileImage'])) {
    $fileName = $_FILES['profileImage']['name'];
    $fileTmpName = $_FILES['profileImage']['tmp_name'];
    $fileSize = $_FILES['profileImage']['size'];
    $fileError = $_FILES['profileImage']['error'];

    // Check if file was uploaded without errors
    if($fileError === 0) {
        // Move uploaded file to a permanent location
        $fileDestination = 'uploads/' . $fileName;
        move_uploaded_file($fileTmpName, $fileDestination);

        // Update profile image path in the database
        $updateImageQuery = "UPDATE users SET profile_image = '$fileName' WHERE id = '$id'";
        $stmt = mysqli_prepare($connection, $updateImageQuery);
        mysqli_stmt_bind_param($stmt, "si", $fileDestination, $id);
        if(mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Profile image updated successfully');</script>";
            // Refresh the page to reflect the updated information
            header("Refresh:0");
            exit;
        } else {
            echo "Error updating profile image: " . mysqli_error($connection);
        }
    } else {
        echo "Error uploading file";
    }
}

mysqli_close($connection); // Close database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings</title>
    <style>
        /* Embedded CSS */
        body {
            font-family: Arial, sans-serif;
        }
        .profile-form {
            /* width: 300px; */
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .profile-form label {
            display: block;
            margin-bottom: 10px;
        }
        .profile-form input[type="text"],
        .profile-form input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .profile-form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .profile-form input[type="submit"]:hover {
            background-color: #45a049;
        }
        .back-button {
            display: block;
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>
    <p>
        <br>
    <div class="profile-form">
        <h1>Profile Settings</h1>
        <form method="post" enctype="multipart/form-data">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $userData['email']; ?>" required><br>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $userData['username']; ?>" required><br>
            <label for="password">Password:</label>
            <input type="text" id="password" name="password" value="" placeholder="Enter Your Password"> <br>
            <!-- Add file input for image upload -->
            <label for="profileImage">Profile Image:</label>
            <input type="file" id="profileImage" name="profileImage"><br><br>
            <input type="submit" name="update" value="Update">
        </form>
        <a href="<?php echo APP_URL; ?>index.php" class="back-button">Back to Home</a>
    </div>
</body>
</html>
<?php require "..\includes/footer.html" ?>
