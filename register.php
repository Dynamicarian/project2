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
    <title>Manager Registration</title>
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
        .register-container {
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
        .success-message {
            background-color: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            color: #2e7d32;
            text-align: center;
        }
        .success-message a {
            color: #2e7d32;
            font-weight: 600;
            text-decoration: none;
        }
        .success-message a:hover {
            text-decoration: underline;
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
        .password-rules ul {
            margin: 5px 0;
            padding-left: 20px;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #555;
        }
        .login-link a {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register Manager</h2>

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
</body>
</html>