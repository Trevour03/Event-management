
<?php
session_start();
include 'db.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: LOG IN.php");
    exit();
}

// Example query to fetch user details
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id); // Bind the user_id as an integer
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo "Welcome, " . $user['username'] . "!";
} else {
    echo "User not found.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $user_id = $_SESSION['user_id'];
    $event_type = $_POST['event_type'];
    $event_place = $_POST['event_place'];
    $no_of_guests = $_POST['guests'];
    $event_date = $_POST['event_date'];
    $equipment = isset($_POST['equipment']) ? implode(', ', $_POST['equipment']) : '';
    $food = isset($_POST['food']) ? implode(', ', $_POST['food']) : '';
    $lunch_type = $_POST['lunch_type'];
    $dinner_type = $_POST['dinner_type'];
    $lighting = $_POST['lighting'];
    $flowers = $_POST['flowers'];
    $seating = $_POST['seating'];
    $status = "Pending"; // Default status for new bookings

    // Insert or update booking in the database
    $stmt = $conn->prepare(
        "INSERT INTO bookings (user_id, event_type, event_place, no_of_guests, event_date, equipment, food, lunch_type, dinner_type, lighting, flowers, seating, status)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE 
         event_type = VALUES(event_type), event_place = VALUES(event_place), no_of_guests = VALUES(no_of_guests),
         event_date = VALUES(event_date), equipment = VALUES(equipment), food = VALUES(food), lunch_type = VALUES(lunch_type),
         dinner_type = VALUES(dinner_type), lighting = VALUES(lighting), flowers = VALUES(flowers), seating = VALUES(seating), status = VALUES(status)"
    );

    // Bind parameters
    $stmt->bind_param(
        "issssssssssss", 
        $user_id, $event_type, $event_place, $no_of_guests, $event_date, $equipment, $food, $lunch_type, $dinner_type, $lighting, $flowers, $seating, $status
    );

    // Execute the statement and handle success or error
    if ($stmt->execute()) {
        echo "<script>alert('Booking successfully recorded!');</script>";
    } else {
        echo "<script>alert('Error recording booking. Please try again.');</script>";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Booking</title>
    <style>
        body {
            background-image: url('image/s1.PNG');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            color: purple;
        }
        .container {
            margin: 50px auto;
            width: 300px;
            padding: 20px;
            border: 2px solid yellow;
            border-radius: 10px;
            background-color: white;
        }
        label {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        label input[type="checkbox"] {
            margin-right: 10px;
            width: auto;
        }
        input[type="text"],
        input[type="number"],
        input[type="date"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        button {
            background-color: yellow;
            color: black;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }
        button:hover {
            background-color: darkgoldenrod;
        }
        #invoice {
            display: none; /* Initially hidden */
        }
    </style>
</head>
<body>

    <h2 style="text-align:center;">Book Your Event</h2>

    <div class="container">
        <form id="eventForm" method="post">
            <label for="event_type">Event Type:</label>
            <select id="event_type" name="event_type" required>
                <option value="Marriage">Marriage</option>
                <option value="Graduation">Graduation</option>
                <option value="Family Function">Family Function</option>
                <option value="Birthday Party">Birthday Party</option>
                <option value="Anniversary Party">Anniversary Party</option>
                <option value="Farewell Party">Farewell Party</option>
                <option value="College Event">College Event</option>
                <option value="Crusade Event">Crusade Event</option>
            </select>

            <label for="event_place">Event Place:</label>
            <select id="event_place" name="event_place" required>
                <option value="Oaklandground">Oaklandground</option>
                <option value="Parklandground">Parklandground</option>
                <option value="Xyzground">Xyzground</option>
            </select>

            <label for="guests">Number of Guests:</label>
            <input type="number" id="guests" name="guests" min="1" required>

            <label for="event_date">Event Date:</label>
            <input type="date" id="event_date" name="event_date" required>

            <h3>Equipment Selection:</h3>
            <label><input type="checkbox" name="equipment[]" value="DJ"> DJ</label>
            <label><input type="checkbox" name="equipment[]" value="Stage"> Stage</label>
            <label><input type="checkbox" name="equipment[]" value="Mike"> Mike</label>
            <label><input type="checkbox" name="equipment[]" value="Speaker"> Speaker</label>

            <h3>Food Selection:</h3>
            <label><input type="checkbox" name="food[]" value="Breakfast"> Breakfast</label>
            <label><input type="checkbox" name="food[]" value="Lunch"> Lunch</label>
            <label><input type="checkbox" name="food[]" value="Tea and snacks"> Tea and Snacks</label>
            <label><input type="checkbox" name="food[]" value="Dinner"> Dinner</label>
            <label><input type="checkbox" name="food[]" value="Veg"> Only Veg</label>
            <label><input type="checkbox" name="food[]" value="Veg and Non-Veg"> Veg and Non-Veg</label>

            <label for="lunch_type">Lunch Type:</label>
            <select id="lunch_type" name="lunch_type">
                <option value="No">No</option>
                <option value="Normal">Normal</option>
                <option value="Deluxe">Deluxe</option>
                <option value="Royal">Royal</option>
            </select>

            <label for="dinner_type">Dinner Type:</label>
            <select id="dinner_type" name="dinner_type">
                 <option value="No">No</option>
                <option value="Normal">Normal</option>
                <option value="Deluxe">Deluxe</option>
                <option value="Royal">Royal</option>
            </select>

            <button type="button" onclick="showDecorationOptions()">Next</button>

            <div id="decorationOptions" style="display:none;">
                <h3>Decoration Options:</h3>
                <label for="lighting">Lighting:</label>
                <select id="lighting" name="lighting">
                    <option value="No">No</option>
                    <option value="Normal">Normal</option>
                    <option value="Deluxe">Deluxe</option>
                    <option value="Royal">Royal</option>
                </select>

                <label for="flowers">Flowers:</label>
                <select id="flowers" name="flowers">
                    <option value="No">No</option>
                    <option value="Normal">Normal</option>
                    <option value="Deluxe">Deluxe</option>
                    <option value="Royal">Royal</option>
                </select>

                <label for="seating">Seating:</label>
                <select id="seating" name="seating">
                    <option value="Chairs">Chairs</option>
                    <option value="Sofa and Chair">Sofa and Chair</option>
                    <option value="Sofa">Sofa</option>
                </select>

                <button type="button" onclick="calculateTotal()">Calculate Total</button>
                <p id="totalCost"></p>

                <button type="button" onclick="SubmitBooking()">Submit Booking</button>
            </div>
        </form>
    </div>

    <div id="invoice">
        <h3>Invoice</h3>
        <p id="invoiceDetails"></p>
    </div>

    <script>
        function showDecorationOptions() {
            document.getElementById("decorationOptions").style.display = "block";
        }
        function calculateTotal() {
            let total = 0;
            const guests = parseInt(document.getElementById("guests").value) || 0;
            total += guests * 200;
            document.getElementById("totalCost").innerText = "Total: ksh" + total;
        }

        function SubmitBooking() {
            document.getElementById("eventForm").submit();
        }
    </script>
</body>
</html>
