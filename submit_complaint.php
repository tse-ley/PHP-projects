<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $subject = $_POST['subject'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO complaints (user_id, subject, description) VALUES (?, ?, ?)");
    
    try {
        $stmt->execute([$user_id, $subject, $description]);
        $success = "Complaint submitted successfully!";
    } catch (PDOException $e) {
        $error = "Error submitting complaint: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Complaint Management System</h1>
    </header>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="submit_complaint.php">Submit Complaint</a>
        <a href="logout.php"> Logout </a>
    </nav>
    <main>
        <?php if (isset($success)): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <h2>Submit a Complaint</h2>
        <form method="post">
            <input type="text" name="subject" placeholder="Subject" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <input type="submit" value="Submit Complaint">
        </form>
    </main>

    <footer>
        <p>&copy; 2024 Complaint Management System. All rights reserved.</p>
    </footer>
</body>
</html>