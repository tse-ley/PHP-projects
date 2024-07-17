<?php
session_start(); 


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    
    $user_id = $_SESSION['user_id']; 
    $sql = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($db_password);
    $stmt->fetch();
    $stmt->close();

    
    if (password_verify($current_password, $db_password)) {
        
        if ($new_password === $confirm_password) {
           
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

            
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $hashed_new_password, $user_id);
            if ($stmt->execute()) {
                echo "Password changed successfully.";
            } else {
                echo "Error updating password: " . $conn->error;
            }
            $stmt->close();
        } else {
            echo "New password and confirm password do not match.";
        }
    } else {
        echo "Current password is incorrect.";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html>
<html lang ="en">
<head>
<meta charset="utf-8">
<title>Change password</title>
</head>

<body>
    <h1> Chnage password</h1>
    <form action="change_password.php" method="post">
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" required><br><br>
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
        <input type="submit" value="Change Password">
    </form>


</body>



</html>