<?php
// db.php
$host = "localhost";
$user = "root"; // database username
$pass = ""; // database password
$dbname = "website_db";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
