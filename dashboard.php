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
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<header>
        <h1>Complaint Management System</h1>
    </header>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="submit_complaint.php">Submit Complaint</a>
        <?php if ($is_admin): ?>
            <a href="admin_dashboard.php">Admin Dashboard</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    </nav>
    <main>
    <main>
    <?php 
    if (isset($_SESSION['success_message'])) {
        echo '<div class="success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
        unset($_SESSION['success_message']);
    }
    ?>
       
    
    <h2>User Dashboard</h2>
    
        <p>Welcome, User ID: <?= htmlspecialchars($user_id) ?></p>

        <h3>Your Complaints</h3>
        <?php if (empty($complaints)): ?>
            <p>You haven't submitted any complaints yet.</p>
        <?php else: ?>
            <div class="responsive-table">
    

            <table class="table table-striped table-hover" >

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Subject</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($complaints as $complaint): ?>
                    <tr>
                        <td><?= htmlspecialchars($complaint['id']) ?></td>
                        <td><?= htmlspecialchars($complaint['subject']) ?></td>
                        <td><?= htmlspecialchars($complaint['description']) ?></td>
                        <td><?= htmlspecialchars($complaint['status']) ?></td>
                        <td><?= htmlspecialchars($complaint['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <td>
   

            </table>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>