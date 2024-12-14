<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'Lab_5b');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$matric = $_POST['matric'];
$name = $_POST['name'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password
$role = $_POST['role'];

// Check if matric already exists
$check_sql = "SELECT * FROM users WHERE matric = '$matric'";
$result = $conn->query($check_sql);

if ($result->num_rows > 0) {
    echo "Error: Matric number already exists. Please use a different one.";
} else {
    // Insert into database
    $sql = "INSERT INTO users (matric, name, password, role) VALUES ('$matric', '$name', '$password', '$role')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
