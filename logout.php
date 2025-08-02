<?php
session_start();
session_destroy();
header('Location: index.php?success=Logged out successfully');
exit;
?>