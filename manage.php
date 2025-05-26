<?php
// Session authentication - ensure manager is logged in
session_start();
if (!isset($_SESSION['manager_logged_in']) || $_SESSION['manager_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
require_once "settings.php";

// Initialize delete mode flag
$delete_mode = false;

// Handle POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Toggle delete mode
    if (isset($_POST['toggle_delete_mode'])) {
        // Toggle delete mode ON or OFF based on submitted value
        $delete_mode = ($_POST['toggle_delete_mode'] === '1') ? true : false;

    // Securely delete selected records using prepared statement
    } elseif (isset($_POST['delete_selected']) && isset($_POST['delete_record'])) {
        // First, get the applicant_id for each selected EOI
        $get_applicant_sql = "SELECT applicant_id FROM eoi WHERE eoi_id = ?";
        $get_stmt = mysqli_prepare($conn, $get_applicant_sql);
        
        // Delete from applicants table (cascade will handle eoi and applicant_skills)
        $delete_sql = "DELETE FROM applicants WHERE applicant_id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_sql);
        
        // Process each selected record
        foreach ($_POST['delete_record'] as $eoi_to_delete => $val) {
            // Get the applicant_id for this EOI
            mysqli_stmt_bind_param($get_stmt, "i", $eoi_to_delete);
            mysqli_stmt_execute($get_stmt);
            $result = mysqli_stmt_get_result($get_stmt);
            
            if ($row = mysqli_fetch_assoc($result)) {
                $applicant_id = $row['applicant_id'];
                
                // Delete the applicant (cascade will delete EOI and skills)
                mysqli_stmt_bind_param($delete_stmt, "i", $applicant_id);
                mysqli_stmt_execute($delete_stmt);
            }
        }
        
        mysqli_stmt_close($get_stmt);
        mysqli_stmt_close($delete_stmt);
        $delete_mode = false; // Exit delete mode after deletion
    }
}

// Securely handle status updates with parameterized query
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["status_update"])) {
    $update_sql = "UPDATE eoi SET status = ? WHERE eoi_id = ?";
    $stmt = mysqli_prepare($conn, $update_sql);
    
    // Validate status and update each record
    foreach ($_POST["status_update"] as $eoi_id => $new_status) {
        if (in_array($new_status, ["New", "Current", "Final"])) {
            mysqli_stmt_bind_param($stmt, "si", $new_status, $eoi_id);
            mysqli_stmt_execute($stmt);
        }
    }
    mysqli_stmt_close($stmt);
}

// Clean input function (still useful for LIKE clauses)
function clean_input($conn, $value) {
    return strtolower(trim(mysqli_real_escape_string($conn, $value)));
}

// Updated base query to join normalized tables
$query = "SELECT 
    e.eoi_id,
    e.ref_id as job_reference,
    a.first_name,
    a.last_name,
    a.date_of_birth,
    a.gender,
    a.street_address,
    a.suburb,
    a.state,
    a.postcode,
    a.email,
    a.phone,
    a.other_skills,
    e.status,
    GROUP_CONCAT(s.skill_name ORDER BY s.skill_id SEPARATOR ', ') as skills_list,
    MAX(CASE WHEN s.skill_name = 'Technical Support' THEN 1 ELSE 0 END) as technical_support,
    MAX(CASE WHEN s.skill_name = 'System Administration' THEN 1 ELSE 0 END) as system_administration,
    MAX(CASE WHEN s.skill_name = 'Problem Solving and Communication' THEN 1 ELSE 0 END) as problem_solving_and_communication
FROM eoi e
JOIN applicants a ON e.applicant_id = a.applicant_id
LEFT JOIN applicant_skills aps ON a.applicant_id = aps.applicant_id
LEFT JOIN skills s ON aps.skill_id = s.skill_id";

$conditions = [];
$params = [];
$types = "";

$search_terms = [
    "job_reference" => "",
    "first_name" => "",
    "last_name" => "",
];

$status_options = ["New", "Current", "Final"];

$sortable_fields = [
    "e.eoi_id" => "EOI ID",
    "e.ref_id" => "Job Ref",
    "a.first_name" => "First Name",
    "a.last_name" => "Last Name",
    "a.suburb" => "Suburb",
    "a.state" => "State",
    "a.postcode" => "Postcode",
    "e.status" => "Status"
];

// Initialize default sort values
$current_sort_field = "e.eoi_id";  // Default to EOI ID
$current_sort_order = "ASC";       // Default to Ascending

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle filter reset - clear all search terms, reset sorting, and revert to base query
    if (isset($_POST['reset_filters'])) {
        foreach ($search_terms as $key => $val) {
            $search_terms[$key] = "";   // Clear each search field
        }
        // Reset sorting to defaults
        $current_sort_field = "e.eoi_id";
        $current_sort_order = "ASC";
    } else {
        // Preserve current sort settings if not resetting
        if (isset($_POST['sort_field']) && array_key_exists($_POST['sort_field'], $sortable_fields)) {
            $current_sort_field = $_POST['sort_field'];
        }
        if (isset($_POST['sort_order']) && in_array(strtoupper($_POST['sort_order']), ["ASC", "DESC"])) {
            $current_sort_order = strtoupper($_POST['sort_order']);
        }
        
        // Process search filters when query is submitted
        if (isset($_POST['run_query'])) {
            foreach ($search_terms as $field => $value) {
                if (!empty($_POST[$field])) {
                    // Sanitize input while preserving original case for display
                    $clean = clean_input($conn, $_POST[$field]);
                    $search_terms[$field] = $_POST[$field];

                    // Map field names to the correct table columns
                    if ($field === "job_reference") {
                        $conditions[] = "LOWER(e.ref_id) LIKE ?";
                        $clean = "%$clean%";
                    } elseif ($field === "first_name") {
                        $conditions[] = "LOWER(a.first_name) LIKE ?";
                        $clean = "%$clean%";
                    } elseif ($field === "last_name") {
                        $conditions[] = "LOWER(a.last_name) LIKE ?";
                        $clean = "%$clean%";
                    }

                    // Store parameters and types for prepared statement
                    $params[] = $clean;
                    $types .= "s";  // 's' indicates string type for binding
                }
            }
        }

        // Combine all conditions with WHERE if we have any filters
        if (count($conditions) > 0) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
    }
}

// Add GROUP BY clause (required because of JOIN with skills)
$query .= " GROUP BY e.eoi_id, e.ref_id, a.first_name, a.last_name, a.date_of_birth, a.gender, a.street_address, a.suburb, a.state, a.postcode, a.email, a.phone, a.other_skills, e.status";

// Always add sorting (either default or user-selected)
$query .= " ORDER BY $current_sort_field $current_sort_order";

// Execute main query with parameter binding
$stmt = mysqli_prepare($conn, $query);
if ($params) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager View</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
<!-- Nav bar -->
    <?php include 'header.inc' ?>
    <div class="manage-page">
        <h1>Manager View</h1>

            <form method="post">
                <section class="filter-panel">
                    <h2>🔍 Filter Applications</h2>
                    <fieldset>
                        <legend>Search filters for job applications</legend>

                        <div class="form-group">
                            <label for="job_reference">Job Ref</label>
                            <input type="text" name="job_reference" id="job_reference" value="<?= htmlspecialchars($search_terms['job_reference']) ?>" placeholder="e.g., DEV78">
                        </div>

                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($search_terms['first_name']) ?>" placeholder="e.g., Christina">
                        </div>

                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($search_terms['last_name']) ?>" placeholder="e.g., Smith">
                        </div>
                    </fieldset>
                    
                    <!-- Action buttons row -->
                    <div class="buttons-row">
                        <button type="submit" name="run_query" class="submit-btn">
                            Apply Filter
                        </button>
                        
                        <button type="submit" name="reset_filters" class="submit-btn">
                            Reset Filters
                        </button>

                        <!-- Conditional delete mode toggle -->
                        <?php if (!$delete_mode): ?>
                            <button type="submit" name="toggle_delete_mode" value="1" class="submit-btn">
                                Delete Records
                            </button>
                        <?php else: ?>
                            <button type="submit" name="delete_selected" value="1" class="submit-btn">
                                Delete Selected Records
                            </button>

                            <button type="submit" name="toggle_delete_mode" value="0" class="submit-btn">
                                Cancel Delete Mode
                            </button>
                        <?php endif; ?>
                    </div>
                </section>

                <br><br>
                
                <!-- Results section -->
                 <section class="results-section">
                    <div class="results-header">
                        <h2>Results</h2>
                        <div class="sort-controls">
                            <label for="sort_field"><strong>Sort By:</strong></label>
                            <select id="sort_field" name="sort_field" class="sort-dropdown">
                            <?php
                            foreach ($sortable_fields as $field => $label) {
                                $selected = ($current_sort_field == $field) ? "selected" : "";
                                echo "<option value=\"$field\" $selected>$label</option>";
                            }
                            ?>
                            </select>
                            <!-- Sort direction toggle -->
                            <label for="sort_order">Order:</label>
                            <select id="sort_order" name="sort_order" class="sort-dropdown">
                                <option value="ASC" <?= ($current_sort_order == 'ASC') ? 'selected' : '' ?>>Ascending</option>
                                <option value="DESC" <?= ($current_sort_order == 'DESC') ? 'selected' : '' ?>>Descending</option>
                            </select>

                            <button type="submit" name="run_query" class="sort-btn">Sort</button>
                        </div>
                    </div>

                    <!-- Results table -->
                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>EOI Num</th>
                                    <th>Job Ref#</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>DOB</th>
                                    <th>Gender</th>
                                    <th>Street</th>
                                    <th>Suburb</th>
                                    <th>State</th>
                                    <th>Postcode</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Tech Support</th>
                                    <th>Sys Admin</th>
                                    <th>Solving & Comms</th>
                                    <th>Other Skills</th>
                                    <th>Status</th>
                                    <?php if ($delete_mode): ?>
                                        <th style="text-align:center;">Delete?</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <!-- Display all record data with output escaping -->
                                        <td><?= htmlspecialchars($row["eoi_id"]) ?></td>
                                        <td><?= htmlspecialchars($row["job_reference"]) ?></td>
                                        <td><?= htmlspecialchars($row["first_name"]) ?></td>
                                        <td><?= htmlspecialchars($row["last_name"]) ?></td>
                                        <td><?= htmlspecialchars($row["date_of_birth"]) ?></td>
                                        <td><?= htmlspecialchars($row["gender"]) ?></td>
                                        <td><?= htmlspecialchars($row["street_address"]) ?></td>
                                        <td><?= htmlspecialchars($row["suburb"]) ?></td>
                                        <td><?= htmlspecialchars($row["state"]) ?></td>
                                        <td><?= htmlspecialchars($row["postcode"]) ?></td>
                                        <td><?= htmlspecialchars($row["email"]) ?></td>
                                        <td><?= htmlspecialchars($row["phone"]) ?></td>
                                        <td class="check_cross"><?= $row["technical_support"] ? '&#10004;' : '&#10008;'; ?></td>
                                        <td class="check_cross"><?= $row["system_administration"] ? '&#10004;' : '&#10008;'; ?></td>
                                        <td class="check_cross"><?= $row["problem_solving_and_communication"] ? '&#10004;' : '&#10008;'; ?></td>
                                        <td><?= htmlspecialchars($row["other_skills"]) ?></td>

                                        <!-- Status dropdown for each record -->
                                        <td>
                                            <label for="status_<?= $row["eoi_id"] ?>">Status:</label>
                                            <select id="status_<?= $row["eoi_id"] ?>" class="status-dropdown" name="status_update[<?= $row["eoi_id"] ?>]">
                                                <?php foreach ($status_options as $status): 
                                                    $selected = ($row["status"] === $status) ? "selected" : "";
                                                ?>
                                                    <option value="<?= $status ?>" <?= $selected ?>><?= $status ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        
                                        <div class="delete-mode">
                                            <?php if ($delete_mode): ?>
                                                <td style="text-align:center;">
                                                    <label for="delete_<?= $row["eoi_id"] ?>">Delete:</label>
                                                    <input type="checkbox" id="delete_<?= $row["eoi_id"] ?>" class="delete-checkbox" name="delete_record[<?= $row["eoi_id"] ?>]" value="1">
                                                </td>
                                            <?php endif; ?>
                                        </div>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <!-- Empty results message -->
                        <div class="no-results">
                            <p>No applications found matching the current filter criteria.</p>
                        </div>
                    <?php endif; ?>
                </section>
            </form>
            
        <div class="manager-button-container">
            <a href="enhancements.php" class="manager_page_button">
            <img src="images/enhancements-icon.png" alt="View Enhancements" class="manager-icon">
            Enhancements
            </a>
        </div>
    </div>
    <?php include 'footer.inc' ?>
</body>
</html>

<?php 
if (isset($stmt)) {
    mysqli_stmt_close($stmt);
}
mysqli_close($conn); 
?>