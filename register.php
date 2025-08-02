<?php
/**
 * Athlete Registration Form for Rwanda Football Registry System
 * Handles both form display and form submission
 * Uses simple PHP with mysqli for database operations
 */

// Include database connection
include 'includes/db_connect.php';

// Initialize variables
$success_message = '';
$error_message = '';
$form_data = array(); // Store form data for repopulation on error

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get and sanitize form data
    $name = sanitize_input($_POST['name']);
    $team = sanitize_input($_POST['team']);
    $position = sanitize_input($_POST['position']);
    $age = (int)$_POST['age'];
    $height = (float)$_POST['height'];
    $market_value = (float)$_POST['market_value'];
    $rating = (int)$_POST['rating'];
    $nationality = sanitize_input($_POST['nationality']);
    
    // Store form data for repopulation
    $form_data = $_POST;
    
    // Validation
    $errors = array();
    
    if (empty($name)) $errors[] = "Name is required";
    if (empty($team)) $errors[] = "Team is required";
    if (empty($position)) $errors[] = "Position is required";
    if ($age < 15 || $age > 40) $errors[] = "Age must be between 15 and 40";
    if ($height < 150 || $height > 220) $errors[] = "Height must be between 150-220 cm";
    if ($rating < 1 || $rating > 100) $errors[] = "Rating must be between 1 and 100";
    
    // Handle file upload
    $photo_name = 'default.jpg'; // Default photo
    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $validation = validate_image($_FILES['photo']);
        
        if ($validation['success']) {
            // Create uploads directory if it doesn't exist
            if (!file_exists('uploads')) {
                mkdir('uploads', 0755, true);
            }
            
            // Generate unique filename
            $file_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $photo_name = uniqid() . '_' . time() . '.' . $file_extension;
            $upload_path = 'uploads/' . $photo_name;
            
            // Move uploaded file
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                $errors[] = "Failed to upload photo";
            }
        } else {
            $errors[] = $validation['message'];
        }
    }
    
    // If no errors, insert into database
    if (empty($errors)) {
        $sql = "INSERT INTO athletes (name, team, position, age, height, market_value, rating, nationality, photo) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiidiss", $name, $team, $position, $age, $height, $market_value, $rating, $nationality, $photo_name);
        
        if ($stmt->execute()) {
            $success_message = "Athlete registered successfully! Registration ID: " . $conn->insert_id;
            $form_data = array(); // Clear form data on success
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        $error_message = implode('<br>', $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Athlete - Rwanda Football Registry</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <h2>🏆 Rwanda Football Registry</h2>
            </div>
            <ul class="nav-menu">
                <li><a href="index.html" class="nav-link">Home</a></li>
                <li><a href="register.php" class="nav-link active">Register Athlete</a></li>
                <li><a href="view_athletes.php" class="nav-link">View Athletes</a></li>
                <li><a href="index.html#contact" class="nav-link">Contact</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main style="margin-top: 100px; padding: 2rem 0; min-height: calc(100vh - 200px);">
        <div class="container">
            <div class="form-container">
                <h1 style="text-align: center; color: #007bff; margin-bottom: 2rem;">
                    ⚽ Register New Football Athlete
                </h1>
                
                <!-- Success Message -->
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Error Message -->
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-error">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <!-- Registration Form -->
                <form action="register.php" method="POST" enctype="multipart/form-data" id="athleteForm">
                    
                    <!-- Personal Information Section -->
                    <h3 style="color: #28a745; margin-bottom: 1rem;">👤 Personal Information</h3>
                    
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" required 
                               value="<?php echo isset($form_data['name']) ? htmlspecialchars($form_data['name']) : ''; ?>"
                               placeholder="Enter athlete's full name">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="age">Age *</label>
                            <input type="number" id="age" name="age" min="15" max="40" required
                                   value="<?php echo isset($form_data['age']) ? $form_data['age'] : ''; ?>"
                                   placeholder="Age (15-40)">
                        </div>
                        <div class="form-group">
                            <label for="nationality">Nationality</label>
                            <input type="text" id="nationality" name="nationality" 
                                   value="<?php echo isset($form_data['nationality']) ? htmlspecialchars($form_data['nationality']) : 'Rwandan'; ?>"
                                   placeholder="Player nationality">
                        </div>
                    </div>

                    <!-- Football Information Section -->
                    <h3 style="color: #28a745; margin: 2rem 0 1rem 0;">⚽ Football Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="team">Team/Club *</label>
                            <input type="text" id="team" name="team" required
                                   value="<?php echo isset($form_data['team']) ? htmlspecialchars($form_data['team']) : ''; ?>"
                                   placeholder="e.g., APR FC, Rayon Sports">
                        </div>
                        <div class="form-group">
                            <label for="position">Position *</label>
                            <select id="position" name="position" required>
                                <option value="">Select Position</option>
                                <option value="Goalkeeper" <?php echo (isset($form_data['position']) && $form_data['position'] == 'Goalkeeper') ? 'selected' : ''; ?>>Goalkeeper</option>
                                <option value="Defender" <?php echo (isset($form_data['position']) && $form_data['position'] == 'Defender') ? 'selected' : ''; ?>>Defender</option>
                                <option value="Midfielder" <?php echo (isset($form_data['position']) && $form_data['position'] == 'Midfielder') ? 'selected' : ''; ?>>Midfielder</option>
                                <option value="Striker" <?php echo (isset($form_data['position']) && $form_data['position'] == 'Striker') ? 'selected' : ''; ?>>Striker</option>
                            </select>
                        </div>
                    </div>

                    <!-- Physical & Performance Section -->
                    <h3 style="color: #28a745; margin: 2rem 0 1rem 0;">📊 Physical & Performance Data</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="height">Height (cm) *</label>
                            <input type="number" id="height" name="height" step="0.1" min="150" max="220" required
                                   value="<?php echo isset($form_data['height']) ? $form_data['height'] : ''; ?>"
                                   placeholder="Height in centimeters">
                        </div>
                        <div class="form-group">
                            <label for="rating">Rating (1-100) *</label>
                            <input type="number" id="rating" name="rating" min="1" max="100" required
                                   value="<?php echo isset($form_data['rating']) ? $form_data['rating'] : ''; ?>"
                                   placeholder="Player rating out of 100">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="market_value">Market Value (RWF) *</label>
                        <input type="number" id="market_value" name="market_value" step="1000" min="0" required
                               value="<?php echo isset($form_data['market_value']) ? $form_data['market_value'] : ''; ?>"
                               placeholder="Market value in Rwandan Francs">
                    </div>

                    <!-- Photo Upload Section -->
                    <h3 style="color: #28a745; margin: 2rem 0 1rem 0;">📸 Profile Photo</h3>
                    
                    <div class="form-group">
                        <label for="photo">Upload Photo (Optional)</label>
                        <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png,.gif">
                        <small style="color: #666; display: block; margin-top: 0.5rem;">
                            Accepted formats: JPG, PNG, GIF. Maximum size: 2MB
                        </small>
                    </div>

                    <!-- Submit Button -->
                    <div style="text-align: center; margin-top: 3rem;">
                        <button type="submit" class="btn btn-primary" style="padding: 15px 40px; font-size: 1.1rem;">
                            🚀 Register Athlete
                        </button>
                        <a href="view_athletes.php" class="btn btn-secondary" style="margin-left: 1rem; padding: 15px 40px; font-size: 1.1rem;">
                            👥 View All Athletes
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; 2024 Rwanda Football Athlete Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript for Form Validation -->
    <script src="js/validation.js"></script>
</body>
</html>