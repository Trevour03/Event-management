<?php
include 'db.php'; // Include your database connection

// Check if the database connection is established
if ($conn->connect_error) {
    die('Connection failed: ' . htmlspecialchars($conn->connect_error));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernameOrEmail = trim($_POST['usernameOrEmail']); // Input field can be either username or email
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($usernameOrEmail) || empty($password)) {
        die('Please fill in all fields.');
    }

    // Check if username or email exists in the database
    $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE username = ? OR email = ?");
    if (!$stmt) {
        die('Error preparing query: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 0) {
        die('Username or email not found.');
    }
    $stmt->bind_result($id, $username, $email, $hashed_password);
    $stmt->fetch();
    $stmt->close();
    // Verify password
    if (!password_verify($password, $hashed_password)) {
        die('Incorrect password.');
    }

    // Start a session for the user    session_start();
    $_SESSION['user_id'] = $id;
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;

    // Redirect to a dashboard or home page after successful login
    header("Location: index.php");
    exit(); // Ensure no further code is executed after the redirect
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System - Login</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Style */
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('image/background.png'); /* Your background image */
            background-size: cover;
            background-position: center;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        /* Container for the content */
        .container {
            background: rgba(0, 0, 0, 0.7); /* Semi-transparent background */
            padding: 20px;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
        }

        /* Header Style */
        .header {
            margin-bottom: 20px;
        }

        .header h1 {
            margin-bottom: 10px;
            font-size: 24px;
        }

        /* Login Form Style */
        .login-form {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            width: 100%;
        }

        button[type="submit"] {
            background-color: #8b5e3c; /* Earthy brown color */
            color: white;
            cursor: pointer;
            border: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #7b4c2a; /* Darker brown color */
        }

        /* Links Style */
        .links {
            margin-top: 10px;
        }

        .links a {
            color: #f1c40f; /* Yellow color for links */
            text-decoration: none;
            font-size: 14px;
            margin: 0 5px;
            display: inline-block;
            transition: color 0.3s ease;
        }

        .links a:hover {
            color: #f39c12; /* Slightly darker yellow for hover effect */
            text-decoration: underline;
        }

        /* Footer Style */
        .footer {
            margin-top: 20px;
            font-size: 12px;
        }

        .footer p {
            color: #ccc; /* Light grey color for footer text */
        }

        .footer a {
            color: #f1c40f; /* Yellow color for footer links */
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Event Management System</h1>
        </div>

        <div class="login-form">
            <h2>Login</h2>
            <form action="LOG IN.php" method="post">
                <div class="form-group">
                    <label for="usernameOrEmail">Username/Email</label>
                    <input type="text" id="usernameOrEmail" name="usernameOrEmail" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Login</button>
            </form>
            <div class="links">
                <a href="forgot-password.php">Forgot Password?</a> | <a href="sign-up.php">Sign Up</a>
            </div>
        </div>

    </div>
</body>
</html>
