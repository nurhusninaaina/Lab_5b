<?php
// Start the session
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'Lab_5b');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle DELETE action
if (isset($_GET['delete'])) {
    $matric_to_delete = $_GET['delete'];
    $delete_sql = "DELETE FROM users WHERE matric = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("s", $matric_to_delete);
    $stmt->execute();
    $stmt->close();
    header("Location: display_users.php");
    exit();
}

// Fetch all users
$sql = "SELECT matric, name, role FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User List</title>
</head>
<body>
    <h2>User List</h2>
    <table border="1" cellspacing="0" cellpadding="5">
        <tr>
            <th>Matric</th>
            <th>Name</th>
            <th>Level</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['matric']); ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['role']); ?></td>
            <td>
                <a href="update_users.php?matric=<?php echo $row['matric']; ?>">Update</a> |
                <a href="display_update_delete.php?delete=<?php echo $row['matric']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <p><a href="register_users.php">Register a new user</a></p>
</body>
</html>

<?php
$conn->close();
?>
