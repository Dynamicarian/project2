<?php
require_once "settings.php";

$dbconn = @mysqli_connect($host, $user, $password, $sql_db);
if (!$dbconn) {
    die("Connection failed: " . mysqli_connect_error());
}

$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required.";
    } else {
        // Check uniqueness
        $stmt = mysqli_prepare($dbconn, "SELECT id FROM manager_creds WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errors[] = "Username already exists.";
        }
        mysqli_stmt_close($stmt);
    }

    // Validate password rules
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one uppercase letter.";
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must contain at least one lowercase letter.";
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must contain at least one number.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // If no errors, insert manager
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($dbconn, "INSERT INTO manager_creds (username, password_hash) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ss", $username, $password_hash);
        if (mysqli_stmt_execute($stmt)) {
            $success = true;
        } else {
            $errors[] = "Failed to register user.";
        }
        mysqli_stmt_close($stmt);
    }
}

?>

<!DOCTYPE html>
<html>
<head><title>Manager Registration</title></head>
<body>
<h2>Register Manager</h2>

<?php if ($success): ?>
    <p>Registration successful! You can now <a href="login.php">login</a>.</p>
<?php else: ?>
    <?php if ($errors): ?>
        <ul style="color:red;">
            <?php foreach ($errors as $error) echo "<li>" . htmlspecialchars($error) . "</li>"; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="">
        <label>Username: <input type="text" name="username" value="<?= htmlspecialchars($username ?? '') ?>"></label><br><br>
        <label>Password: <input type="password" name="password"></label><br><br>
        <label>Confirm Password: <input type="password" name="confirm_password"></label><br><br>
        <input type="submit" value="Register">
    </form>
<?php endif; ?>

</body>
</html>
