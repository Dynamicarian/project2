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
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            width: 96%;    
            margin: auto;
            margin-bottom: 30px;
        }

        .filter-columns {
            display: flex;
            justify-content: space-between;
            gap: 40px;
            padding-bottom: 10px;
        }

        .filter-column {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
            border-right: 1px solid #ccc;
            padding-right: 20px;
        }

        .filter-column:last-child {
            border-right: none;
            padding-right: 0;
        }

        .filter-column label {
            font-weight: bold;
            margin-top: 10px;
        }

        .filter-column input,
        .filter-column select {
            padding: 6px;
            border: 1px solid #999;
            border-radius: 4px;
        }

        .submit-btn, .reset-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .submit-btn:hover, .reset-btn:hover {
            background-color: #2980b9;
        }

        .buttons-row {
            text-align: center;
            gap: 15px;
            display: inline-flex;
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
    </style>
</head>
<body>

<h2>Manager View (christina_test)</h2>

<div class="filter-panel">
    <form method="post">
        <div class="filter-columns">
            <div class="filter-column">
                <label>Identification</label>
                <label for="EOInumber">EOInumber</label>
                <input type="text" name="EOInumber" value="<?= htmlspecialchars($search_terms['EOInumber']) ?>">

                <label for="job_reference_number">Job Ref</label>
                <input type="text" name="job_reference_number" value="<?= htmlspecialchars($search_terms['job_reference_number']) ?>">

                <label for="first_name">First Name</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($search_terms['first_name']) ?>">

                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($search_terms['last_name']) ?>">
            </div>

            <div class="filter-column">
                <label>Address</label>
                <label for="street_address">Street Address</label>
                <input type="text" name="street_address" value="<?= htmlspecialchars($search_terms['street_address']) ?>">

                <label for="suburb">Suburb</label>
                <input type="text" name="suburb" value="<?= htmlspecialchars($search_terms['suburb']) ?>">

                <label for="state">State</label>
                <select name="state">
                    <option value="">-- Any --</option>
                    <?php
                    $states = ["VIC", "NSW", "QLD", "SA", "TAS", "ACT", "WA", "NT"];
                    foreach ($states as $state) {
                        $selected = ($search_terms['state'] == $state) ? "selected" : "";
                        echo "<option value=\"$state\" $selected>$state</option>";
                    }
                    ?>
                </select>

                <label for="postcode">Postcode</label>
                <input type="text" name="postcode" value="<?= htmlspecialchars($search_terms['postcode']) ?>">
            </div>

            <div class="filter-column">
                <label>Skills</label>
                <label for="skill1">Skill 1</label>
                <select name="skill1">
                    <option value="">-- Any --</option>
                    <?php
                    $skills = ["HTML", "CSS", "JavaScript", "Python", "SQL", "Java", "React", "PHP"];
                    foreach ($skills as $skill) {
                        $selected = ($search_terms['skill1'] == $skill) ? "selected" : "";
                        echo "<option value=\"$skill\" $selected>$skill</option>";
                    }
                    ?>
                </select>

                <label for="skill2">Skill 2</label>
                <select name="skill2">
                    <option value="">-- Any --</option>
                    <?php
                    foreach ($skills as $skill) {
                        $selected = ($search_terms['skill2'] == $skill) ? "selected" : "";
                        echo "<option value=\"$skill\" $selected>$skill</option>";
                    }
                    ?>
                </select>

                <label for="skill3">Skill 3</label>
                <select name="skill3">
                    <option value="">-- Any --</option>
                    <?php
                    foreach ($skills as $skill) {
                        $selected = ($search_terms['skill3'] == $skill) ? "selected" : "";
                        echo "<option value=\"$skill\" $selected>$skill</option>";
                    }
                    ?>
                </select>

                <label for="other_skills">Other Skills</label>
                <input type="text" name="other_skills" value="<?= htmlspecialchars($search_terms['other_skills']) ?>">

                <label for="status">Status</label>
                <select name="status">
                    <option value="">-- Any --</option>
                    <?php
                    foreach ($status_options as $status) {
                        $selected = ($search_terms['status'] == $status) ? "selected" : "";
                        echo "<option value=\"$status\" $selected>$status</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="buttons-row">
            <input type="submit" value="Run Query" class="submit-btn" name="run_query">
            <input type="submit" value="Reset" class="reset-btn" name="reset_filters">

            <?php if (!$delete_mode): ?>
                <!-- Button to turn ON delete mode -->
                <button type="submit" name="toggle_delete_mode" value="1" class="submit-btn">
                    Delete Records
                </button>
            <?php else: ?>
                <!-- Button to delete selected records -->
                <button type="submit" name="delete_selected" value="1" class="submit-btn">
                    Delete Selected Records
                </button>

                <!-- Button to turn OFF delete mode without deleting -->
                <button type="submit" name="toggle_delete_mode" value="0" class="submit-btn">
                    Cancel Delete Mode
                </button>
            <?php endif; ?>
        </div>

        <br><br>

        <h3 style="text-align:center;">Results</h3>

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
