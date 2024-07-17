<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $complaint_id = $_POST['complaint_id'];
    $feedback = $_POST['feedback'];

    $stmt = $pdo->prepare("UPDATE complaints SET feedback = ?, feedback_date = CURRENT_TIMESTAMP WHERE id = ?");
    
    try {
        $stmt->execute([$feedback, $complaint_id]);
        $_SESSION['success_message'] = "Feedback added successfully!";
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error adding feedback: " . $e->getMessage();
    }
    
    header("Location: admin_dashboard.php");
    exit();
}

$complaint_id = $_GET['id'] ?? '';
if (!$complaint_id) {
    header("Location: admin_dashboard.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM complaints WHERE id = ?");
$stmt->execute([$complaint_id]);
$complaint = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$complaint) {
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Feedback</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <header>
        <h1>Complaint Management System</h1>
    </header>
    <nav>
        <a href="admin_dashboard.php"><i class="fas fa-home"></i> Admin Dashboard</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
    <main>
        <h2>Add Feedback</h2>
        <form method="post">
            <input type="hidden" name="complaint_id" value="<?= htmlspecialchars($complaint['id']) ?>">
            <p><strong>Complaint Subject:</strong> <?= htmlspecialchars($complaint['subject']) ?></p>
            <p><strong>Complaint Description:</strong> <?= htmlspecialchars($complaint['description']) ?></p>
            <label for="feedback">Feedback:</label>
            <textarea id="feedback" name="feedback" required><?= htmlspecialchars($complaint['feedback'] ?? '') ?></textarea>
            <input type="submit" value="Submit Feedback">
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Complaint Management System. All rights reserved.</p>
    </footer>
</body>
</html>