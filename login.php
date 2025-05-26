<?php
require_once "settings.php";

// Initialize session for authentication tracking
if (session_status() === PHP_SESSION_NONE) { // https://www.php.net/manual/en/session.constants.php
    session_start();
}

$errors = [];

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic input validation
    if (empty($username) || empty($password)) {
        $errors[] = "Please enter username and password.";
    } else {
        // Retrieve user credentials and lockout information from database
        $stmt = mysqli_prepare($conn, "SELECT id, password_hash, failed_attempts, last_failed_login, locked_until FROM manager_creds WHERE username = ?");
        if (!$stmt) {
            $errors[] = "Database error. Please try again.";
        } else {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            
            if (mysqli_stmt_num_rows($stmt) === 1) {
                mysqli_stmt_bind_result($stmt, $id, $password_hash, $failed_attempts, $last_failed_login, $locked_until);
                mysqli_stmt_fetch($stmt);
                
                $now = new DateTime();
                $locked_until_dt = $locked_until ? new DateTime($locked_until) : null;

                // Check if account is currently locked
                if ($locked_until_dt && $locked_until_dt > $now) {
                    $errors[] = "Account locked until " . $locked_until_dt->format('Y-m-d H:i:s') . ". Please try later.";
                } elseif (password_verify($password, $password_hash)) {
                    // Successful login - reset security counters and create session
                    $update_stmt = mysqli_prepare($conn, "UPDATE manager_creds SET failed_attempts=0, locked_until=NULL WHERE id=?");
                    mysqli_stmt_bind_param($update_stmt, "i", $id);
                    mysqli_stmt_execute($update_stmt);
                    mysqli_stmt_close($update_stmt);

                    // Set session variables for authenticated user
                    $_SESSION['manager_logged_in'] = true;
                    $_SESSION['manager_id'] = $id;
                    $_SESSION['manager_username'] = $username;

                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    
                    header("Location: manage.php");
                    exit;
                } else {
                    // Failed login - implement security lockout mechanism
                    $failed_attempts++;
                    $locked_until_update = null;

                    // Check if account should be locked (3+ failures within 15 minutes)
                    $last_failed_dt = $last_failed_login ? new DateTime($last_failed_login) : null;
                    $minutes_since_last_fail = $last_failed_dt ? ($now->getTimestamp() - $last_failed_dt->getTimestamp()) / 60 : null;

                    if ($failed_attempts >= 3 && $minutes_since_last_fail !== null && $minutes_since_last_fail <= 15) {
                        // Lock account for 10 minutes
                        $lock_expires = (new DateTime())->add(new DateInterval('PT10M'))->format('Y-m-d H:i:s');
                        $locked_until_update = $lock_expires;
                        $errors[] = "Account locked due to multiple failed login attempts. Try again after 10 minutes.";
                    } else {
                        $errors[] = "Invalid username or password.";
                    }

                    // Update failed attempt counters in database
                    $update_stmt = mysqli_prepare($conn, "UPDATE manager_creds SET failed_attempts=?, last_failed_login=NOW(), locked_until=? WHERE username=?");
                    mysqli_stmt_bind_param($update_stmt, "iss", $failed_attempts, $locked_until_update, $username);
                    mysqli_stmt_execute($update_stmt);
                    mysqli_stmt_close($update_stmt);
                }
            } else {
                $errors[] = "Invalid username or password.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Secure manager login portal for accessing the job application management system. Authentication required to view and manage EOI records.">
    <meta name="keywords" content="manager login, secure authentication, admin access, login portal, session management, password protection, manager credentials, access control">
    <meta name="author" content="Christina Lian Fernandez">
    <title>Manager Login</title>
    <link rel="stylesheet" href="styles/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php include 'header.inc' ?>
    <div class="login-wrapper">
        <div class="login-container">
            <h1 class="manager_login_register">Manager Login</h1>

            <!-- Display any login errors to user -->
            <?php if ($errors): ?>
                <ul class="error-list">
                    <?php foreach ($errors as $error) echo "<li>" . htmlspecialchars($error) . "</li>"; ?>
                </ul>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                <label for="username">Username</label>
                    <input type="text"  id="username" name="username" value="<?= htmlspecialchars($username ?? '') ?>" placeholder="Enter your username">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password">
                </div>
                
                <input type="submit" value="Login">
            </form>

            <p class="register-link">New user? <a href="register.php">Create an account</a></p>
        </div>
    </div>
    <?php include 'footer.inc' ?>
</body>
</html>