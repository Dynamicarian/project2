<?php
require_once "settings.php";
$dbconn = @mysqli_connect($host, $user, $password, $sql_db);
if (!$dbconn) {
    die("Connection failed: " . mysqli_connect_error());
}

$delete_mode = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['toggle_delete_mode'])) {
        // Toggle delete mode ON or OFF based on submitted value
        $delete_mode = ($_POST['toggle_delete_mode'] === '1') ? true : false;
    } elseif (isset($_POST['delete_selected']) && isset($_POST['delete_record'])) {
        // Delete selected records from DB
        foreach ($_POST['delete_record'] as $eoi_to_delete => $val) {
            $eoi_to_delete = intval($eoi_to_delete);
            $delete_sql = "DELETE FROM christina_test WHERE EOInumber = $eoi_to_delete";
            mysqli_query($dbconn, $delete_sql);
        }
        $delete_mode = false; // exit delete mode after deletion
    }
}


// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_status"])) {
    $eoi = intval($_POST["EOInumber"]);
    $new_status = mysqli_real_escape_string($dbconn, $_POST["status"]);
    $update_query = "UPDATE christina_test SET status = '$new_status' WHERE EOInumber = $eoi";
    mysqli_query($dbconn, $update_query);
}

// Clean input and build search conditions
function clean_input($conn, $value) {
    return strtolower(trim(mysqli_real_escape_string($conn, $value)));
}

$query = "SELECT * FROM christina_test";
$conditions = [];

$search_terms = [ // initialize all form fields
    "EOInumber" => "",
    "job_reference_number" => "",
    "first_name" => "",
    "last_name" => "",
    "street_address" => "",
    "suburb" => "",
    "state" => "",
    "postcode" => "",
    "skill1" => "",
    "skill2" => "",
    "skill3" => "",
    "other_skills" => "",
    "status" => ""
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
        $query = "SELECT * FROM christina_test";
    } else {
        if (isset($_POST['status_update']) && is_array($_POST['status_update'])) {
            foreach ($_POST['status_update'] as $eoi => $new_status) {
                $eoi_int = intval($eoi);
                $new_status_clean = mysqli_real_escape_string($dbconn, $new_status);
                if (in_array($new_status_clean, $status_options)) {
                    $update_sql = "UPDATE christina_test SET status='$new_status_clean' WHERE EOInumber = $eoi_int";
                    mysqli_query($dbconn, $update_sql);
                }
            }
        }

        foreach ($search_terms as $field => $value) {
            if (!empty($_POST[$field])) {
                $clean = clean_input($dbconn, $_POST[$field]);
                $search_terms[$field] = $_POST[$field]; // preserve case
                if (in_array($field, ["skill1", "skill2", "skill3"])) {
                    $conditions[] = "LOWER($field) = '$clean'";
                } elseif ($field === "status") {
                    $conditions[] = "status = '$clean'";
                } else {
                    $conditions[] = "LOWER($field) LIKE '%$clean%'";
                }
            }
        }

        if (count($conditions) > 0) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        // Add sorting if requested
        if (isset($_POST['sort_field']) && array_key_exists($_POST['sort_field'], $sortable_fields)) {
            $sort_field = mysqli_real_escape_string($dbconn, $_POST['sort_field']);
            $sort_order = (isset($_POST['sort_order']) && strtoupper($_POST['sort_order']) === "DESC") ? "DESC" : "ASC";
            $query .= " ORDER BY $sort_field $sort_order";
        }
    }
}

$result = mysqli_query($dbconn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manager View</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }

        h2 {
            text-align: center;
        }

        .filter-panel {
            background: #ffffffff;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 24px;
            max-width: 1000px;
            margin: 30px auto;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
        }

            .filter-panel h3 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 20px;
            color: #333;
        }

            fieldset {
            border: none;
            padding: 0;
        }

            legend {
            font-weight: bold;
            margin-bottom: 12px;
            color: #555;
            font-size: 16px;
        }

            .form-group {
            margin-bottom: 16px;
        }

            .form-group label {
            display: block;
            font-size: 14px;
            color: #444;
            margin-bottom: 6px;
        }

            .form-group input[type="text"] {
            width: 100%;
            padding: 8px 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: border-color 0.2s;
        }

            .form-group input[type="text"]:focus {
            border-color: #3f51b5;
            outline: none;
            box-shadow: 0 0 0 2px rgba(63, 81, 181, 0.1);
        }

            .buttons-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }

            .submit-btn,
            .reset-btn {
            background-color: #3f51b5;
            color: #fff;
            border: none;
            padding: 10px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

            .reset-btn {
            background-color: #f44336;
        }

            .submit-btn:hover,
            .reset-btn:hover {
            opacity: 0.9;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        select.status-dropdown {
            width: 100%;
            padding: 4px;
            border-radius: 4px;
        }

        input.delete-checkbox {
            transform: scale(1.3);
            margin-left: 5px;
            cursor: pointer;
        }

        .results-header {
            display: flex;
            align-items: flex-start; /* align items at top */
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .results-header h3 {
            margin: 0; /* remove default margin for cleaner alignment */
            padding-top: 4px; /* align vertically with dropdown */
        }

        .sort-controls {
            display: flex;
            align-items: center;
            gap: 10px; /* space between label and dropdowns */
        }

        select.sort-dropdown {
            width: 140px; /* adjust width as needed */
            padding: 8px 10px; /* keeps the slightly taller dropdown from before */
            border-radius: 6px;
            border: 1px solid #999;
            background-color: #fff;
            font-size: 14px;
            cursor: pointer;
            transition: border-color 0.3s ease;
            line-height: 1.3;
        }

        select.sort-dropdown:hover {
            border-color: #3498db;
        }

        .sort-btn {
            height: 32px;
            padding: 4px 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s ease;
        }

        .sort-btn:hover {
            background-color: #2980b9;
        }

    </style>
</head>
<body>

<h2>Manager View (christina_test)</h2>

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
                    <th>EOInumber</th>
                    <th>Job Ref</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Street</th>
                    <th>Suburb</th>
                    <th>State</th>
                    <th>Postcode</th>
                    <th>Skill 1</th>
                    <th>Skill 2</th>
                    <th>Skill 3</th>
                    <th>Other Skills</th>
                    <th>Status</th>
                    <?php if ($delete_mode): ?>
                        <th style="text-align:center;">Delete?</th>
                    <?php endif; ?>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["EOInumber"]) ?></td>
                        <td><?= htmlspecialchars($row["job_reference_number"]) ?></td>
                        <td><?= htmlspecialchars($row["first_name"]) ?></td>
                        <td><?= htmlspecialchars($row["last_name"]) ?></td>
                        <td><?= htmlspecialchars($row["street_address"]) ?></td>
                        <td><?= htmlspecialchars($row["suburb"]) ?></td>
                        <td><?= htmlspecialchars($row["state"]) ?></td>
                        <td><?= htmlspecialchars($row["postcode"]) ?></td>
                        <td><?= htmlspecialchars($row["skill1"]) ?></td>
                        <td><?= htmlspecialchars($row["skill2"]) ?></td>
                        <td><?= htmlspecialchars($row["skill3"]) ?></td>
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

</body>
</html>

<?php mysqli_close($dbconn); ?>
