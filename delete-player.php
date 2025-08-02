<?php
require_once 'config.php';

// Check if manager is logged in
if (!isset($_SESSION['manager_id'])) {
    header('Location: login.php');
    exit;
}

$playerId = $_GET['id'] ?? 0;

// Delete player (only if owned by current manager)
$stmt = $pdo->prepare("DELETE FROM players WHERE id = ? AND manager_id = ?");
$deleted = $stmt->execute([$playerId, $_SESSION['manager_id']]);

if ($deleted && $stmt->rowCount() > 0) {
    header('Location: dashboard.php?success=Player deleted successfully');
} else {
    header('Location: dashboard.php?error=Player not found or access denied');
}
exit;
?>
