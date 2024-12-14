<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'Lab_5b');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$error_message = "";
$success_message = "";
$matric = "";
$name = "";
$role = "";

// Fetch user data for the given matric
if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];
    $stmt = $conn->prepare("SELECT name, role FROM users WHERE matric = ?");
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $role = $row['role'];
    } else {
        $error_message = "User not found!";
    }
    $stmt->close();
}

// Handle form submission to update user data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $role = $_POST['role'];

    if (empty($name) || empty($role)) {
        $error_message = "All fields are required!";
    } else {
        $update_stmt = $conn->prepare("UPDATE users SET name = ?, role = ? WHERE matric = ?");
        $update_stmt->bind_param("sss", $name, $role, $matric);
        if ($update_stmt->execute()) {
            $success_message = "User updated successfully!";
        } else {
            $error_message = "Failed to update user!";
        }
        $update_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update User</title>
</head>
<body>
    <h2>Update User</h2>

    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <?php if (!empty($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <form action="update_user.php" method="POST">
        <label for="matric">Matric:</label><br>
        <input type="text" name="matric" id="matric" value="<?php echo htmlspecialchars($matric); ?>" readonly><br><br>

        <label for="name">Name:</label><br>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" required><br><br>

        <label for="role">Access Level:</label><br>
        <input type="text" name="role" id="role" value="<?php echo htmlspecialchars($role); ?>" required><br><br>

        <button type="submit">Update</button>
        <a href="display_update_delete.php">Cancel</a>
    </form>
</body>
</html>

<?php
$conn->close();
?>
