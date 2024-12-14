<?php
// Start the session to track login
session_start();

// Initialize error message
$error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'Lab_5b');

    // Check database connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data
    $matric = $_POST['matric'];
    $password = $_POST['password'];

    // Prepare and execute SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE matric = ?");
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verify user credentials
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if ($password == $row['password']) { // Replace with password_verify() if passwords are hashed
            $_SESSION['matric'] = $matric;
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = $row['role']; // Save role in session

            // Redirect to the display page after successful login (Question 5)
            header("Location: display_users.php");
            exit();
        } else {
            $error_message = "Invalid username or password. Please try again.";
        }
    } else {
        $error_message = "Invalid username or password. Please try again.";
    }

    // Close the connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login Page</h2>
    <!-- Login Form -->
    <form action="login.php" method="POST">
        <label for="matric">Matric:</label><br>
        <input type="text" name="matric" id="matric" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" name="password" id="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <!-- Display error message if login fails -->
    <?php
    if (!empty($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    ?>

    <!-- Link to the registration page -->
    <p>Register <a href="register_users.php">here</a> if you don’t have an account.</p>

    <!-- Optional: Link to retry login -->
    <p>Invalid username or password, try <a href="login_users.php">login again</a>.</p>
</body>
</html>
