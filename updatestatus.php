<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$is_admin = $_SESSION['is_admin'];

if ($is_admin) {
    $stmt = $pdo->query("SELECT * FROM complaints ORDER BY created_at DESC");
} else {
    $stmt = $pdo->prepare("SELECT * FROM complaints WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
}

$complaints = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Dashboard</h2>
    <a href="submit_complaint.php">Submit a Complaint</a>
    <h3>Your Complaints</h3>
    <div class="responsive-table">
    <table>

        <tr>
            <th>Subject</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
        <?php foreach ($complaints as $complaint): ?>
        <tr>
            <td><?= htmlspecialchars($complaint['subject']) ?></td>
            <td><?= htmlspecialchars($complaint['status']) ?></td>
            <td><?= htmlspecialchars($complaint['created_at']) ?></td>
            <?php if ($is_admin): ?>
            <td><a href="update_status.php?id=<?= $complaint['id'] ?>">Update Status</a></td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </table>
    </div>

    <footer>
        <p>&copy; 2024 Complaint Management System. All rights reserved.</p>
    </footer>
</body>
</html>