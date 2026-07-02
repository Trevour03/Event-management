
<?php
include 'db.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username) || empty($password)) {
        die('Please fill all fields.');
    }

    // Check for specific admin credentials
    if ($username === 'admin' && $password === 'Ragner') {
        session_start();
        $_SESSION['user id'] = 'admin'; // Set a session variable for admin
        header("Location:Admin Dashboard.php"); // Redirect to admin dashboard
        exit();
    }


}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event  Management system - Login</title>
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
            background-image: url('image/bkg adm.png'); /* Your background image */
            background-size: cover;
            background-position: center;
            color:lightgreen;
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

        input[type="text"], input[type="password"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            width: 100%;
        }

        button[type="submit"] {
            background-color: #8b5e3c; /* Earthy brown color */
            color:aquamarine;
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
            <h1>Event management system</h1>
        </div>

        <div class="login-form">
            <h2>Login</h2>
            <form action="Admin.php" method="post">
                <div class="form-group">
                    <label for="username">Admin name/Email</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Login</button>
            </form>
            <div class="links">
                <a href="forgot password.php">Forgot Password?</a> 
            </div>
        </div>

    </div>
</body>
</html>