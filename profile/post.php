
<?php
include('..\includes/header.php');
include('..\includes/config.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_title = $_POST['job_title'];

    $category_id = $_POST['category'];

    $location = $_POST['location'];  // Renamed 'state' to 'location'
    $region = $_POST['region'];
    $job_time = $_POST['job_time'];
    $experience = $_POST['experience'];
    $salary = $_POST['salary'];
    $responsibility = $_POST['responsibility'];
    $education_required = $_POST['education_required'];
    $company_name = $_POST['company_name'];
    $company_email = $_POST['company_email'];
    // $company_id = $_POST['company_id'];
    $company_tagline = $_POST['company_tagline'];  // New field
    $company_website = $_POST['company_website'];  // New field
    $company_fb = $_POST['company_website_fb'];    // New field
    $company_tw = $_POST['company_website_tw'];    // New field
    $company_linkedin = $_POST['company_website_linkedin']; // New field

    // Handling the file upload
    $target_dir = "..\jobs/";
    // $company_image = $target_dir . basename($_FILES["company_image"]["name"]);
    $company_image = basename($_FILES["company_image"]["name"]);


    move_uploaded_file($_FILES["company_image"]["tmp_name"], $company_image);

    $sql = "INSERT INTO jobs (job_title, category_id, location, region, job_time, experience, salary, responsibility, education_required, company_name, company_email, company_id, company_tagline, company_website, company_fb, company_tw, company_linkedin, company_image)
            VALUES ('$job_title','$category_id' ,'$location', '$region', '$job_time', '$experience', '$salary', '$responsibility', '$education_required', '$company_name', '$company_email', '$company_tagline', '$company_website', '$company_fb', '$company_tw', '$company_linkedin', '$company_image')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Record updated successfully');</script>";
        header("Location: " . APP_URL . "index.php");
        exit();
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Post Form</title>
    <link rel="stylesheet" href="..\css/job.css">
</head>
<body>

<section class="site-section">
    <div class="container">

        <div class="row align-items-center mb-5">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="d-flex align-items-center">
                    <div>
                        <h2>Post A Job</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-lg-12">
                <form class="p-4 p-md-5 border rounded" action="post.php" method="POST" enctype="multipart/form-data">
                    <h3 class="text-black mb-5 border-bottom pb-2">Job Details</h3>
                    
                    <div class="form-group">
                        <label for="company-image d-block">Upload Featured Image</label> <br>
                        <label class="btn btn-primary btn-md btn-file">
                            Browse File<input type="file" id="company_image" name="company_image" hidden>
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" id="company_email" name="company_email" value="<?php echo $_SESSION['email'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="job-title">Job Title</label>
                        <input type="text" class="form-control" id="job_title" name="job_title" required>
                    </div>

                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>

                    <div class="form-group">
                        <label for="job-region">Job Region</label>
                        <select class="selectpicker border rounded" id="region" name="region" data-style="btn-black" data-width="100%" data-live-search="true" title="Select Region">
                            <option value="Mumbai">Mumbai</option>
                            <option value="Pune">Pune</option>
                            <option value="Nagpur">Nagpur</option>
                            <option value="Nashik">Nashik</option>
                            <option value="Aurangabad">Aurangabad</option>
                        </select>
                    </div>

                    <div class="form-group"> 
    <label for="job-region">Job Category</label>
    <select class="selectpicker border rounded" id="category" name="category" data-style="btn-black" data-width="100%" data-live-search="true" title="Select Category">
        <?php
        // Assuming you have a database connection established
        $query = "SELECT id, name FROM category";
        $result = mysqli_query($conn, $query); // Replace $conn with your actual DB connection variable

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
            }
        } else {
            echo "<option value=''>No categories found</option>";
        }
        ?>
    </select>
</div>

                    <div class="form-group">
                        <label for="job-type">Job Time</label>
                        <select class="selectpicker border rounded" id="job_time" name="job_time" data-style="btn-black" data-width="100%" data-live-search="true" title="Select Job Type">
                            <option value="Morning">Morning</option>
                            <option value="Night">Night</option>
                            <option value="Part Time">Part Time</option>
                            <option value="Freelancer">Freelancer</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="experience">Experience</label>
                        <input type="text" class="form-control" id="experience" name="experience" required>
                    </div>

                    <div class="form-group">
                        <label for="salary">Salary</label>
                        <select class="selectpicker border rounded" id="salary" name="salary" data-style="btn-black" data-width="100%" data-live-search="true" title="Select Salary">
                            <option value="10k">10k</option>
                            <option value="20k">20k</option>
                            <option value="30k">30k</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="responsibility">Responsibility</label>
                        <textarea class="form-control" id="responsibility" name="responsibility" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="education_required">Education Required</label>
                        <input type="text" class="form-control" id="education_required" name="education_required" required>
                    </div>

                    <h3 class="text-black my-5 border-bottom pb-2">Company Details</h3>
                    
                    <div class="form-group">
                        <label for="company-name">Company Name</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo $_SESSION['username'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="company-tagline">Tagline (Optional)</label>
                        <input type="text" class="form-control" id="company_tagline" name="company_tagline">
                    </div>

                    <div class="form-group">
                        <label for="job-description">Company Description (Optional)</label>
                        <div class="editor" id="editor-2">
                            <p>Description</p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="company-website">Website (Optional)</label>
                        <input type="text" class="form-control" id="company_website" name="company_website" placeholder="https://">
                    </div>

                    <div class="form-group">
                        <label for="company-website-fb">Facebook Username (Optional)</label>
                        <input type="text" class="form-control" id="company_website_fb" name="company_website_fb" placeholder="companyname">
                    </div>

                    <div class="form-group">
                        <label for="company-website-tw">Twitter Username (Optional)</label>
                        <input type="text" class="form-control" id="company_website_tw" name="company_website_tw" placeholder="@companyname">
                    </div>

                    <div class="form-group">
                        <label for="company-website-linkedin">LinkedIn Username (Optional)</label>
                        <input type="text" class="form-control" id="company_website_linkedin" name="company_website_linkedin" placeholder="companyname">
                    </div>

                    <div class="form-group">
                        <label for="company-image d-block">Upload Logo</label> <br>
                        <label class="btn btn-primary btn-md btn-file">
                            Browse File<input type="file" id="company_image" name="company_image" hidden>
                        </label>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Post Job">
                </form>
            </div>
        </div>
    </div>
</section>

<?php include('..\includes/footer.html'); ?>
</body>
</html>
