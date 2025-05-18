<?php
    // Just used to test database. Can be used as template.
    // Romove by end of project.
    require_once "settings.php";
    
    $query = 'SELECT * FROM test_table';
    $result = mysqli_query($dbconn, $query);
    if ($result)
    {
        while ($row = $result->fetch_assoc())
        {
            echo $row['user_id'] . $row["a"] . $row["b"] . $row["c"] . "<br>";
        }
    }
    else
    {
        echo 'brrrrrr error :)';
    }

    $conn->close();
?>