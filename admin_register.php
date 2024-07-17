<?php
require_once 'config.php';
session_start();


define('ADMIN_SECRET_KEY', 'your_very_secret_key_here');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $secret_key = $_POST['secret_key'];

    if ($secret_key !== ADMIN_SECRET_KEY) {
        $error = "Invalid secret key";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, is_admin) VALUES (?, ?, TRUE)");
        try {
            $stmt->execute([$username, $password]);
            $success = "Admin account created successfully";
        } catch (PDOException $e) {
            $error = "Error creating admin account: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <title>Admin Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Admin Registration</h2>
    <?php
    if (isset($error)) echo "<p style='color: red;'>$error</p>";
    if (isset($success)) echo "<p style='color: green;'>$success</p>";
    ?>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="password" name="secret_key" placeholder="Secret Key" required><br>
        <input type="submit" value="Register Admin">
    </form>


    <footer>
        <p>&copy; 2024 Complaint Management System. All rights reserved.</p>
    </footer>
</body>
</html>