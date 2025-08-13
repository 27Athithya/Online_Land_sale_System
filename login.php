<?php
include 'connect.php';
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Fetch user data or other data you want to show in the DataTable
    $sql = "SELECT * FROM users"; // For demo purposes, showing all users
    $result = $conn->query($sql);
	header('Location: index.php');
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user from the database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start session
            $_SESSION['user_id'] = $user['user_id'];
            header('Location: index.php'); // Refresh page to show DataTable
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found with that email!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page with DataTable</title>
    <link rel="stylesheet" href="login.css"> <!-- Login-specific CSS -->

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
</head>
<body>

<div class="form">
    <div class="title">Login</div>

    <?php if (!isset($_SESSION['user_id'])): ?>
    <!-- Login Form -->
    <form action="login.php" method="POST">
        <!-- Email Input -->
        <div class="input-container ic2">
            <input id="email" name="email" class="input" type="text" placeholder=" " required />
            <div class="cut"></div>
            <label for="email" class="placeholder">Email</label>
        </div>

        <!-- Password Input -->
        <div class="input-container ic2">
            <input id="password" name="password" class="input" type="password" placeholder=" " required />
            <div class="cut"></div>
            <label for="password" class="placeholder">Password</label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="submit">Log In</button>
    </form>

    <div class="links">
        Don't have an account? <a href="register.php">Register here.</a>
    </div>
    
    <?php else: ?>
    <!-- DataTable displayed after login -->
    <div class="table-container">
        <table id="userTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo $row['first_name']; ?></td>
                    <td><?php echo $row['last_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<!-- jQuery and DataTables JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#userTable').DataTable(); // Initialize DataTables
    });
</script>

</body>
</html>
