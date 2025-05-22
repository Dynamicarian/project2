<?php
require_once "settings.php";

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
        $stmt = mysqli_prepare($conn, "SELECT id FROM manager_creds WHERE username = ?");
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
        $stmt = mysqli_prepare($conn, "INSERT INTO manager_creds (username, password_hash) VALUES (?, ?)");
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
<head>
<link rel="stylesheet" href="styles/styles.css">
    <title>Manager Registration</title>
</head>
<body>
<?php include 'navbar.inc' ?>
<div class="register-wrapper">
    <div class="register-container">
        <h2 class="manager_login_register">Register Manager</h2>

        <?php if ($success): ?>
            <div class="success-message">
                Registration successful! You can now <a href="login.php">login</a>.
            </div>
        <?php else: ?>
            <?php if ($errors): ?>
                <ul class="error-list">
                    <?php foreach ($errors as $error) echo "<li>" . htmlspecialchars($error) . "</li>"; ?>
                </ul>
            <?php endif; ?>

            <?php
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                // Check if any password-related errors exist
                $passwordErrors = [
                    "Password must be at least 8 characters.",
                    "Password must contain at least one uppercase letter.",
                    "Password must contain at least one lowercase letter.",
                    "Password must contain at least one number.",
                    "Passwords do not match."
                ];
                
                foreach ($errors as $error) {
                    if (in_array($error, $passwordErrors)) {
                        break;
                    }
                }
            }
            ?>

            <form method="post" action="">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($username ?? '') ?>" placeholder="Choose a username">
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Create a password">
                </div>
                
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" placeholder="Re-enter your password">
                </div>
                
                <input type="submit" value="Register">
            </form>

            <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
        <?php endif; ?>
    </div>
    </div>
    <?php include 'footer.inc' ?>
</body>
</html>