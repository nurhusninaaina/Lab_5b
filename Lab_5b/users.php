<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
</head>
<body>
    <form action="insert_user.php" method="POST">
        <label for="matric">Matric:</label><br>
        <input type="text" id="matric" name="matric" required><br><br>
        
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        
        <label for="role">Role:</label><br>
        <select id="role" name="role" required>
            <option value="">Please select</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select><br><br>
        
        <button type="submit">Submit</button>
    </form>
</body>
</html>
