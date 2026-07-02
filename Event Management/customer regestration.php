<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5e8e3;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .container h2 {
            text-align: center;
            color: #7a297a;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            margin: 5px 0 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            background-color: #7a297a;
            color: white;
            padding: 10px;
            border: none;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #9b3b9b;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Customer Registration</h2>
    <?php
        $userIdErr = $nameErr = $addressErr = $mobileErr = $emailErr = $passwordErr = $confirmPasswordErr = "";
        $userId = $name = $address = $mobile = $email = $password = $confirmPassword = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // User ID Validation
            if (empty($_POST["userId"]) || strlen($_POST["userId"]) > 5) {
                $userIdErr = "User ID is required and should be a maximum of 5 characters.";
            } else {
                $userId = test_input($_POST["userId"]);
            }

            // Name Validation
            if (empty($_POST["name"]) || strlen($_POST["name"]) > 20) {
                $nameErr = "Name is required and should be a maximum of 20 characters.";
            } else {
                $name = test_input($_POST["name"]);
            }

            // Address Validation
            if (empty($_POST["address"]) || strlen($_POST["address"]) > 10) {
                $addressErr = "Address is required and should be a maximum of 10 characters.";
            } else {
                $address = test_input($_POST["address"]);
            }

            // Mobile Number Validation
            if (empty($_POST["mobile"]) || !preg_match("/^[0-9]{10}$/", $_POST["mobile"])) {
                $mobileErr = "Mobile number is required and should be 10 digits.";
            } else {
                $mobile = test_input($_POST["mobile"]);
            }

            // Email Validation
            if (empty($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Valid email is required.";
            } else {
                $email = test_input($_POST["email"]);
            }

            // Password Validation
            if (empty($_POST["password"]) || strlen($_POST["password"]) > 12) {
                $passwordErr = "Password is required and should be a maximum of 12 characters.";
            } else {
                $password = test_input($_POST["password"]);
            }

            // Confirm Password Validation
            if (empty($_POST["confirmPassword"]) || $_POST["confirmPassword"] !== $_POST["password"]) {
                $confirmPasswordErr = "Password confirmation must match.";
            } else {
                $confirmPassword = test_input($_POST["confirmPassword"]);
            }

            // If all fields are valid, proceed (Here you can add the logic to store the data)
            if (empty($userIdErr) && empty($nameErr) && empty($addressErr) && empty($mobileErr) && empty($emailErr) && empty($passwordErr) && empty($confirmPasswordErr)) {
                echo "<p style='color:green;text-align:center;'>Registration Successful!</p>";
                // You can save to a database here
            }
        }

        function test_input($data) {
            return htmlspecialchars(stripslashes(trim($data)));
        }
    ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="form-group">
            <label for="userId">User ID:</label>
            <input type="text" name="userId" value="<?php echo $userId;?>">
            <span class="error"><?php echo $userIdErr;?></span>
        </div>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo $name;?>">
            <span class="error"><?php echo $nameErr;?></span>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" name="address" value="<?php echo $address;?>">
            <span class="error"><?php echo $addressErr;?></span>
        </div>
        <div class="form-group">
            <label for="mobile">Mobile No:</label>
            <input type="text" name="mobile" value="<?php echo $mobile;?>">
            <span class="error"><?php echo $mobileErr;?></span>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $email;?>">
            <span class="error"><?php echo $emailErr;?></span>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password">
            <span class="error"><?php echo $passwordErr;?></span>
        </div>
        <div class="form-group">
            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" name="confirmPassword">
            <span class="error"><?php echo $confirmPasswordErr;?></span>
        </div>
        <button type="submit" class="btn">Submit</button>
        <button type="reset" class="btn" style="background-color: #ccc; margin-top: 10px;">Cancel</button>
    </form>
</div>

</body>
</html>
