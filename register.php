<?php
include 'connect.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['mobile'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $location = $_POST['location']; // Capture location from the form
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Calculate age
    $birth_date = new DateTime($dob);
    $current_date = new DateTime();
    $age = $current_date->diff($birth_date)->y;

    // Check if email already exists
    $check_email = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($check_email);

    if ($result->num_rows > 0) {
        echo "Email already exists!";
    } else {
        // Insert the new user data into the database
        $sql = "INSERT INTO users (first_name, last_name, email, phone, dob, gender, location, password)
                VALUES ('$first_name', '$last_name', '$email', '$phone', '$dob', '$gender', '$location', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            echo "Registration successful!";
            session_start();
            $_SESSION['user_id'] = $conn->insert_id; // Store the user ID in session
            header('Location: profile.php'); // Redirect to the profile page
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="registration.css"> <!-- Link to external CSS -->
</head>
<body>

    <div class="form">
        <div class="title">Register</div>

        <!-- Registration Form -->
        <form action="register.php" method="POST">
            <!-- First Name and Last Name on the same line -->
            <div class="name-container">
                <div class="input-container ic-half">
                    <input id="first-name" name="first_name" class="input" type="text" placeholder=" " required />
                    <div class="cut"></div>
                    <label for="first-name" class="placeholder">First Name</label>
                </div>
                <div class="input-container ic-half">
                    <input id="last-name" name="last_name" class="input" type="text" placeholder=" " required />
                    <div class="cut"></div>
                    <label for="last-name" class="placeholder">Last Name</label>
                </div>
            </div>

            <!-- Email Input -->
            <div class="input-container ic2">
                <input id="email" name="email" class="input" type="email" placeholder=" " required />
                <div class="cut"></div>
                <label for="email" class="placeholder">Email</label>
                <div class="validation-message" id="email-message"></div>
            </div>

            <!-- Mobile Number Input -->
            <div class="input-container ic2">
                <input id="mobile" name="mobile" class="input" type="text" placeholder=" " required />
                <div class="cut"></div>
                <label for="mobile" class="placeholder">Mobile Number</label>
                <div class="validation-message" id="mobile-message"></div>
            </div>

            <!-- Date of Birth Input -->
            <div class="input-container ic2">
                <input id="dob" name="dob" class="input" type="date" placeholder=" " required />
                <div class="cut"></div>
                <label for="dob" class="placeholder">Date of Birth</label>
            </div>

            <!-- Gender Selection -->
            <div class="input-container ic2">
                <select id="gender" name="gender" class="input" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
                <div class="cut"></div>
                <label for="gender" class="placeholder">Gender</label>
            </div>

            <!-- Location Input -->
            <div class="input-container ic2">
                <input id="location" name="location" class="input" type="text" placeholder=" " required />
                <div class="cut"></div>
                <label for="location" class="placeholder">Location</label>
            </div>

            <!-- Password Input -->
            <div class="input-container ic2">
                <input id="password" name="password" class="input" type="password" placeholder="" required />
                <div class="cut"></div>
                <label for="password" class="placeholder">Create Password</label>
                <div class="validation-message" id="password-message"></div>
            </div>

            <!-- Confirm Password Input -->
            <div class="input-container ic2">
                <input id="confirm-password" name="confirm_password" class="input" type="password" placeholder=" " required />
                <div class="cut"></div>
                <label for="confirm-password" class="placeholder">Confirm Password</label>
                <div class="validation-message" id="confirm-password-message"></div>
            </div>

            <!-- Terms and Conditions Checkbox -->
            <div class="input-container ic2">
                <input type="checkbox" id="terms" name="terms" required />
                <label for="terms">I accept the <a href="#">Terms and Conditions</a></label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="submit">Register</button>
        </form>

        <!-- Link to Log In Page -->
        <div class="links">
            Already have an account? <a href="login.php">Log in here</a>
        </div>
    </div>

    <script src="registration.js"></script> <!-- External JavaScript -->
</body>
</html>
