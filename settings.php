<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $sql_db = "cai_db";

    $dbconn = @mysqli_connect($host, $user, $password, $sql_db);
    if (!$dbconn) { die("Connection failed: " . mysqli_connect_error()); }
?>