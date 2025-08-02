<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_POST) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill all fields';
    } else {
        // Check if manager exists
        $stmt = $pdo->prepare("SELECT * FROM managers WHERE email = ?");
        $stmt->execute([$email]);
        $manager = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($manager && password_verify($password, $manager['password'])) {
            $_SESSION['manager_id'] = $manager['id'];
            $_SESSION['manager_name'] = $manager['name'];
            $_SESSION['manager_email'] = $manager['email'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid email or password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Login - Player Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo"><a href=""><img src="image-removebg-preview (2).png" width="130" height="130" alt=""></a></div>
            <nav class="nav-links">
                <a href="index.php">← Back to Home</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="form-container">
            <h2>Login to Dashboard</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="POST" id="loginForm">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
            
            <p style="text-align: center; margin-top: 1rem;">
                Don't have an account? <a href="register.php">Register here</a>
            </p>
        </div>
    </div>
 <footer>
    <h6>copyright@2025 made by irabaruta</h6></footer>
    <script src="script.js"></script>
</body>
</html>