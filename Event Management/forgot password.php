<?php
// Include the database connection
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'])) {
        // Step 1: Handle "Forgot Password" request
        $email = $_POST['email'];
        $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Generate token and expiration time
            $token = bin2hex(random_bytes(50));
            $expires_at = date("Y-m-d H:i:s", strtotime('+1 hour'));

            // Store token in password_resets table
            $stmt = $db->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires_at)");
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":token", $token);
            $stmt->bindParam(":expires_at", $expires_at);
            $stmt->execute();

            // Send the reset email
            $resetLink = "https://yourdomain.com/forgot_password.php?token=" . $token;
            $subject = "Password Reset Request";
            $message = "Click this link to reset your password: " . $resetLink;
            $headers = "From: no-reply@yourdomain.com";

            if (mail($email, $subject, $message, $headers)) {
                echo "Password reset link has been sent to your email.";
            } else {
                echo "Failed to send email.";
            }
        } else {
            echo "No account found with that email address.";
        }
    } elseif (isset($_POST['new_password'], $_POST['email'])) {
        // Step 3: Handle password update with token verification
        $email = $_POST['email'];
        $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
        
        // Update the user's password
        $stmt = $db->prepare("UPDATE users SET password = :new_password WHERE email = :email");
        $stmt->bindParam(":new_password", $new_password);
        $stmt->bindParam(":email", $email);

        if ($stmt->execute()) {
            // Delete the used reset token
            $stmt = $db->prepare("DELETE FROM password_resets WHERE email = :email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            echo "Password has been reset successfully.";
        } else {
            echo "Failed to reset password.";
        }
    }
}

// Step 2: Verify token if reset link clicked
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $db->prepare("SELECT email FROM password_resets WHERE token = :token AND expires_at > NOW()");
    $stmt->bindParam(":token", $token);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $email = $stmt->fetchColumn();
    } else {
        die("Invalid or expired token.");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
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
            background-image: url('image/bkg.png'); /* Your background image */
            background-size: cover;
            background-position: center;
            color: #f1c40f; /* Yellow color for text */
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
            color:pink; 
            font-size: 24px;
            font-weight: bold;
        }

        /* Label Style */
        label {
            display: block;
            margin-bottom: 5px;
            color:pink;
            font-weight: bold;
            font-size: 14px;
        }

        /* Input Style */
        input[type="text"], input[type="email"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            width: 100%;
            margin-bottom: 15px;
            color: #333;
        }

        /* Button Style */
        button[type="submit"] {
            background-color: #28a745; /* Green color for submit button */
            color: white;
            cursor: pointer;
            border: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #218838; /* Darker green color for hover effect */
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
    <?php if (!isset($_GET['token']) && !isset($email)) : ?>
        <!-- Step 1: Request reset link form -->
        <form action="forgot_password.php" method="post">
            <label for="email">Enter your email address:</label>
            <input type="email" name="email" required>
            <button type="submit">Send Reset Link</button>
        </form>
    <?php elseif (isset($email)) : ?>
        <!-- Step 3: Reset password form -->
        <form action="forgot_password.php" method="post">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" required>
            <button type="submit">Update Password</button>
        </form>
    <?php endif; ?>
</body>
</html>

