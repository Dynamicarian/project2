<?php
require_once "settings.php";

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dbconn = @mysqli_connect($host, $user, $password, $sql_db);
if (!$dbconn) {
    die("Connection failed: " . mysqli_connect_error());
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $errors[] = "Please enter username and password.";
    } else {
        // Fetch user
        $stmt = mysqli_prepare($dbconn, "SELECT id, password_hash, failed_attempts, last_failed_login, locked_until FROM manager_creds WHERE username = ?");
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
                    $update_stmt = mysqli_prepare($dbconn, "UPDATE manager_creds SET failed_attempts=0, locked_until=NULL WHERE id=?");
                    mysqli_stmt_bind_param($update_stmt, "i", $id);
                    mysqli_stmt_execute($update_stmt);
                    mysqli_stmt_close($update_stmt);

                    $_SESSION['manager_logged_in'] = true;
                    $_SESSION['manager_id'] = $id;
                    $_SESSION['manager_username'] = $username;

                    mysqli_stmt_close($stmt);
                    mysqli_close($dbconn);
                    
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

                    $update_stmt = mysqli_prepare($dbconn, "UPDATE manager_creds SET failed_attempts=?, last_failed_login=NOW(), locked_until=? WHERE username=?");
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
<html>
<head>
    <title>Manager Login</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 450px;
        }
        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
        }
        .error-list {
            background-color: #fee;
            border-left: 4px solid #ff5252;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            list-style-type: none;
        }
        .error-list li {
            color: #ff5252;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        input[type="submit"] {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 14px 20px;
            width: 100%;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #2980b9;
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #555;
        }
        .register-link a {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Manager Login</h2>

        <?php if ($errors): ?>
            <ul class="error-list">
                <?php foreach ($errors as $error) echo "<li>" . htmlspecialchars($error) . "</li>"; ?>
            </ul>
        <?php endif; ?>

        <form method="post" action="">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($username ?? '') ?>" placeholder="Enter your username">
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password">
            </div>
            
            <input type="submit" value="Login">
        </form>

        <p class="register-link">New user? <a href="register.php">Create an account</a></p>
    </div>
</body>
</html>