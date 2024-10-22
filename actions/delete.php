<?php
require "..\includes/config.php";
require "..\includes/header.php";

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    die("You must be logged in to delete users.");
}

// Check if the 'id' parameter is set
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;

    // Prepare and execute the delete statement
    $sql_delete = "DELETE FROM applied WHERE user_id = ? AND job_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    if (!$stmt_delete) {
        die("Error preparing delete statement: " . $conn->error);
    }
    $stmt_delete->bind_param("ii", $user_id, $job_id);
    if ($stmt_delete->execute()) {
        // Successful deletion
        echo "<script>window.location.href = '" . APP_URL . "applications/recruter.php?id=" . $job_id . "';</script>";
        exit(); // Stop further script execution
    } else {
        // Error occurred
        die("Error deleting user: " . $stmt_delete->error);
    }
    $stmt_delete->close();
} else {
    die("Invalid request.");
}

$conn->close();
?>
