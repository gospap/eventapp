<?php
$host = "localhost";
$username = "root";
$pass = "";
$dbname = "businessdb";

$conn = mysqli_connect($host, $username, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
