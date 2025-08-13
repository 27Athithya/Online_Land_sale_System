<?php
session_start();
include 'connect.php';

$user_id = $_SESSION['user_id']; 

// Fetch user data from the database
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$success_message = ""; 

// Handle profile update or delete request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        // Delete the user
        $delete_sql = "DELETE FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        session_destroy();
        header('Location: login.php');
        exit;
    } elseif (isset($_POST['update_profile'])) {
        // Collect form data
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $new_password = $_POST['new_password'];

        // Update logic for password/email change
        if ($email !== $user['email'] || !empty($new_password)) {
            if (password_verify($password, $user['password'])) {
                if (!empty($new_password)) {
                    // Update with new password
                    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                    $update_sql = "UPDATE users SET first_name = ?, last_name = ?, mobile = ?, email = ?, password = ? WHERE user_id = ?";
                    $stmt = $conn->prepare($update_sql);
                    $stmt->bind_param("sssssi", $first_name, $last_name, $mobile, $email, $hashed_password, $user_id);
                } else {
                    // Update without password change
                    $update_sql = "UPDATE users SET first_name = ?, last_name = ?, mobile = ?, email = ? WHERE user_id = ?";
                    $stmt = $conn->prepare($update_sql);
                    $stmt->bind_param("ssssi", $first_name, $last_name, $mobile, $email, $user_id);
                }
            } else {
                echo "Incorrect password!";
                exit;
            }
        } else {
            // Simple update for non-email/password fields
            $update_sql = "UPDATE users SET first_name = ?, last_name = ?, mobile = ? WHERE user_id = ?";
            $stmt->prepare($update_sql);
            $stmt->bind_param("sssi", $first_name, $last_name, $mobile, $user_id);
        }

        // Execute update query
        if ($stmt->execute()) {
            $success_message = "Profile updated successfully!";
        } else {
            echo "Error updating profile: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        /* Profile Popup Container */
        .profile-popup {
            background-color: #fff;
            padding: 20px;
            width: 400px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Headings */
        h2 {
            margin-bottom: 20px;
            color: #5a5a5a;
        }

        /* Form Styles */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            text-align: left;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s ease-in-out;
        }

        input[type="text"]:disabled,
        input[type="email"]:disabled {
            background-color: #f0f0f0;
        }

        input[type="password"] {
            display: none;
        }

        input:focus {
            outline: none;
            border-color: #007bff;
        }

        /* Button Styles */
        button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        #edit_button {
            background-color: #007bff;
            color: #fff;
        }

        #done_button {
            background-color: #28a745;
            color: #fff;
        }

        #delete_button {
            background-color: #dc3545;
            color: #fff;
        }

        #logout_button {
            background-color: #6c757d;
            color: #fff;
        }

        button:hover {
            opacity: 0.9;
        }

        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        /* Success Message */
        .success-message {
            background-color: #28a745;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        /* Password Section */
        #password_section {
            display: none;
        }

        #password_section input[type="password"] {
            display: block;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .profile-popup {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="profile-popup">
        <h2>Your Profile</h2>
        <?php if ($success_message): ?>
            <div id="success_message" class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo $user['first_name']; ?>" disabled required><br>
            
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo $user['last_name']; ?>" disabled required><br>
            
            <label for="mobile">Mobile Number:</label>
            <input type="text" id="mobile" name="mobile" value="<?php echo $user['phone']; ?>" disabled required><br>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" disabled required><br>
            
            <div id="password_section" style="display: none;">
                <label for="password">Current Password (required for email or password change):</label>
                <input type="password" id="password" name="password"><br>
                
                <label for="new_password">New Password (optional):</label>
                <input type="password" id="new_password" name="new_password"><br>
                
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password"><br>
            </div>
            
            <button type="button" id="edit_button">Edit</button>
            <button type="submit" id="done_button" name="update_profile" disabled style="display:none;">Done</button>
            <button type="submit" name="delete" id="delete_button">Delete Account</button>
            <a href="logout.php"><button type="button" id="logout_button">Logout</button></a>
        </form>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editButton = document.getElementById("edit_button");
            const doneButton = document.getElementById("done_button");
            const emailField = document.getElementById("email");
            const passwordSection = document.getElementById("password_section");
            const successMessage = document.getElementById("success_message");

            const formFields = document.querySelectorAll('#first_name, #last_name, #mobile, #email');

            // Enable editing when Edit button is clicked
            editButton.addEventListener('click', () => {
                formFields.forEach(field => field.disabled = false);
                doneButton.disabled = false;
                editButton.style.display = "none";
                doneButton.style.display = "inline-block";

                // Show password section if email changes
                emailField.addEventListener('input', () => {
                    if (emailField.value !== emailField.defaultValue) {
                        passwordSection.style.display = 'block';
                    } else {
                        passwordSection.style.display = 'none';
                    }
                });
            });

            // Hide success message after a few seconds
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 3000); 
            }

            // Validate password matching
            const newPasswordField = document.getElementById("new_password");
            const confirmPasswordField = document.getElementById("confirm_password");
            newPasswordField.addEventListener('input', () => {
                if (newPasswordField.value !== confirmPasswordField.value) {
                    confirmPasswordField.setCustomValidity("Passwords do not match!");
                } else {
                    confirmPasswordField.setCustomValidity("");
                }
            });
        });
    </script>
</body>
</html>
