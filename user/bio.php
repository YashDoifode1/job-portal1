

<?php 
require "..\includes/header.php";
require "..\includes/config.php";

// Check if user is logged in
if (isset($_SESSION['username'])) {
    $user = $_SESSION['username'];

    // Fetch user details
    $sql = "SELECT title, bio, facebook, instagram, linkedin, cv FROM users WHERE username='$user'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $row = ['title' => '', 'bio' => '', 'facebook' => '', 'instagram' => '', 'linkedin' => '', 'cv' => ''];
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = $conn->real_escape_string($_POST['title']);
        $bio = $conn->real_escape_string($_POST['bio']);
        $facebook = $conn->real_escape_string($_POST['facebook']);
        $instagram = $conn->real_escape_string($_POST['instagram']);
        $linkedin = $conn->real_escape_string($_POST['linkedin']);

        // Handle file upload
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] == UPLOAD_ERR_OK) {
            $resume_tmp_name = $_FILES['resume']['tmp_name'];
            $resume_name = basename($_FILES['resume']['name']);
            $resume_dir = 'resumes/';
            $resume_path = $resume_dir . $resume_name;

            // Create the directory if it doesn't exist
            if (!is_dir($resume_dir)) {
                mkdir($resume_dir, 0777, true);
            }

            // Move the uploaded file to the server directory
            if (move_uploaded_file($resume_tmp_name, $resume_path)) {
                $cv = $conn->real_escape_string($resume_path);
            } else {
                echo "<script>alert('Failed to upload resume');</script>";
            }
        } else {
            $cv = $row['cv']; // Keep the old value if no new file is uploaded
        }

        $sql = "UPDATE users SET title='$title', bio='$bio', facebook='$facebook', instagram='$instagram', linkedin='$linkedin', cv='$cv' WHERE username='$user'";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Record updated successfully');</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }

    $conn->close();
} else {
    echo "User not logged in";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update bio Information</title>
    <link rel="stylesheet" href="..\css/style.css">
</head>
<body>
    <div class="container2">
        <h1>Update bio Information</h1>
        <form action="bio.php" method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <textarea id="title" name="title" required><?php echo htmlspecialchars($row['title']); ?></textarea><br><br>

            <label for="bio">Bio:</label>
            <textarea id="bio" name="bio" required><?php echo htmlspecialchars($row['bio']); ?></textarea><br><br>

            <label for="facebook">Facebook:</label>
            <input type="url" id="facebook" name="facebook" value="<?php echo htmlspecialchars($row['facebook']); ?>"><br><br>

            <label for="instagram">Instagram:</label>
            <input type="url" id="instagram" name="instagram" value="<?php echo htmlspecialchars($row['instagram']); ?>"><br><br>

            <label for="linkedin">LinkedIn:</label>
            <input type="url" id="linkedin" name="linkedin" value="<?php echo htmlspecialchars($row['linkedin']); ?>"><br><br>

            <label for="resume">Upload Resume:</label>
            <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx"><br><br>

            <input type="submit" value="Update">
        </form>
    </div><br>
</body>
</html>

<?php require "..\includes/footer.html" ; ?>
