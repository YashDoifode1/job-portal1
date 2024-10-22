<?php
session_start();
require "..\includes/config.php";
define('APP_URL', 'http://localhost/job/job-portal/');

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    die("You must be logged in to delete a saved job.");
}

$user_id = $_SESSION['id'];

// Check if job_id is provided via GET
if (isset($_GET['id'])) {
    $job_id = $_GET['id'];
} else {
    // Handle the case where job_id is not provided
    die("Error: job_id is not provided.");
}

// Validate that the job_id is a positive integer
if ($job_id > 0) {
    // Prepare and execute the deletion query
    $stmt = $conn->prepare("DELETE FROM saved_job WHERE user_id = ? AND job_id = ?");
    $stmt->bind_param("ii", $user_id, $job_id);

    if ($stmt->execute()) {
        // Redirect back to the saved jobs page with a success message
        header("Location: " . APP_URL . "profile/saved.php?message=Job+deleted+successfully");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    die("Invalid job ID.");
}

$conn->close();
?>
