<?php
$host = "localhost";
$user = "root";
$password = "";
$sql_db = "cai_db";

$conn = new mysqli($host, $user, $password, $sql_db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$result = $conn->query("SELECT image FROM jobs");
?>