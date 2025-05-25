<?php
    if ($_SERVER['REQUEST_METHOD'] != 'POST' || empty($_POST))
    {
        header("Location: apply.php");
        exit();
    }

    include 'settings.php';
    
    // Create EOI table if it doesn't exist (assumes other tables already exist)
    $eoiTableQuery = "CREATE TABLE IF NOT EXISTS `eoi` (
        `eoi_id` int(11) NOT NULL AUTO_INCREMENT,
        `applicant_id` int(11) NOT NULL,
        `ref_id` varchar(10) NOT NULL,
        `status` enum('New','Current','Final') DEFAULT 'New',
        PRIMARY KEY (`eoi_id`),
        KEY `applicant_id` (`applicant_id`),
        KEY `ref_id` (`ref_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
    
    if (!$conn->query($eoiTableQuery)) {
        error_log("Failed to create EOI table: " . $conn->error);
        header("Location: error_page.php");
        exit();
    }
    
    // Check if foreign key constraints exist before adding them
    $checkConstraintsQuery = "SELECT CONSTRAINT_NAME 
                             FROM information_schema.TABLE_CONSTRAINTS 
                             WHERE TABLE_SCHEMA = DATABASE() 
                             AND TABLE_NAME = 'eoi' 
                             AND CONSTRAINT_TYPE = 'FOREIGN KEY'";
    
    $existingConstraints = [];
    $result = $conn->query($checkConstraintsQuery);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $existingConstraints[] = $row['CONSTRAINT_NAME'];
        }
    }
    
    // Add foreign key constraints only if they don't exist
    if (!in_array('eoi_ibfk_1', $existingConstraints)) {
        $constraint1 = "ALTER TABLE `eoi` 
                       ADD CONSTRAINT `eoi_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`) ON DELETE CASCADE";
        $conn->query($constraint1);
    }
    
    if (!in_array('eoi_ibfk_2', $existingConstraints)) {
        $constraint2 = "ALTER TABLE `eoi` 
                       ADD CONSTRAINT `eoi_ibfk_2` FOREIGN KEY (`ref_id`) REFERENCES `jobs` (`ref_id`) ON DELETE CASCADE";
        $conn->query($constraint2);
    }
    
    // Sanitize data
    function SanitizeInput ($data) { 
        return htmlspecialchars(stripslashes(trim($data))); 
    }
    
    $sanitizedPost = [];
    foreach ($_POST as $key => $value)
    {
        if (is_array($value))
        {
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
    $selectedSkills = isset($sanitizedPost['skills']) ? $sanitizedPost['skills'] : [];
    $otherSkills = isset($sanitizedPost['comments']) ? $sanitizedPost['comments'] : '';

    // Debug: Log the selected skills
    error_log("Selected skills: " . print_r($selectedSkills, true));

    // Validate data
    // Check if job reference number is valid
    $stmt = $conn->prepare("SELECT * FROM jobs WHERE ref_id = ?");
    $stmt->bind_param("s", $jobRef);
    $stmt->execute();
    $result = $stmt->get_result();
    $jobExists = $result->fetch_assoc();
    if (!$jobExists)
    {
        $conn->close();
        header("Location: error_page.php");
        exit();
    }

    // Check name length
    if (strlen($firstName) <= 0 || strlen($firstName) > 20)
    {
        $conn->close();
        header("Location: error_page.php");
        exit();
    }
    if (strlen($lastName) <= 0 || strlen($lastName) > 20)
    {
        $conn->close();
        header("Location: error_page.php");
        exit();
    }

    // Check dob format (yyyy-mm-dd)
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob) !== 1)
    {
        $conn->close();
        header("Location: error_page.php");
        exit();
    }

    // Check address length
    if (strlen($streetAddress) <= 0 || strlen($streetAddress) > 40)
    {
        $conn->close();
        header("Location: error_page.php");
        exit();
    }
    if (strlen($suburbTown) <= 0 || strlen($suburbTown) > 40)
    {
        $conn->close();
        header("Location: error_page.php");
        exit();
    }

    // Check state
    if (!in_array($state, array('VIC', 'NSW', 'QLD', 'NT', 'WA', 'SA', 'TAS', 'ACT')))
    {
        $conn->close();
        header("Location: error_page.php");
        exit();
    }

    // Check postcode length
    if (strlen($postcode) != 4)
    {
        $conn->close();
        header("Location: error_page.php");
        exit();
    }

    // Check email format
    if (preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email) !== 1)
    {
        $conn->close();
        header("Location: error_page.php");
        exit();
    }

    // Check phone number length
    $phoneNumLen = strlen(str_replace(' ', '', $phone));
    if ($phoneNumLen < 8 || $phoneNumLen > 12)
    {
        $conn->close();
        header("Location: error_page.php");
        exit();
    }

    // Start transaction for data consistency
    $conn->autocommit(false);
    
    try {
        // Step 1: Insert into applicants table
        $applicantQuery = "INSERT INTO applicants (first_name, last_name, date_of_birth, gender, street_address, suburb, state, postcode, email, phone, other_skills) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($applicantQuery);
        
        // Handle empty other_skills
        $otherSkillsValue = !empty($otherSkills) ? $otherSkills : null;
        
        $stmt->bind_param("sssssssssss", $firstName, $lastName, $dob, $gender, $streetAddress, $suburbTown, $state, $postcode, $email, $phone, $otherSkillsValue);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert applicant data");
        }
        
        // Get the applicant_id
        $applicantId = $conn->insert_id;
        
        // Step 2: Insert into eoi table
        $eoiQuery = "INSERT INTO eoi (applicant_id, ref_id, status) VALUES (?, ?, 'New')";
        $stmt = $conn->prepare($eoiQuery);
        $stmt->bind_param("is", $applicantId, $jobRef);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert EOI data");
        }
        
        // Get the eoi_id for confirmation
        $eoiId = $conn->insert_id;
        
        // Step 3: Insert selected skills into applicant_skills table
        if (!empty($selectedSkills)) {
            // Debug: Log what skills we're processing
            error_log("Processing skills for applicant_id: " . $applicantId);
            
            // Get all available skills to debug
            $debugSkillQuery = "SELECT skill_id, skill_name FROM skills";
            $debugResult = $conn->query($debugSkillQuery);
            while ($debugRow = $debugResult->fetch_assoc()) {
                error_log("Available skill: " . $debugRow['skill_name'] . " (ID: " . $debugRow['skill_id'] . ")");
            }
            
            // Process each selected skill
            $skillQuery = "SELECT skill_id FROM skills WHERE skill_name = ?";
            $skillStmt = $conn->prepare($skillQuery);
            
            $applicantSkillQuery = "INSERT INTO applicant_skills (applicant_id, skill_id) VALUES (?, ?)";
            $applicantSkillStmt = $conn->prepare($applicantSkillQuery);
            
            foreach ($selectedSkills as $skillName) {
                error_log("Looking for skill: '" . $skillName . "'");
                
                // Try exact match first
                $skillStmt->bind_param("s", $skillName);
                $skillStmt->execute();
                $skillResult = $skillStmt->get_result();
                $skillRow = $skillResult->fetch_assoc();
                
                if ($skillRow) {
                    $skillId = $skillRow['skill_id'];
                    error_log("Found skill: " . $skillName . " with ID: " . $skillId);
                    
                    // Insert into applicant_skills
                    $applicantSkillStmt->bind_param("ii", $applicantId, $skillId);
                    if (!$applicantSkillStmt->execute()) {
                        throw new Exception("Failed to insert skill data for: " . $skillName);
                    } else {
                        error_log("Successfully inserted skill: " . $skillName);
                    }
                } else {
                    // Try with trimmed spaces and check for similar matches
                    $trimmedSkillName = trim($skillName);
                    error_log("Skill not found with exact match. Trying trimmed: '" . $trimmedSkillName . "'");
                    
                    // Try a LIKE query to find similar matches
                    $likeQuery = "SELECT skill_id, skill_name FROM skills WHERE TRIM(skill_name) = ? OR skill_name LIKE ?";
                    $likeStmt = $conn->prepare($likeQuery);
                    $likePattern = "%" . $trimmedSkillName . "%";
                    $likeStmt->bind_param("ss", $trimmedSkillName, $likePattern);
                    $likeStmt->execute();
                    $likeResult = $likeStmt->get_result();
                    
                    if ($likeRow = $likeResult->fetch_assoc()) {
                        $skillId = $likeRow['skill_id'];
                        error_log("Found similar skill: " . $likeRow['skill_name'] . " with ID: " . $skillId);
                        
                        // Insert into applicant_skills
                        $applicantSkillStmt->bind_param("ii", $applicantId, $skillId);
                        if (!$applicantSkillStmt->execute()) {
                            throw new Exception("Failed to insert skill data for: " . $skillName);
                        } else {
                            error_log("Successfully inserted similar skill: " . $likeRow['skill_name']);
                        }
                    } else {
                        // Skill doesn't exist - log it but don't fail the transaction
                        error_log("Unknown skill: '" . $skillName . "' - skipping");
                    }
                }
            }
        }
        
        // Commit the transaction
        $conn->commit();
        
        // Success - redirect to confirmation page
        session_start();
        $_SESSION['eoiNumber'] = $eoiId; // Using eoi_id as the reference number
        $_SESSION['applicantId'] = $applicantId;
        header("Location: confirmation_page.php");
        exit();
        
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        error_log("EOI Processing Error: " . $e->getMessage());
        header("Location: error_page.php");
        exit();
    } finally {
        $conn->autocommit(true);
        $conn->close();
    }
?>