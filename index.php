<?php
require_once 'config.php';

// Fetch all players for public display
$stmt = $pdo->query("SELECT * FROM players ORDER BY created_at DESC");
$players = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">  
          <div class="logo"><a href=""><img src="image-removebg-preview (2).png" width="130" height="130" alt=""></a></div>
            <nav class="nav-links">
                <a href="index.php">Home</a>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            </nav>
        </div>
    </header>

    <section class="container">
      <div class="heading">
        <h2 style="color: aqua;">--- Our Rwandan Players with their positions ---</h2>
        </div>
        <?php if (empty($players)): ?>
            <p class="no-players">No players found. Managers can add players after logging in.</p>
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
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
                </section>
    <section class="container2">
      <p class="p1">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Error, temporibus? Suscipit sit laborum ut sunt adipisci maxime commodi, quibusdam aperiam perferendis consequuntur quas hic perspiciatis doloremque natus provident ullam excepturi. </p>

      <a href="login.php" class="button1">Start Manage</a>
    </section>
    
    <footer>
    <h6>copyright@2025 made by irabaruta</h6></footer>
    <script src="script.js"></script>

</body>
</html>