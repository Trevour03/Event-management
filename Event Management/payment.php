<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment and Invoice</title>
    <style>
        /* General Styles */
        body {
            background-image: url('image/pymt.png');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        /* Payment Page Styles */
        .payment-page {
            background-image: url('image/pymt.png');
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        form {
            width: 300px;
            padding: 20px;
            background-color: #444;
            border-radius: 8px;
        }
        input[type="text"], input[type="number"], input[type="month"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: none;
        }
        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .pay-btn {
            background-color: green;
            color: white;
        }
        .cancel-btn {
            background-color: red;
            color: white;
        }

        /* Invoice Page Styles */
        .invoice-page {
            background-color: lightblue;
            color: black;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .invoice-container {
            width: 500px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
        }
        .total {
            font-weight: bold;
            font-size: 18px;
            text-align: right;
        }
        .back-btn {
            display: block;
            margin-top: 20px;
            padding: 10px;
            text-align: center;
            background-color: #333;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
    </style>
</head>
<body>

<?php
session_start();
include 'db.php';

// Check if user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('No booking found. Please make a booking first.'); window.location.href='Book Event.php';</script>";
    exit();
}

// Retrieve the booking details from the database using user_id
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM bookings WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $booking = $result->fetch_assoc();
    $user_id = $booking["user_id"];
} else {
    echo "<script>alert('Booking not found.'); window.location.href='Book Event.php';</script>";
    exit();
}

// Check if the payment form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch form data
    $user_id = $_POST['user_id'];
    $userName = $_POST['user_name'];
    $cardNumber = $_POST['card_number'];
    $bankName = $_POST['bank_name'];
    $amount = $_POST['amount'];  // This will be the amount passed from the form
    $expiration = $_POST['expiration'];
    
    // Insert payment details into the database
    $sql = "INSERT INTO `payment` (user_id, user_name, card_number, name_of_bank, amount, expiration) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssis", $user_id, $userName, $cardNumber, $bankName, $amount, $expiration);

    if ($stmt->execute()) {
        echo "<p>Payment was successfully recorded.</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();

    // Generate the invoice
    $billNo = rand(1000, 9999);  // Random Bill No
    $functionName = $booking['event_type'];  // Event type from booking
    $noOfGuests = $booking['no_of_guests'];  // Number of guests from booking
    
    // Define items with their associated costs
    $items = [
        'Equipment',
        'Food',
        'Decoration',
    ];

    // Calculate total cost
    $Cost = array_sum($items);  // Calculate total cost
?>
    <!-- Invoice Page -->
    <div class="invoice-page">
        <div class="invoice-container">
            <h2>Invoice</h2>
            <p><strong>Bill No:</strong> <?php echo $billNo; ?></p>
            <p><strong>Function Name:</strong> <?php echo $functionName; ?></p>
            <p><strong>No of Guests:</strong> <?php echo $noOfGuests; ?></p>
            <h3>Items</h3>
            <?php foreach ($items as $item => $cost): ?>
                <div class="item-row">
                    <span><?php echo $item; ?></span>
                    <span>$<?php echo $cost; ?></span>
                </div>
            <?php endforeach; ?>
            <p class="total">Total Cost: $<?php echo $Cost; ?></p>
            <a href="?" class="back-btn">Back to Payment Page</a>
        </div>
    </div>
<?php
} else {
?>

    <!-- Payment Form -->
    <div class="payment-page">
        <form action="" method="POST">
            <h2>Payment Form</h2>
            <label for="user_id">User id:</label>
            <input type="text" id="user_id" name="user_id" required>

            <label for="user_name">User Name:</label>
            <input type="text" id="user_name" name="user_name" required>

            <label for="card_number">Card Number:</label>
            <input type="text" id="card_number" name="card_number" required>
            
            <label for="bank_name">Name of Bank/Card:</label>
            <input type="text" id="bank_name" name="bank_name" required>
            
            <label for="amount">Amount:</label>
            <input type="text" id="amount" name="amount" required>

            <label for="expiration">Expiration (MM/YYYY):</label>
            <input type="month" id="expiration" name="expiration" required>
            
            <button type="submit" class="pay-btn">Pay</button>
            <button type="button" class="cancel-btn" onclick="window.location.href='?'">Cancel</button>
        </form>
    </div>

<?php } ?>
</body>
</html>

