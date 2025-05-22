<?php
    if ($_SERVER['REQUEST_METHOD'] != 'POST' || empty($_POST))
    {
        header("Location: apply.php");
        exit();
    }

    include 'settings.php';

    // For testing... remove later--------------------------------------------
    echo "<h2>POST Data Received:</h2>";
    echo "<ul>";
    
    foreach ($_POST as $key => $value)
    {
        if (is_array($value)) {
            echo "<li><strong>" . htmlspecialchars($key) . "</strong>: ";
            echo implode(", ", array_map('htmlspecialchars', $value));
            echo "</li>";
        } else {
            echo "<li><strong>" . htmlspecialchars($key) . "</strong>: " . htmlspecialchars($value) . "</li>";
        }
    }

    echo "</ul>";
    //-----------------------------------------------------------------------
    
    //add table if its not exisitng
    $sql = "CREATE TABLE IF NOT EXISTS `eoi` (
  `EOInumber` int(11) NOT NULL,
  `job_reference` varchar(5) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('female','male','other') NOT NULL,
  `street_address` varchar(40) NOT NULL,
  `suburb` varchar(40) NOT NULL,
  `state` enum('ACT','NSW','NT','QLD','SA','TAS','VIC','WA') NOT NULL,
  `postcode` char(4) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `technical_support` tinyint(1) NOT NULL DEFAULT 0,
  `system_administration` tinyint(1) NOT NULL DEFAULT 0,
  `problem_solving_&_communication` tinyint(1) NOT NULL DEFAULT 0,
  `other_skills` text DEFAULT NULL,
  `status` enum('New','Current','Final') DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
    mysqli_query($conn, $sql);
    

    // Sanitize data
    //
    function SanitizeInput ($data) { return htmlspecialchars(stripslashes(trim($data))); }
    $sanitizedPost = [];
    foreach ($_POST as $key => $value)
    {
        if (is_array($value))
        {
            // ChatGPT prompt: How can I apply a funtion to every item in a array? - answer was array_map()
            $sanitizedPost[$key] = array_map('SanitizeInput', $value);
        }
        else
        {
            $sanitizedPost[$key] = SanitizeInput($value);
        }
    }

    // Get POST data
    $jobRef = $sanitizedPost['JobReference'];
    $firstName = $sanitizedPost['FirstName'];
    $lastName = $sanitizedPost['LastName'];
    $dob = $sanitizedPost['dob'];
    $gender = $sanitizedPost['Gender'];
    $streetAddress = $sanitizedPost['StreetAddress'];
    $suburbTown = $sanitizedPost['SuburbTown'];
    $state = $sanitizedPost['State'];
    $postcode = $sanitizedPost['PostCode'];
    $email = $sanitizedPost['EmailAddress'];
    $phone = $sanitizedPost['PhoneNumber'];
    $skills = implode(', ', $sanitizedPost['skills']);
    $comments = $sanitizedPost['comments'];

    // Validate data
    // When testing: put novalidate=”novalidate” attribute into form
    // Check if job reference number is valid
    $count = mysqli_query($conn, 'SELECT COUNT(*) FROM jobs WHERE ref_id = ' . $jobRef);
    if ($count == 0) { echo 'No :('; }
    // Check name length
    if (strlen($firstName) <= 0 || strlen($firstName) > 20) { echo 'No :('; }
    if (strlen($lastName) <= 0 || strlen($lastName) > 20) { echo 'No :('; }
    // Check dob format (dd/mm/yyyy) Modified from ChatGPT, prompt: How do I make sure a string has dd/mm/yyyy format?
    if (preg_match('/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/', $dob) !== 1) {{ echo 'No :('; }}
    // Check address length
    if (strlen($streetAddress) <= 0 || strlen($streetAddress) > 40) { echo 'No :('; }
    if (strlen($suburbTown) <= 0 || strlen($suburbTown) > 40) { echo 'No :('; }
    // Check state
    if (!in_array($state, array('VIC', 'NSW', 'QLD', 'NT', 'WA', 'SA', 'TAS', 'ACT'))) { echo 'No :('; }
    // Check postcode length
    if (strlen($postcode) != 4) { echo 'No :('; }
    // Check email format
    if (preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email) !== 1) {{ echo 'No :('; }}
    // Check phone number length
    $phoneNumLen = strlen(str_replace(' ', '', $phone));
    if ($phoneNumLen < 8 || $phoneNumLen > 12) { echo 'No :('; }

    // create eoi reference number
    // https://www.geeksforgeeks.org/format-a-number-with-leading-zeros-in-php/
    $eoiNumber = '';
    $isUnique = false;
    while (!$isUnique)
    {
        $eoiNumber = sprintf('%010d', rand(0, 9999999999));
        $count = mysqli_query($conn, 'SELECT COUNT(*) FROM eoi WHERE eoiNumber = ' . $eoiNumber);
        if ($count == 0) { $isUnique = true; }
    }

    // Insert into database
    // Change variables to suit table
    $query = "INSERT INTO eoi ()
        VALUES ('$eoiNumber', '$jobRef', '$firstName', '$lastName', '$dob', '$gender', '$streetAddress', '$suburbTown', '$state', '$postcode', '$email', '$phone', '$skills', '$comments')";
    $result = mysqli_query($conn, $query);
    if ($result)
    {
        // Go to success webpage with eoiNumber displayed and link to home
        echo 'Yay!';
    }
    else
    {
        // Go to user friendly error page
        echo 'No :(';
    }
    $conn->close();
?>