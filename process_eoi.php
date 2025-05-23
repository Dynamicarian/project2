<?php
    if ($_SERVER['REQUEST_METHOD'] != 'POST' || empty($_POST))
    {
        header("Location: apply.php");
        exit();
    }

    include 'settings.php';
    
    //add table if its not exisitng
    $query = "CREATE TABLE IF NOT EXISTS `eoi` (
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
    $stmt = $conn->prepare($query);
    $stmt->execute();
    

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
    $skills = array(in_array('Technical Support', $sanitizedPost['skills']),
                    in_array('System Aministration', $sanitizedPost['skills']),
                    in_array('Problem-Solving & Communication', $sanitizedPost['skills']));
    $comments = $sanitizedPost['comments'];

    // Validate data
    // When testing: put novalidate=”novalidate” attribute into form
    // Check if job reference number is valid
    $result = mysqli_query($conn, "SELECT * FROM jobs WHERE ref_id = '" . $jobRef . "'");
    $eoi = mysqli_fetch_assoc($result);
    if (!$eoi) { echo '1No :('; }
    // Check name length
    if (strlen($firstName) <= 0 || strlen($firstName) > 20)
    {
        header("Location: error_page.php");
        exit();
    }
    if (strlen($lastName) <= 0 || strlen($lastName) > 20)
    {
        header("Location: error_page.php");
        exit();
    }
    // Check dob format (dd/mm/yyyy) Modified from ChatGPT, prompt: How do I make sure a string has yyyy-mm-dd format?
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob) !== 1)
    {
        header("Location: error_page.php");
        exit();
    }
    // Check address length
    if (strlen($streetAddress) <= 0 || strlen($streetAddress) > 40)
    {
        header("Location: error_page.php");
        exit();
    }
    if (strlen($suburbTown) <= 0 || strlen($suburbTown) > 40)
    {
        header("Location: error_page.php");
        exit();
    }
    // Check state
    if (!in_array($state, array('VIC', 'NSW', 'QLD', 'NT', 'WA', 'SA', 'TAS', 'ACT')))
    {
        header("Location: error_page.php");
        exit();
    }
    // Check postcode length
    if (strlen($postcode) != 4)
    {
        header("Location: error_page.php");
        exit();
    }
    // Check email format
    if (preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email) !== 1)
    {
        header("Location: error_page.php");
        exit();
    }
    // Check phone number length
    $phoneNumLen = strlen(str_replace(' ', '', $phone));
    if ($phoneNumLen < 8 || $phoneNumLen > 12)
    {
        header("Location: error_page.php");
        exit();
    }

    // create eoi reference number
    // https://www.geeksforgeeks.org/format-a-number-with-leading-zeros-in-php/
    $eoiNumber = '';
    $isUnique = false;
    while (!$isUnique)
    {
        $eoiNumber = rand(0, 9999999999);
        $stmt = $conn->prepare('SELECT * FROM eoi WHERE eoiNumber = ?');
        $stmt->bind_param("i", $eoiNumber);
        $stmt->execute();
        $result = $stmt->get_result();
        $eoi = mysqli_fetch_assoc($result);
        if (!$eoi) { $isUnique = true; }
    }
    // Insert into database
    // Change variables to suit table
    $query = "INSERT INTO eoi (EOInumber, job_reference, first_name, last_name, date_of_birth, gender, street_address, suburb, `state`, postcode, email, phone, technical_support, system_administration, problem_solving_and_communication, other_skills, `status`)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $skills0 = (int)$skills[0]; $skills1 = (int)$skills[1]; $skills2 = (int)$skills[2]; $status = 'New';
    $stmt->bind_param("isssssssssssiiiss", $eoiNumber, $jobRef, $firstName, $lastName, $dob, $gender, $streetAddress, $suburbTown, $state, $postcode, $email, $phone, $skills0, $skills1, $skills2, $comments, $status);
    $result = $stmt->execute();
    if ($result)
    {
        // Go to success webpage with eoiNumber displayed and link to home
        session_start();
        $_SESSION['eoiNumber'] = $eoiNumber;
        header("Location: confirmation_page.php");
        exit();
    }
    else
    {
        // Go to user friendly error page
        header("Location: error_page.php");
        exit();
    }
    $conn->close();
?>