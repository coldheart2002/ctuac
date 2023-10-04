<?php
session_start(); // Start the session for storing login status

// Predefined credentials
$valid_adminusername = "admin";
$valid_adminpassword = "password"; // Set a secure admin password
$valid_studentusername = "ctuac";
$valid_studentpassword = ""; // Set a secure student password

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($username === $valid_adminusername && $password === $valid_adminpassword) {
        // Admin authentication successful
        $_SESSION["username"] = $valid_adminusername;
        header("Location: admin/admin_display_data.php");
        exit();
    } else if ($username === $valid_studentusername && $password === $valid_studentpassword) {
        // Student authentication successful
        $_SESSION["username"] = $valid_studentusername;
        header("Location: others/others_display_data.php");
        exit();
    } else {
        // Authentication failed
        echo "Invalid username or password. Please try again.";
    }
}
?>
