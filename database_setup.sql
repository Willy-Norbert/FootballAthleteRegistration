-- Database Setup for Rwanda Football Athlete Management System
-- Execute these commands in phpMyAdmin or MySQL command line

-- Create database
CREATE DATABASE IF NOT EXISTS football_rwanda;
USE football_rwanda;

-- Create athletes table
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
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data for testing (Famous Rwandan footballers and some fictional ones)
INSERT INTO athletes (name, team, position, age, height, market_value, rating, nationality, photo) VALUES
('Jean Baptiste Mugiraneza', 'APR FC', 'Midfielder', 28, 175.5, 85000.00, 88, 'Rwandan', 'default.jpg'),
('Eric Ndayishimiye', 'Rayon Sports FC', 'Striker', 26, 180.0, 120000.00, 91, 'Rwandan', 'default.jpg'),
('Aimable Nsabimana', 'AS Kigali', 'Defender', 24, 178.0, 65000.00, 84, 'Rwandan', 'default.jpg'),
('Innocent Nshuti', 'Police FC', 'Goalkeeper', 29, 185.0, 75000.00, 86, 'Rwandan', 'default.jpg'),
('Djihad Bizimana', 'SC Kiyovu', 'Midfielder', 23, 172.0, 55000.00, 82, 'Rwandan', 'default.jpg'),
('Muhadjiri Hakizimana', 'Mukura Victory Sports', 'Striker', 25, 176.0, 70000.00, 85, 'Rwandan', 'default.jpg'),
('Emery Bayisenge', 'Gorilla FC', 'Defender', 27, 183.0, 60000.00, 83, 'Rwandan', 'default.jpg'),
('Pierrot Kwizera', 'Espoir FC', 'Midfielder', 22, 170.0, 45000.00, 79, 'Rwandan', 'default.jpg'),
('Fitina Omborenga', 'AS Kigali Women', 'Striker', 24, 165.0, 40000.00, 87, 'Rwandan', 'default.jpg'),
('Grace Nyinawumuntu', 'APR Women FC', 'Midfielder', 23, 168.0, 38000.00, 85, 'Rwandan', 'default.jpg'),
('Sandrine Uwimana', 'Rayon Sports Women', 'Defender', 25, 170.0, 35000.00, 83, 'Rwandan', 'default.jpg'),
('Chadia Kanakuze', 'Les Lionnes FC', 'Goalkeeper', 26, 172.0, 42000.00, 86, 'Rwandan', 'default.jpg'),
('Patrick Sibomana', 'Etincelles FC', 'Defender', 30, 181.0, 50000.00, 80, 'Rwandan', 'default.jpg'),
('Yannick Mukunzi', 'Bugesera FC', 'Striker', 21, 174.0, 35000.00, 78, 'Rwandan', 'default.jpg'),
('Olivier Niyonzima', 'Sunrise FC', 'Midfielder', 28, 177.0, 58000.00, 84, 'Rwandan', 'default.jpg');

-- Create indexes for better performance
CREATE INDEX idx_name ON athletes(name);
CREATE INDEX idx_team ON athletes(team);
CREATE INDEX idx_position ON athletes(position);
CREATE INDEX idx_rating ON athletes(rating);
CREATE INDEX idx_created_at ON athletes(created_at);

-- Create a view for quick athlete statistics
CREATE VIEW athlete_stats AS
SELECT 
    position,
    COUNT(*) as total_players,
    AVG(age) as average_age,
    AVG(height) as average_height,
    AVG(rating) as average_rating,
    AVG(market_value) as average_market_value,
    MAX(market_value) as highest_market_value,
    MIN(market_value) as lowest_market_value
FROM athletes 
GROUP BY position;

-- Create a view for team statistics
CREATE VIEW team_stats AS
SELECT 
    team,
    COUNT(*) as total_players,
    AVG(rating) as average_rating,
    SUM(market_value) as total_market_value,
    AVG(market_value) as average_market_value
FROM athletes 
GROUP BY team
ORDER BY total_market_value DESC;

-- Show the created tables and views
SHOW TABLES;

-- Display sample data
SELECT 'Sample Athletes Data:' as Info;
SELECT id, name, team, position, age, rating, market_value FROM athletes LIMIT 5;

SELECT 'Position Statistics:' as Info;
SELECT * FROM athlete_stats;

SELECT 'Team Statistics (Top 5):' as Info;
SELECT * FROM team_stats LIMIT 5;

/*
SETUP INSTRUCTIONS:

1. Install XAMPP and start Apache and MySQL services

2. Open phpMyAdmin in your browser (usually http://localhost/phpmyadmin)

3. Copy and paste this entire SQL script into the SQL tab and execute it

4. The script will:
   - Create the 'football_rwanda' database
   - Create the 'athletes' table with proper structure
   - Insert sample data for testing
   - Create indexes for better performance
   - Create useful views for statistics

5. Create the following folder structure in your project:
   project-folder/
   ├── index.html
   ├── register.php
   ├── view_athletes.php
   ├── css/
   │   └── style.css
   ├── js/
   │   └── validation.js
   ├── includes/
   │   └── db_connect.php
   ├── uploads/ (create this folder for storing photos)
   └── assets/ (optional for additional images)

6. Make sure the 'uploads' folder has write permissions:
   - On Windows: Right-click folder → Properties → Security → Allow full control
   - On Linux/Mac: chmod 755 uploads/

7. Test the database connection by accessing your project in the browser

SAMPLE QUERIES FOR TESTING:

-- Get all athletes
SELECT * FROM athletes ORDER BY created_at DESC;

-- Search athletes by name
SELECT * FROM athletes WHERE name LIKE '%Jean%';

-- Get athletes by position
SELECT * FROM athletes WHERE position = 'Striker';

-- Get top rated athletes
SELECT * FROM athletes ORDER BY rating DESC LIMIT 10;

-- Get athletes by team
SELECT * FROM athletes WHERE team = 'APR FC';

-- Get athlete statistics
SELECT 
    COUNT(*) as total_athletes,
    AVG(age) as avg_age,
    AVG(rating) as avg_rating,
    MAX(market_value) as highest_value
FROM athletes;
*/