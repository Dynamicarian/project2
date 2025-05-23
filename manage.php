<?php
session_start();
if (!isset($_SESSION['manager_logged_in']) || $_SESSION['manager_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
require_once "settings.php";

$delete_mode = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['toggle_delete_mode'])) {
        // Toggle delete mode ON or OFF based on submitted value
        $delete_mode = ($_POST['toggle_delete_mode'] === '1') ? true : false;
    } elseif (isset($_POST['delete2_selected']) && isset($_POST['delete_record'])) {
        // Delete selected records from DB
        foreach ($_POST['delete_record'] as $eoi_to_delete => $val) {
            $eoi_to_delete = intval($eoi_to_delete);
            $delete_sql = "DELETE FROM eoi WHERE EOInumber = $eoi_to_delete";
            mysqli_query($conn, $delete_sql);
        }
        $delete_mode = false; // exit delete mode after deletion
    }
}


// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_status"])) {
    $eoi = intval($_POST["EOInumber"]);
    $new_status = mysqli_real_escape_string($conn, $_POST["status"]);
    $update_query = "UPDATE eoi SET status = '$new_status' WHERE EOInumber = $eoi";
    mysqli_query($conn, $update_query);
}

// Clean input and build search conditions
function clean_input($conn, $value) {
    return strtolower(trim(mysqli_real_escape_string($conn, $value)));
}

$query = "SELECT * FROM eoi";
$conditions = [];

$search_terms = [ // initialize all form fields
    "job_reference_number" => "",
    "first_name" => "",
    "last_name" => "",
];

// Possible statuses for dropdown
$status_options = ["New", "Current", "Final"];

$sortable_fields = [
    "EOInumber" => "EOInumber",
    "job_reference_number" => "Job Ref",
    "first_name" => "First Name",
    "last_name" => "Last Name",
    "suburb" => "Suburb",
    "state" => "State",
    "postcode" => "Postcode",
    "status" => "Status"
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Reset filters
    if (isset($_POST['reset_filters'])) {
        foreach ($search_terms as $key => $val) {
            $search_terms[$key] = "";
        }
        $query = "SELECT * FROM eoi";
    } else {
        if (isset($_POST['status_update']) && is_array($_POST['status_update'])) {
            foreach ($_POST['status_update'] as $eoi => $new_status) {
                $eoi_int = intval($eoi);
                $new_status_clean = mysqli_real_escape_string($conn, $new_status);
                if (in_array($new_status_clean, $status_options)) {
                    $update_sql = "UPDATE eoi SET status='$new_status_clean' WHERE EOInumber = $eoi_int";
                    mysqli_query($conn, $update_sql);
                }
            }
        }
    if (isset($_POST['run_query'])) {
        foreach ($search_terms as $field => $value) {
            if (!empty($_POST[$field])) {
                $clean = clean_input($conn, $_POST[$field]);
                $search_terms[$field] = $_POST[$field]; // preserve case
                if ($field === "status") {
                    $conditions[] = "status = '$clean'";
                } else {
                    $conditions[] = "LOWER($field) LIKE '%$clean%'";
                }
            }
        }
    }
    

        if (count($conditions) > 0) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        // Add sorting if requested
        if (isset($_POST['sort_field']) && array_key_exists($_POST['sort_field'], $sortable_fields)) {
            $sort_field = mysqli_real_escape_string($conn, $_POST['sort_field']);
            $sort_order = (isset($_POST['sort_order']) && strtoupper($_POST['sort_order']) === "DESC") ? "DESC" : "ASC";
            $query .= " ORDER BY $sort_field $sort_order";
        }
    }
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manager View</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
<!-- Nav bar -->
    <?php include 'navbar.inc' ?>
    <div class="manage-page">
        <h2>Manager View (eoi)</h2>

        <div class="filter-panel">
            <form method="post">
                <h3>🔍 Filter Applications</h3>
                        <fieldset>

                            <div class="form-group">
                                <label for="job_reference_number">Job Ref</label>
                                <input type="text" name="job_reference_number" value="<?= htmlspecialchars($search_terms['job_reference_number']) ?>" placeholder="e.g., J1234">
                            </div>

                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" value="<?= htmlspecialchars($search_terms['first_name']) ?>" placeholder="e.g., Christina">
                            </div>

                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" value="<?= htmlspecialchars($search_terms['last_name']) ?>" placeholder="e.g., Smith">
                            </div>
                        </fieldset>
                    
                <div class="buttons-row">
                    <input type="submit" value="Apply Filter" class="submit-btn" name="run_query">
                    <input type="submit" value="Reset" class="reset-btn" name="reset_filters">

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
        </div>

                <br><br>

                <div class="results-header">
                    <h3>Results</h3>
                    <div class="sort-controls">
                        <label for="sort_field"><strong>Sort By:</strong></label>
                        <select name="sort_field" class="sort-dropdown">
                        <?php
                        foreach ($sortable_fields as $field => $label) {
                            $selected = (isset($_POST['sort_field']) && $_POST['sort_field'] == $field) ? "selected" : "";
                            echo "<option value=\"$field\" $selected>$label</option>";
                        }
                        ?>
                        </select>

                        <select name="sort_order" class="sort-dropdown">
                            <option value="ASC" <?= (isset($_POST['sort_order']) && $_POST['sort_order'] == 'ASC') ? 'selected' : '' ?>>Ascending</option>
                            <option value="DESC" <?= (isset($_POST['sort_order']) && $_POST['sort_order'] == 'DESC') ? 'selected' : '' ?>>Descending</option>
                        </select>

                        <button type="submit" name="run_query" class="sort-btn">Sort</button>
                    </div>
                </div>


                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <table>
                        <tr>
                            <th>EOI Number</th>
                            <th>Job Reference Number</th>
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
                            <th>Technical Support</th>
                            <th>System Administration</th>
                            <th>Problem Solving and Communication</th>
                            <th>Other Skills</th>
                            <th>Status</th>
                            <?php if ($delete_mode): ?>
                                <th style="text-align:center;">Delete?</th>
                            <?php endif; ?>
                        </tr>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row["EOInumber"]) ?></td>
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
                                <td><?= htmlspecialchars($row["technical_support"]) ? '&check;' : '&cross;'; ?></td>
                                <td><?= htmlspecialchars($row["system_administration"]) ? '&check;' : '&cross;'; ?></td>
                                <td><?= htmlspecialchars($row["problem_solving_and_communication"]) ? '&check;' : '&cross;'; ?></td>
                                <td><?= htmlspecialchars($row["other_skills"]) ?></td>
                                <td>
                                    <select class="status-dropdown" name="status_update[<?= intval($row["EOInumber"]) ?>]">
                                        <?php foreach ($status_options as $status): 
                                            $selected = ($row["status"] === $status) ? "selected" : "";
                                        ?>
                                            <option value="<?= $status ?>" <?= $selected ?>><?= $status ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>

                                <?php if ($delete_mode): ?>
                                    <td style="text-align:center;">
                                        <input type="checkbox" class="delete-checkbox" name="delete_record[<?= intval($row["EOInumber"]) ?>]" value="1">
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endwhile; ?>

                    </table>
                <?php else: ?>
                    <p>No results found.</p>
                <?php endif; ?>

            </form>
    </div>
    <?php include 'footer.inc' ?>
</body>
</html>

<?php mysqli_close($conn); ?>
