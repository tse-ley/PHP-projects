<?php 
require_once 'config.php'; 
session_start();  

if (!isset($_SESSION['user_id']) || !is_admin($pdo, $_SESSION['user_id'])) {
    header("Location: login.php");
    exit(); 
}   

$stmt = $pdo->query("SELECT c.*, u.username FROM complaints c JOIN users u ON c.user_id = u.id ORDER BY c.created_at DESC"); 
$complaints = $stmt->fetchAll(PDO::FETCH_ASSOC); 
?>  

<!DOCTYPE html>  
<html lang="en"> 
<head>     
    <meta charset="UTF-8">     
    <meta name="viewport" content="width=device-width, initial-scale=1.0">     
    <title>Admin Dashboard</title>     
    <link rel="stylesheet" href="style.css">     
    <link rel="stylesheet" href="css/bootstrap.min.css">     
    <script src="js/bootstrap.bundle.js"></script> 
</head> 
<body>     
    <header>
        <h2>Admin Dashboard</h2>
    </header>
    
    <nav class="dashboard-links">
        <a href="dashboard.php">Back to User Dashboard</a>     
        <a href="logout.php">Logout</a>     
    </nav>
    
    <main>
        <h3>All Complaints</h3>     
        <?php if (empty($complaints)): ?>         
            <p>No complaints have been submitted yet.</p>     
        <?php else: ?>         
            <div class="responsive-table">          
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>                 
                            <th>ID</th>                 
                            <th>User</th>                 
                            <th>Subject</th>                 
                            <th>Description</th>                 
                            <th>Status</th>                 
                            <th>Created At</th>                 
                            <th>Action</th>                 
                            <th>Feedback</th>             
                        </tr>
                    </thead>
                    <tbody>              
                        <?php foreach ($complaints as $complaint): ?>
                        <tr>                 
                            <td><?= htmlspecialchars($complaint['id']) ?></td>                 
                            <td><?= htmlspecialchars($complaint['username']) ?></td>                 
                            <td><?= htmlspecialchars($complaint['subject']) ?></td>                 
                            <td><?= htmlspecialchars($complaint['description']) ?></td>                 
                            <td><?= htmlspecialchars($complaint['status']) ?></td>                 
                            <td><?= htmlspecialchars($complaint['created_at']) ?></td>                 
                            <td><a href="update_status.php?id=<?= $complaint['id'] ?>">Update Status</a></td>                          
                            <td>
                                <?php if (isset($complaint['feedback']) && $complaint['feedback']): ?>         
                                    <?= htmlspecialchars($complaint['feedback']) ?>     
                                <?php else: ?>         
                                    <a href="add_feedback.php?id=<?= $complaint['id'] ?>">Add Feedback</a>     
                                <?php endif; ?>
                            </td>
                        </tr>             
                        <?php endforeach; ?>
                    </tbody>         
                </table>         
            </div>     
        <?php endif; ?>
    </main>
    
    <footer>         
        <p>&copy; 2024 Complaint Management System. All rights reserved.</p>     
    </footer> 
</body>
</html>