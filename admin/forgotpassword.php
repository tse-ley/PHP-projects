session_start(); 

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"])) {
        $action = $_POST["action"];

        if ($action === "request_reset") {
            // Handle password reset request
            $email = $_POST["email"];
            $sql = "SELECT id FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($user_id);
            $stmt->fetch();
            $stmt->close();

            if ($user_id) {
                $token = bin2hex(random_bytes(50));
                $expires = date("U") + 1800; // 30 minutes from now
                $sql = "INSERT INTO password_resets (user_id, token, expires) VALUES (?, ?, FROM_UNIXTIME(?))";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isi", $user_id, $token, $expires);
                $stmt->execute();
                $stmt->close();

                $reset_link = "http://yourdomain.com/your_script.php?token=" . $token;
                $subject = "Password Reset Request";
                $message = "Click the following link to reset your password: " . $reset_link;
                $headers = "From: no-reply@yourdomain.com";

                if (mail($email, $subject, $message, $headers)) {
                    echo "Password reset email sent.";
                } else {
                    echo "Failed to send password reset email.";
                }
            } else {
                echo "No account found with that email address.";
            }
        } elseif ($action === "reset_password") {
            // Handle password reset
            $token = $_POST["token"];
            $new_password = $_POST["new_password"];
            $confirm_password = $_POST["confirm_password"];

            $sql = "SELECT user_id, expires FROM password_resets WHERE token = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $stmt->bind_result($user_id, $expires);
            $stmt->fetch();
            $stmt->close();

            if ($user_id && $expires > date("U")) {
                if ($new_password === $confirm_password) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $sql = "UPDATE users SET password = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("si", $hashed_password, $user_id);
                    if ($stmt->execute()) {
                        $sql = "DELETE FROM password_resets WHERE token = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $token);
                        $stmt->execute();
                        $stmt->close();

                        echo "Password reset successfully.";
                    } else {
                        echo "Error updating password: " . $conn->error;
                    }
                } else {
                    echo "New password and confirm password do not match.";
                }
            } else {
                echo "Invalid or expired token.";
            }
        } elseif ($action === "change_password") {
            // Handle password change
            $current_password = $_POST["current_password"];
            $new_password = $_POST["new_password"];
            $confirm_password = $_POST["confirm_password"];

            $user_id = $_SESSION['user_id']; // Assuming the user ID is stored in the session
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
                } else {
                    echo "New password and confirm password do not match.";
                }
            } else {
                echo "Current password is incorrect.";
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Management</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="" method="post">
        <input type="hidden" name="action" value="request_reset">
        <label for="email">Enter your email address:</label>
        <input type="email" id="email" name="email" required><br><br>
        <input type="submit" value="Request Password Reset">
    </form>

    <h2>Reset Password</h2>
    <form action="" method="post">
        <input type="hidden" name="action" value="reset_password">
        <input type="hidden" name="token" value="<?php echo isset($_GET['token']) ? htmlspecialchars($_GET['token']) : ''; ?>">
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
        <input type="submit" value="Reset Password">
    </form>

    <h2>Change Password</h2>
    <form action="" method="post">
        <input type="hidden" name="action" value="change_password">
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


</body>


</html>