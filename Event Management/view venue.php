<?php
session_start();
include 'db.php'; // Ensure this file connects to the database and sets $conn

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: LOG IN.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$venue_image = '';
$event_type = '';
$event_place = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected values from the form
    $event_place = $_POST['event_place'] ?? '';  // Correctly set event_place
    $event_type = $_POST['venueType'] ?? '';    // Correctly set event_type

    if ($conn) {
        // Use actual column names from the bookings table
        $stmt = $conn->prepare("SELECT * FROM bookings WHERE user_id = ? AND event_type = ? AND event_place = ?");
        $stmt->bind_param("iss", $user_id, $event_type, $event_place);  // Correct the variable names here
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Set image based on event place
            switch ($event_place) {
                case 'Oaklandground':
                    $venue_image = 'image/oaklandground.png';
                    break;
                case 'Parklandground':
                    $venue_image = 'image/parklandground.png';
                    break;
                case 'Xyzground':
                    $venue_image = 'image/xyzground.jpg';
                    break;
                default:
                    $venue_image = '';
            }
        } else {
            $venue_image = '';
        }

        $stmt->close();
    } else {
        echo "Database connection error.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <title>View Venue</title>

    <style>
        body {
            background-image: url('image/viewvenue.png');
            background-size: cover;
            background-position: center;
            color: #FFEB3B;
            font-weight: bold;
            font-family: Arial, sans-serif;
        }
        h2 {
            text-align: center;
        }
        .user-info {
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
            color: mediumpurple;
        }
        form {
            text-align: center;
        }
        label {
            color: blue;
        }
        select, span {
            background-color: #5D4037;
            color: #FFEB3B;
            font-weight: bold;
            padding: 5px;
            border-radius: 5px;
            border: none;
        }
        img {
            display: block;
            margin: 20px auto;
            border: 5px solid #FFEB3B;
            border-radius: 10px;
            max-width: 80%;
            height: auto;
        }
    </style>
</head>
<body>

<h2>View Venue</h2>

<!-- User ID Display Section -->
<div class="user-info">
    <p>Welcome, User ID: <?php echo htmlspecialchars($user_id); ?></p>
</div>

<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <label for="venueType">Event Type:</label>
    <select name="venueType" id="venueType">
        <option value="">--Select--</option>
        <option value="Marriage" <?php echo ($event_type == "Marriage") ? 'selected' : ''; ?>>Marriage</option>
        <option value="Graduation" <?php echo ($event_type == "Graduation") ? 'selected' : ''; ?>>Graduation</option>
        <option value="Family Function" <?php echo ($event_type == "Family Function") ? 'selected' : ''; ?>>Family Function</option>
        <option value="Birthday Party" <?php echo ($event_type == "Birthday Party") ? 'selected' : ''; ?>>Birthday Party</option>
        <option value="Anniversary Party" <?php echo ($event_type == "Anniversary Party") ? 'selected' : ''; ?>>Anniversary Party</option>
        <option value="Farewell Party" <?php echo ($event_type == "Farewell Party") ? 'selected' : ''; ?>>Farewell Party</option>
        <option value="College Event" <?php echo ($event_type == "College Event") ? 'selected' : ''; ?>>College Event</option>
        <option value="Crusade Event" <?php echo ($event_type == "Crusade Event") ? 'selected' : ''; ?>>Crusade Event</option>
    </select>

    <br><br>

    <label for="event_place">Venue:</label>
    <select name="event_place" id="event_place">
        <option value="">--Select--</option>
        <option value="Oaklandground" <?php echo ($event_place == "Oaklandground") ? 'selected' : ''; ?>>Oaklandground</option>
        <option value="Parklandground" <?php echo ($event_place == "Parklandground") ? 'selected' : ''; ?>>Parklandground</option>
        <option value="Xyzground" <?php echo ($event_place == "Xyzground") ? 'selected' : ''; ?>>Xyzground</option>
    </select>

    <br><br>

    <input type="submit" name="submitButton" value="Submit">
</form>

<!-- Display Venue Image and Event Info after Submission -->
<?php if ($venue_image): ?>
    <div class="event-place_display">
        <h3>You selected the following:</h3>
        <p>Event Type: <?php echo htmlspecialchars($event_type); ?></p>
        <p>Event Place: <?php echo htmlspecialchars($event_place); ?></p>
        <img src="<?php echo htmlspecialchars($venue_image); ?>" alt="Venue Image">
    </div>
<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <div class="alert alert-warning" role="alert">
        No matching booking found. Please select a valid booking to view the image.
    </div>
<?php endif; ?>

</body>
</html>

