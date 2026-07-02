
<?php
session_start();
require_once 'db.php';

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = 'Please fill all fields.';
    } else {
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = 'Invalid email format.';
        } else {
            try {
                // Check if username or email already exists
                $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
                if (!$check_stmt) {
                    throw new Exception("Error preparing check query: " . $conn->error);
                }
                
                $check_stmt->bind_param("ss", $username, $email);
                $check_stmt->execute();
                $check_stmt->store_result();

                if ($check_stmt->num_rows > 0) {
                    $error_message = 'Username or email already exists.';
                } else {
                    // Hash the password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Check if this is the first user (make them admin)
                    $count_query = "SELECT COUNT(*) as total FROM users";
                    $result = $conn->query($count_query);
                    $row = $result->fetch_assoc();
                    $user_role = ($row['total'] == 0) ? 'admin' : 'user';

                    // Insert new user
                    $insert_stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
                    if (!$insert_stmt) {
                        throw new Exception("Error preparing insert query: " . $conn->error);
                    }

                    $insert_stmt->bind_param("ssss", $username, $email, $hashed_password, $user_role);

                    if ($insert_stmt->execute()) {
                        $success_message = "Registration successful!";
                        // Redirect after 2 seconds
                        header("refresh:2;url=LOG IN.php");
                    } else {
                        throw new Exception("Error inserting user: " . $insert_stmt->error);
                    }
                    $insert_stmt->close();
                }
                $check_stmt->close();
            } catch (Exception $e) {
                $error_message = "An error occurred: " . $e->getMessage();
            }
        }
    }
}
?>
{{ ... }}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Event Management System</title>
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
            background-image: url('image/background.png');
            background-size: cover;
            background-position: center;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
            padding: 20px;
        }

        /* Container for the content */
        .container {
            background: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
        }

        /* Message Styles */
        .error-message {
            background-color: rgba(255, 0, 0, 0.1);
            border: 1px solid #ff0000;
            color: #ff0000;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .success-message {
            background-color: rgba(0, 255, 0, 0.1);
            border: 1px solid #00ff00;
            color: #00ff00;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        /* Form Style */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: rgba(255, 255, 255, 0.9);
            color: #333;
            font-size: 16px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #8b5e3c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #7b4c2a;
        }

        .links {
            margin-top: 20px;
        }

        .links a {
            color: #f1c40f;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .links a:hover {
            color: #f39c12;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 style="margin-bottom: 30px;">Sign Up</h1>

        <?php if ($error_message): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required 
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Sign Up</button>
        </form>
        <div class="links">
            <a href="LOG IN.php">Already have an account? Log In</a>
        </div>
    </div>
</body>
</html>