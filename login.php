<?php
require_once "settings.php";

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $errors[] = "Please enter username and password.";
    } else {
        // Fetch user
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

                // Check if user is locked out
                if ($locked_until_dt && $locked_until_dt > $now) {
                    $errors[] = "Account locked until " . $locked_until_dt->format('Y-m-d H:i:s') . ". Please try later.";
                } elseif (password_verify($password, $password_hash)) {
                    // Successful login: reset failed attempts and locked_until
                    $update_stmt = mysqli_prepare($conn, "UPDATE manager_creds SET failed_attempts=0, locked_until=NULL WHERE id=?");
                    mysqli_stmt_bind_param($update_stmt, "i", $id);
                    mysqli_stmt_execute($update_stmt);
                    mysqli_stmt_close($update_stmt);

                    $_SESSION['manager_logged_in'] = true;
                    $_SESSION['manager_id'] = $id;
                    $_SESSION['manager_username'] = $username;

                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    
                    header("Location: manage.php");
                    exit;
                } else {
                    // Failed login: increment failed attempts
                    $failed_attempts++;
                    $locked_until_update = null;

                    // Lock account if 3 or more failed attempts within 15 minutes
                    $last_failed_dt = $last_failed_login ? new DateTime($last_failed_login) : null;
                    $minutes_since_last_fail = $last_failed_dt ? ($now->getTimestamp() - $last_failed_dt->getTimestamp()) / 60 : null;

                    if ($failed_attempts >= 3 && $minutes_since_last_fail !== null && $minutes_since_last_fail <= 15) {
                        // Lock for 10 minutes
                        $lock_expires = (new DateTime())->add(new DateInterval('PT10M'))->format('Y-m-d H:i:s');
                        $locked_until_update = $lock_expires;
                        $errors[] = "Account locked due to multiple failed login attempts. Try again after 10 minutes.";
                    } else {
                        $errors[] = "Invalid username or password.";
                    }

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
    <title>Manager Login</title>
    <link rel="stylesheet" href="styles/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php include 'header.inc' ?>
    <div class="login-wrapper">
        <div class="login-container">
            <h1 class="manager_login_register">Manager Login</h1>

            <?php if ($errors): ?>
                <ul class="error-list">
                    <?php foreach ($errors as $error) echo "<li>" . htmlspecialchars($error) . "</li>"; ?>
                </ul>
            <?php endif; ?>

            <form method="post" action="">
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