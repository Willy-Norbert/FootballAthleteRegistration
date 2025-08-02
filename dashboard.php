<?php
require_once 'config.php';

// Check if manager is logged in
if (!isset($_SESSION['manager_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch players added by this manager
$stmt = $pdo->prepare("SELECT * FROM players WHERE manager_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['manager_id']]);
$players = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard - Player Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="dashboard-header">
                <h1>Welcome, <?php echo htmlspecialchars($_SESSION['manager_name']); ?>!</h1>
                <a href="logout.php" class="btn logout-btn">Logout</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="dashboard-actions">
            <a href="add-player.php" class="btn">+ Add New Player</a>
            <a href="index.php" class="btn" style="background-color: #6c757d;">View Public Page</a>
        </div>
        
        <h2>Your Players (<?php echo count($players); ?>)</h2>
        
        <?php if (empty($players)): ?>
            <p class="no-players">No players added yet. <a href="add-player.php">Add your first player!</a></p>
        <?php else: ?>
            <div class="players-grid">
                <?php foreach ($players as $player): ?>
                    <div class="player-card">
                        <img src="<?php echo htmlspecialchars($player['photo'] ?: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face'); ?>" 
                             alt="<?php echo htmlspecialchars($player['name']); ?>" 
                             class="player-photo"
                             onerror="this.src='https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face'">
                        <h3><?php echo htmlspecialchars($player['name']); ?></h3>
                        <p><strong>Position:</strong> <?php echo htmlspecialchars($player['position']); ?></p>
                        <p><strong>Team:</strong> <?php echo htmlspecialchars($player['team']); ?></p>
                        <div class="player-actions">
                            <a href="edit-player.php?id=<?php echo $player['id']; ?>" class="btn">Edit</a>
                            <a href="delete-player.php?id=<?php echo $player['id']; ?>" 
                               class="btn logout-btn" 
                               onclick="return confirm('Are you sure you want to delete this player?')">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
                   <footer>
    <h6>copyright@2025 made by irabaruta</h6></footer>
    <script src="script.js"></script>
</body>
</html>