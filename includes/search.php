<?php 
$keyword = 'web';

// Start the session to work with cookies
//session_start();

// Check for a POST request or GET request to retrieve the keyword
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $keyword = isset($_POST['search_keyword']) ? trim($_POST['search_keyword']) : '';
    // Set a cookie for the last searched keyword
    setcookie("last_keyword", $keyword, time() + (86400 * 30), "/"); // Expires in 30 days
    
    // Fetch current keywords from cookies
    $keywords = isset($_COOKIE['keywords']) ? explode(',', $_COOKIE['keywords']) : [];

    // Add the new keyword if it's not already in the cookie
    if (!in_array($keyword, $keywords)) {
        $keywords[] = $keyword; // Add the new keyword
        // Store updated keywords in a cookie
        setcookie("keywords", implode(',', $keywords), time() + (86400 * 30), "/"); // Expires in 30 days
    }

} elseif (isset($_GET['search_keyword'])) {
    $keyword = trim($_GET['search_keyword']);
}

// Check if the keyword is provided and not empty
if (!empty($keyword)) {
    // Database operations (existing logic)
    // Check if keyword already exists
    $checkQuery = "SELECT * FROM words WHERE keyword = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $keyword);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If keyword exists, update its frequency
        $updateQuery = "UPDATE words SET frequency = frequency + 1 WHERE keyword = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("s", $keyword);
        $stmt->execute();
    } else {
        // If keyword doesn't exist, insert a new record
        $insertQuery = "INSERT INTO words (keyword, frequency) VALUES (?, 1)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("s", $keyword);
        $stmt->execute();
    }

    // Now search jobs based on the keyword
    $sql = "SELECT id, job_title, description, vacancy, salary, company_name, company_image 
            FROM jobs 
            WHERE job_title LIKE ? OR company_name LIKE ? OR description LIKE ?";
    $likeKeyword = "%$keyword%";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $likeKeyword, $likeKeyword, $likeKeyword);
    $stmt->execute();
    $jobsResult = $stmt->get_result();
}
?>

<form method="post" class="search-jobs-form">
    <center>
        <div class="row mb-5">
            <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
                <input type="text" class="form-control form-control-lg" placeholder="Job title, Company..." name="search_keyword" required>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
                <select class="selectpicker" data-style="btn-white btn-lg" data-width="100%" data-live-search="true" title="Select Region">
                    <option>Anywhere</option>
                    <option>Mumbai</option>
                    <option>Pune</option>
                    <option>Nagpur</option>
                    <option>Nashik</option>
                    <option>Thane</option>
                    <option>Aurangabad</option>
                    <option>Solapur</option>
                    <option>Kolhapur</option>
                    <option>Amravati</option>
                    <option>Sangli</option>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
                <select class="selectpicker" data-style="btn-white btn-lg" data-width="100%" data-live-search="true" title="Select Job Type">
                    <option>Part Time</option>
                    <option>Full Time</option>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
                <button type="submit" class="btn btn-primary btn-lg btn-block text-white btn-search">
                    <span class="icon-search icon mr-2"></span>Search Job
                </button>
            </div>
        </div>
    </center>
</form>

<!-- Display Trending Keywords -->
<div class="row">
    <div class="col-md-12 popular-keywords">
        <h3>Trending Keywords:</h3>
        <ul class="keywords list-unstyled m-0 p-0">
            <?php
            // Fetch top 5 trending keywords
            $trendingQuery = "SELECT keyword FROM words ORDER BY frequency DESC LIMIT 5";
            $trendingResult = $conn->query($trendingQuery);

            while ($row = $trendingResult->fetch_assoc()) {
                echo '<li><a href="?search_keyword=' . urlencode($row['keyword']) . '">' . htmlspecialchars($row['keyword']) . '</a></li>';
            }
            ?>
        </ul>
    </div>
</div>

<!-- Display Last Keyword -->
<div>
    <?php if (isset($_COOKIE['last_keyword'])): ?>
        <h3>Last Searched Keyword: <?php echo htmlspecialchars($_COOKIE['last_keyword']); ?></h3>
    <?php endif; ?>
</div>

<!-- Display Stored Keywords -->
<div>
    <?php if (isset($_COOKIE['keywords'])): ?>
        <h3>Stored Keywords:</h3>
        <ul>
            <?php
            $storedKeywords = explode(',', $_COOKIE['keywords']);
            foreach ($storedKeywords as $storedKeyword) {
                echo '<li>' . htmlspecialchars($storedKeyword) . '</li>';
            }
            ?>
        </ul>
    <?php endif; ?>
</div>
