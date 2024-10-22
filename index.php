<?php
include('includes/header.php');
include('includes/config.php');

// Handle form submission for searching
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="css/new.css">
    <link rel="stylesheet" href="chatbot/style2.css">
    <style>

        /* Ensure job section appears first */

    </style>
</head>
<body><br><br>

<!-- Search Form -->
<?php
include('includes/search.php'); ?>

<div class="container">
    <!-- Sidebar Section -->
    <div class="sidebar">
    <h2>Job Categories</h2>
    <ul>
        <?php
        $sql = "SELECT * FROM category";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $category_name = htmlspecialchars($row['name']);
                $category_id = htmlspecialchars($row['id']);
                
                echo "<li><a href='" . APP_URL . "extra/category.php?category={$category_id}'>{$category_name}</a></li>";
            }
        } else {
            echo "<li>No categories found</li>";
        }
        ?>
    </ul>
    <br><hr>
    <h2>Companies Available</h2>
<ul>
    <?php
    // Fetch all users where usertype is 'company'
    $sql = "SELECT * FROM users WHERE usertype = 'company'";
    $result = $conn->query($sql);

    // Check if there are any results
    if ($result->num_rows > 0) {
        // Loop through each company
        while ($row = $result->fetch_assoc()) {
            $company_name = htmlspecialchars($row['username']); // Get the company name
            $company_id = htmlspecialchars($row['id']); // Get the company ID
            
            // Display as a list item with a link to company page //echo "<li><a href='" . APP_URL . "extra/company.php?id={$company_id}'>{$company_name}</a></li>";
            echo "<li><a href='#'>{$company_name}</a></li>";

        }
    } else {
        // If no companies found
        echo "<li>No companies found</li>";
    }
    ?>
</ul>

</div>





    <!-- Job Listings Section -->
    <div class="job">
        <div class="job-list">
            <?php if (isset($jobsResult) && $jobsResult->num_rows > 0): ?>
                <?php while ($row = $jobsResult->fetch_assoc()): ?>
                    <div class="job-item">
                        <img src="<?php echo htmlspecialchars($row['company_image']); ?>" alt="Company Image" class="company-image">
                        <div class="job-details">
                            <h2><?php echo htmlspecialchars($row['job_title']); ?></h2>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <p><strong>Company:</strong> <?php echo htmlspecialchars($row['company_name']); ?></p>
                            <p><strong>Vacancy:</strong> <?php echo htmlspecialchars($row['vacancy']); ?></p>
                            <p><strong>Salary:</strong> <?php echo htmlspecialchars($row['salary']); ?></p>
                            <button type="submit" class="apply-button">
                                <a href="<?php echo APP_URL; ?>actions/details.php?id=<?php echo $row['id']; ?>" class="detail-link">View Details</a>
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No results found for "<?php echo htmlspecialchars($keyword); ?>"</p>
            <?php endif; ?>
        </div>
    </div>
</div><br>

<!-- Floating Chat Icon -->

<?php include('includes/chat.php'); ?>
<script src="chatbot/script.js"></script>

<?php include('includes/footer.html'); ?>
</body>
</html>
