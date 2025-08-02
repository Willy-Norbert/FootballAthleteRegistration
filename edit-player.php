<?php
require_once 'config.php';

// Check if manager is logged in
if (!isset($_SESSION['manager_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';
$player = null;

// Get player ID
$playerId = $_GET['id'] ?? 0;

// Fetch player data
$stmt = $pdo->prepare("SELECT * FROM players WHERE id = ? AND manager_id = ?");
$stmt->execute([$playerId, $_SESSION['manager_id']]);
$player = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$player) {
    header('Location: dashboard.php?error=Player not found');
    exit;
}

if ($_POST) {
    $name = trim($_POST['name']);
    $position = $_POST['position'];
    $team = trim($_POST['team']);
    $photo = trim($_POST['photo']);
    
    if (empty($name) || empty($position) || empty($team)) {
        $error = 'Please fill all required fields';
    } else {
        // Update player
        $stmt = $pdo->prepare("UPDATE players SET name = ?, position = ?, team = ?, photo = ? WHERE id = ? AND manager_id = ?");
        
        if ($stmt->execute([$name, $position, $team, $photo, $playerId, $_SESSION['manager_id']])) {
            header('Location: dashboard.php?success=Player updated successfully');
            exit;
        } else {
            $error = 'Failed to update player. Please try again.';
        }
    }
} else {
    // Pre-fill form with existing data
    $_POST = $player;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Player - Player Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Edit Player</h1>
            <nav class="nav-links">
                <a href="dashboard.php">← Back to Dashboard</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="form-container">
            <h2>Update Player Information</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" id="editPlayerForm">
                <div class="form-group">
                    <label for="name">Player Name: *</label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="position">Position: *</label>
                    <select id="position" name="position" required>
                        <option value="">Select Position</option>
                        <option value="Goalkeeper" <?php echo ($_POST['position'] ?? '') === 'Goalkeeper' ? 'selected' : ''; ?>>Goalkeeper</option>
                        <option value="Defender" <?php echo ($_POST['position'] ?? '') === 'Defender' ? 'selected' : ''; ?>>Defender</option>
                        <option value="Midfielder" <?php echo ($_POST['position'] ?? '') === 'Midfielder' ? 'selected' : ''; ?>>Midfielder</option>
                        <option value="Forward" <?php echo ($_POST['position'] ?? '') === 'Forward' ? 'selected' : ''; ?>>Forward</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="team">Team: *</label>
                    <input type="text" id="team" name="team" required 
                           value="<?php echo htmlspecialchars($_POST['team'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="photo">Photo URL:</label>
                    <input type="url" id="photo" name="photo" 
                           placeholder="https://example.com/photo.jpg"
                           value="<?php echo htmlspecialchars($_POST['photo'] ?? ''); ?>">
                    <small>Optional: Enter a valid image URL</small>
                </div>
                <button type="submit" class="btn">Update Player</button>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>