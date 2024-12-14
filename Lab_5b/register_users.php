<?php
// Initialize error and success messages
$error_message = "";
$success_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'Lab_5b');

    // Check database connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve and sanitize form data
    $matric = trim($_POST['matric']);
    $name = trim($_POST['name']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    // Input validation
    if (empty($matric) || empty($name) || empty($password) || empty($role)) {
        $error_message = "All fields are required.";
    } else {
        // Check for duplicate matric number (PRIMARY KEY)
        $stmt = $conn->prepare("SELECT * FROM users WHERE matric = ?");
        $stmt->bind_param("s", $matric);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Matric number already exists. Please use a different one.";
        } else {
            // Hash password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user data into the database
            $stmt = $conn->prepare("INSERT INTO users (matric, name, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $matric, $name, $hashed_password, $role);

            if ($stmt->execute()) {
                $success_message = "Registration successful! <a href='login.php'>Login here</a>.";
            } else {
                $error_message = "Error: " . $stmt->error;
            }
        }

        // Close statement and connection
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Register Page</h2>

    <!-- Registration Form -->
    <form action="register.php" method="POST">
        <label for="matric">Matric:</label><br>
        <input type="text" name="matric" id="matric" required><br><br>

        <label for="name">Name:</label><br>
        <input type="text" name="name" id="name" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" name="password" id="password" required><br><br>

        <label for="role">Role:</label><br>
        <select name="role" id="role" required>
            <option value="">Please select</option>
            <option value="Admin">Admin</option>
            <option value="User">User</option>
        </select><br><br>

        <button type="submit">Register</button>
    </form>

    <!-- Display Messages -->
    <?php
    if (!empty($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    if (!empty($success_message)) {
        echo "<p style='color: green;'>$success_message</p>";
    }
    ?>

    <p>Already have an account? <a href="login_users.php">Login here</a>.</p>
</body>
</html>
