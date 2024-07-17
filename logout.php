<?php
session_start();


error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_SESSION['user_id'])) {
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    
    session_destroy();
    header("Location: login.php");
    exit();
} else {
    echo "No active session found. You may already be logged out.";
}

echo "<br>Redirect failed. Please <a href='login.php'>click here</a> to go to the login page.";
?>
