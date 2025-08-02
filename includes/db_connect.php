<?php
/**
 * Database Connection File for Rwanda Football Registry System
 * This file establishes connection to MySQL database using mysqli
 * Simple and secure connection setup for XAMPP environment
 */

// Database configuration
$host = 'localhost';           // XAMPP default host
$username = 'root';            // XAMPP default username
$password = '';                // XAMPP default password (empty)
$database = 'football_rwanda'; // Our database name

// Create connection using mysqli
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8 for proper character encoding
$conn->set_charset("utf8");

// Optional: Display success message (remove in production)
// echo "Connected successfully to Rwanda Football Database";

/**
 * Function to sanitize input data
 * Prevents SQL injection and XSS attacks
 */
function sanitize_input($data) {
    global $conn;
    $data = trim($data);                    // Remove whitespace
    $data = stripslashes($data);            // Remove backslashes
    $data = htmlspecialchars($data);        // Convert special chars
    $data = $conn->real_escape_string($data); // Escape SQL characters
    return $data;
}

/**
 * Function to validate file upload
 * Checks file type and size for security
 */
function validate_image($file) {
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
    $max_size = 2 * 1024 * 1024; // 2MB in bytes
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // Check if file type is allowed
    if (!in_array($file_extension, $allowed_types)) {
        return array('success' => false, 'message' => 'Only JPG, PNG and GIF files are allowed');
    }
    
    // Check file size
    if ($file['size'] > $max_size) {
        return array('success' => false, 'message' => 'File size must be less than 2MB');
    }
    
    return array('success' => true, 'message' => 'File is valid');
}

// Keep connection open for other files to use
// Connection will be closed automatically when script ends
?>

<!-- 
DATABASE SETUP INSTRUCTIONS:

1. Start XAMPP and ensure Apache and MySQL are running
2. Open phpMyAdmin (http://localhost/phpmyadmin)
3. Create a new database called 'football_rwanda'
4. Run the following SQL commands to create the athletes table:

CREATE DATABASE football_rwanda;
USE football_rwanda;

CREATE TABLE athletes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    team VARCHAR(255) NOT NULL,
    position VARCHAR(50) NOT NULL,
    age INT NOT NULL,
    height DECIMAL(5,2) NOT NULL,
    market_value DECIMAL(10,2) NOT NULL,
    rating INT NOT NULL,
    nationality VARCHAR(100) DEFAULT 'Rwandan',
    photo VARCHAR(255) DEFAULT 'default.jpg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert some sample data for testing
INSERT INTO athletes (name, team, position, age, height, market_value, rating, nationality, photo) VALUES
('Jean Baptiste Uwimana', 'Rayon Sports FC', 'Midfielder', 24, 175.5, 50000.00, 85, 'Rwandan', 'default.jpg'),
('Eric Ndayishimiye', 'APR FC', 'Striker', 26, 180.0, 75000.00, 88, 'Rwandan', 'default.jpg'),
('Grace Mukamana', 'AS Kigali', 'Defender', 22, 168.0, 35000.00, 82, 'Rwandan', 'default.jpg');

5. Make sure to create an 'uploads' folder in your project directory for storing photos
6. Set proper permissions on the uploads folder (chmod 755 on Linux/Mac)
-->