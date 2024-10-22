# Job Portal Installation Guide

## Introduction
This Job Portal is designed to offer a seamless platform for managing job listings and applications. Follow the steps below to set it up on your local XAMPP server.

## Installation Steps

### 1. Download and Extract
- Clone or download the project files from this repository.
- Extract the files and rename the folder to `job`.

### 2. Move to XAMPP Directory
Copy the entire `job` folder to the `htdocs` directory of your XAMPP installation:
C:\xampp\htdocs\job


### 3. Database Setup
1. Start `Apache` and `MySQL` from the XAMPP Control Panel.
2. Open your browser and navigate to [phpMyAdmin](http://localhost/phpmyadmin/).
3. Create a new database (e.g., `job_portal`).
4. Import the provided SQL file into this database.

### 4. Configure Application URL
1. Open the `header.php` file located in the project directory.
2. Find the following line:
    ```php
    define('APP_URL', 'http://localhost/job/');
    ```
3. Update the URL to match your XAMPP setup if needed, then save the changes.

### 5. Access the Job Portal
Open your browser and go to:
http://localhost/job

### 6. You're Ready!
The Job Portal is now set up on your local server. You can begin exploring its features.

## Notes
- Ensure that XAMPP is running Apache and MySQL services before accessing the portal.
- For any issues, feel free to check out the [Issues](https://github.com/YashDoifode1/codex_sih/issues) section or raise a new one.

## License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
