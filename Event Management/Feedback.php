<?php
// Include database connection
include 'db.php';

// Start the session (if not already started)
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get feedback from the form
    $feedback = $conn->real_escape_string($_POST['feedback']);

    // Get user ID from session
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
    $user_name = isset($_SESSION['user_name']) ? $conn->real_escape_string($_SESSION['user_name']) : "Anonymous";

    // Ensure user_id is valid before proceeding
    if ($user_id === null) {
        echo "Error: User not logged in. Please log in to provide feedback.";
        exit;
    }

    // Get current timestamp
    $created_at = date('Y-m-d H:i:s');

    // Insert data into the feedback table
    $sql = "INSERT INTO feedback (user_id, user_name, feedback, created_at) 
            VALUES ('$user_id', '$user_name', '$feedback', '$created_at')";

    // Execute the query and check for success
    if ($conn->query($sql) === TRUE) {
        echo "Thank you for your feedback!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <style>
        body {
            background-color: #f7e3e9; /* Soft pastel pink */
            font-family: Arial, sans-serif;
            color: #333;
            text-align: center;
            padding: 50px;
        }
        h1 {
            color: #2c3e50;
        }
        p {
            color: peru;
        }
        .feedback-container {
            background-color: rgba(255, 255, 255, 0.9);
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            width: 300px;
            margin: 0 auto;
        }
        textarea {
            width: 100%;
            height: 100px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            font-style: italic;
            background-color: white;
            color: black;
        }
        .button {
            background-color: green;
            color: black;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
            margin: 10px 5px;
        }
        .button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <h1>Feedback</h1>
    <p>Your feedback is valuable to us!</p>

    <div class="feedback-container">
        <form action="feedback.php" method="POST">
            <textarea name="feedback" placeholder="Please enter your feedback here..." required></textarea><br>
            <button type="submit" class="button">Submit</button>
            <button type="button" onclick="window.history.back();" class="button">Cancel</button>
        </form>
    </div>
</body>
</html>