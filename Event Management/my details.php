
<?php
session_start();  // Start the session to access user data

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php'; // Connect to the database

// Initialize $user_id to avoid undefined variable warning
$user_id = ''; 

// Check if the user is logged in (assumes user_id is stored in session)
if (isset($_SESSION['user_id'])) {
    // If user is logged in, use the session 'user_id' to autofill
    $user_id = $_SESSION['user_id'];
} else {
    // If user_id is not found in session, check the form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve and sanitize input data
        $name = trim($_POST['name']);
        $address = trim($_POST['address']);
        $mobile_no = trim($_POST['mobile_no']);
        $email = trim($_POST['email']);

        // Check if the user already exists based on the email
        $check_user_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?"); 
        $check_user_stmt->bind_param("s", $email); 
        $check_user_stmt->execute();
        $result = $check_user_stmt->get_result();

        if ($result->num_rows > 0) {
            // Existing user: Retrieve their user_id
            $row = $result->fetch_assoc();
            $user_id = $row['id']; // Assuming 'id' is the correct field for user ID
            // Store the user_id in session for later use
            $_SESSION['user_id'] = $user_id;
        } else {
            // New user: Insert and auto-generate the user_id
            $stmt = $conn->prepare("INSERT INTO users (name, address, mobile_no, email) 
                                    VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $address, $mobile_no, $email);

            if ($stmt->execute()) {
                // Retrieve the auto-generated user_id
                $user_id = $conn->insert_id;
                // Store the new user_id in session
                $_SESSION['user_id'] = $user_id;
            } else {
                echo "Execution error: " . htmlspecialchars($stmt->error);
            }
            $stmt->close();
        }

        $check_user_stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details Form</title>
    <style>
        body {
            background-image: url('image/BKG3.PNG');
            color: yellow; /* Yellow text color */
            font-family: Arial, sans-serif;
        }
        .container {
            width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #333; /* Darker background for form container */
            border-radius: 10px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
        }
        input[readonly] {
            background-color: #555;
            color: #ddd;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
        }
        .btn {
            padding: 10px 20px;
            background-color: blue; /* Button color */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Enter Your Details</h2>
    <form action="my_details.php" method="post">
        <label for="user_id">User ID:</label>
        <!-- Populate user_id field dynamically -->
        <input type="text" id="user_id" name="user_id" value="<?php echo htmlspecialchars($user_id ?? ''); ?>" readonly>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required>

        <label for="mobile_no">Mobile No:</label>
        <input type="text" id="mobile_no" name="mobile_no" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <div class="buttons">
            <button type="submit" class="btn">Update</button>
            <button type="reset" class="btn">Cancel</button>
        </div>
    </form>
</div>

</body>
</html>