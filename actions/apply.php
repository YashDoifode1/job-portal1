<?php 
//session_start();
require '..\includes/config.php';
require '..\includes/header.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    die("You must be logged in to apply for a job.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['id'];
    $username = $_SESSION['username'];
    $cv = $_SESSION['cv'];
    $email = $_SESSION['email'];
    $job_id = $_POST['job_id'];

    // Insert into applied table
    $stmt = $conn->prepare("INSERT INTO applied (user_id, username, cv, email, job_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $user_id, $username, $cv, $email, $job_id);

    if ($stmt->execute()) {
        echo "<script>alert('Applied successfully');</script>";
        echo "<script>window.location.href = '" . APP_URL . "index.php';</script>";
        exit(); // Stop further script execution
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
