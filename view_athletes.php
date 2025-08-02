<?php
/**
 * View Athletes Page for Rwanda Football Registry System
 * Displays all registered athletes in a grid layout with search functionality
 * Uses simple PHP and mysqli for database operations
 */

// Include database connection
include 'includes/db_connect.php';

// Initialize search variables
$search_term = '';
$where_clause = '';

// Handle search functionality
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = sanitize_input($_GET['search']);
    $where_clause = "WHERE name LIKE '%$search_term%' OR team LIKE '%$search_term%' OR position LIKE '%$search_term%'";
}

// Fetch athletes from database
$sql = "SELECT * FROM athletes $where_clause ORDER BY created_at DESC";
$result = $conn->query($sql);

// Count total athletes
$count_sql = "SELECT COUNT(*) as total FROM athletes $where_clause";
$count_result = $conn->query($count_sql);
$total_athletes = $count_result->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Athletes - Rwanda Football Registry</title>
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
                <li><a href="register.php" class="nav-link">Register Athlete</a></li>
                <li><a href="view_athletes.php" class="nav-link active">View Athletes</a></li>
                <li><a href="index.html#contact" class="nav-link">Contact</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main style="margin-top: 100px; padding: 2rem 0; min-height: calc(100vh - 200px);">
        <div class="container">
            
            <!-- Page Header -->
            <div style="text-align: center; margin-bottom: 3rem;">
                <h1 style="color: #007bff; font-size: 2.5rem; margin-bottom: 1rem;">
                    👥 Registered Football Athletes
                </h1>
                <p style="color: #666; font-size: 1.1rem;">
                    Total Athletes: <strong><?php echo $total_athletes; ?></strong>
                    <?php if (!empty($search_term)): ?>
                        | Search Results for: "<strong><?php echo htmlspecialchars($search_term); ?></strong>"
                    <?php endif; ?>
                </p>
            </div>

            <!-- Search Section -->
            <div class="search-container">
                <form method="GET" action="view_athletes.php" class="search-box">
                    <input type="text" 
                           name="search" 
                           placeholder="🔍 Search by name, team, or position..." 
                           value="<?php echo htmlspecialchars($search_term); ?>"
                           style="padding-right: 120px;">
                    <button type="submit" 
                            style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); 
                                   background: #007bff; color: white; border: none; padding: 8px 15px; 
                                   border-radius: 20px; cursor: pointer;">
                        Search
                    </button>
                </form>
                
                <?php if (!empty($search_term)): ?>
                    <div style="margin-top: 1rem;">
                        <a href="view_athletes.php" class="btn btn-secondary" style="padding: 8px 20px;">
                            Clear Search
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Quick Action Buttons -->
            <div style="text-align: center; margin-bottom: 2rem;">
                <a href="register.php" class="btn btn-primary">➕ Add New Athlete</a>
                <button onclick="exportData()" class="btn btn-secondary" style="margin-left: 1rem;">
                    📊 Export Data
                </button>
            </div>

            <!-- Athletes Grid -->
            <?php if ($result && $result->num_rows > 0): ?>
                <div class="athletes-grid">
                    <?php while($athlete = $result->fetch_assoc()): ?>
                        <div class="athlete-card">
                            <!-- Athlete Photo -->
                            <div style="position: relative;">
                                <?php 
                                $photo_path = 'uploads/' . $athlete['photo'];
                                if ($athlete['photo'] != 'default.jpg' && file_exists($photo_path)): 
                                ?>
                                    <img src="<?php echo $photo_path; ?>" 
                                         alt="<?php echo htmlspecialchars($athlete['name']); ?>" 
                                         class="athlete-photo">
                                <?php else: ?>
                                    <div class="athlete-photo" 
                                         style="display: flex; align-items: center; justify-content: center; 
                                                color: white; font-size: 3rem; font-weight: bold;">
                                        <?php echo strtoupper(substr($athlete['name'], 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Position Badge -->
                                <div style="position: absolute; top: 10px; right: 10px; 
                                           background: rgba(0,0,0,0.7); color: white; 
                                           padding: 5px 10px; border-radius: 15px; font-size: 0.8rem;">
                                    <?php echo htmlspecialchars($athlete['position']); ?>
                                </div>
                            </div>

                            <!-- Athlete Information -->
                            <div class="athlete-info">
                                <h3 class="athlete-name">
                                    <?php echo htmlspecialchars($athlete['name']); ?>
                                </h3>
                                
                                <div class="athlete-details">
                                    <strong>🏟️ Team:</strong> <?php echo htmlspecialchars($athlete['team']); ?>
                                </div>
                                
                                <div class="athlete-details">
                                    <strong>🎂 Age:</strong> <?php echo $athlete['age']; ?> years old
                                </div>
                                
                                <div class="athlete-details">
                                    <strong>📏 Height:</strong> <?php echo $athlete['height']; ?> cm
                                </div>
                                
                                <div class="athlete-details">
                                    <strong>💰 Market Value:</strong> 
                                    <?php echo number_format($athlete['market_value']); ?> RWF
                                </div>
                                
                                <div class="athlete-details">
                                    <strong>🇷🇼 Nationality:</strong> <?php echo htmlspecialchars($athlete['nationality']); ?>
                                </div>

                                <!-- Rating Badge -->
                                <div class="rating-badge">
                                    ⭐ Rating: <?php echo $athlete['rating']; ?>/100
                                </div>

                                <!-- Registration Date -->
                                <div style="margin-top: 1rem; color: #999; font-size: 0.85rem;">
                                    Registered: <?php echo date('M j, Y', strtotime($athlete['created_at'])); ?>
                                </div>

                                <!-- Action Buttons -->
                                <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                                    <button onclick="viewDetails(<?php echo $athlete['id']; ?>)" 
                                            style="flex: 1; background: #007bff; color: white; border: none; 
                                                   padding: 8px; border-radius: 5px; cursor: pointer;">
                                        👁️ View
                                    </button>
                                    <button onclick="editAthlete(<?php echo $athlete['id']; ?>)" 
                                            style="flex: 1; background: #28a745; color: white; border: none; 
                                                   padding: 8px; border-radius: 5px; cursor: pointer;">
                                        ✏️ Edit
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination (if needed in future) -->
                <div style="text-align: center; margin-top: 3rem;">
                    <p style="color: #666;">
                        Showing all <?php echo $total_athletes; ?> registered athletes
                    </p>
                </div>

            <?php else: ?>
                <!-- No Athletes Found -->
                <div style="text-align: center; padding: 4rem 2rem; background: white; 
                           border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                    
                    <?php if (!empty($search_term)): ?>
                        <h2 style="color: #666;">🔍 No athletes found</h2>
                        <p style="color: #666; margin: 1rem 0;">
                            No athletes match your search for "<strong><?php echo htmlspecialchars($search_term); ?></strong>"
                        </p>
                        <a href="view_athletes.php" class="btn btn-primary">View All Athletes</a>
                    <?php else: ?>
                        <h2 style="color: #666;">⚽ No athletes registered yet</h2>
                        <p style="color: #666; margin: 1rem 0;">
                            Be the first to register a football athlete in our system!
                        </p>
                        <a href="register.php" class="btn btn-primary">Register First Athlete</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

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

    <!-- JavaScript for Interactive Features -->
    <script>
        /**
         * View athlete details in a modal or alert
         */
        function viewDetails(athleteId) {
            // For now, show an alert. In a full system, this would open a modal
            alert('Viewing details for Athlete ID: ' + athleteId + '\n\nIn a full system, this would show detailed athlete information in a modal window.');
            
            // Future enhancement: Open modal with detailed athlete information
            // You could make an AJAX call to get more details
        }

        /**
         * Edit athlete information
         */
        function editAthlete(athleteId) {
            // For now, show an alert. In a full system, this would redirect to edit page
            alert('Editing Athlete ID: ' + athleteId + '\n\nIn a full system, this would redirect to an edit form.');
            
            // Future enhancement: Redirect to edit page
            // window.location.href = 'edit_athlete.php?id=' + athleteId;
        }

        /**
         * Export athlete data (placeholder function)
         */
        function exportData() {
            alert('Export functionality would be implemented here.\n\nThis could export to CSV, Excel, or PDF format.');
            
            // Future enhancement: Generate and download CSV/Excel file
            // window.location.href = 'export_athletes.php?format=csv';
        }

        /**
         * Initialize search functionality
         */
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🏆 Athletes page loaded successfully!');
            
            // Add keyboard shortcut for search
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.key === 'f') {
                    e.preventDefault();
                    document.querySelector('input[name="search"]').focus();
                }
            });
            
            // Auto-focus search if there's a search term
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput && searchInput.value === '') {
                // Only auto-focus if no search term is present
                // searchInput.focus();
            }
            
            // Add loading state to search
            const searchForm = document.querySelector('.search-box');
            searchForm.addEventListener('submit', function() {
                const submitButton = this.querySelector('button[type="submit"]');
                submitButton.textContent = 'Searching...';
                submitButton.disabled = true;
            });
        });

        /**
         * Filter athletes by position (client-side filtering)
         */
        function filterByPosition(position) {
            const athleteCards = document.querySelectorAll('.athlete-card');
            
            athleteCards.forEach(card => {
                const positionBadge = card.querySelector('div[style*="position: absolute"]');
                if (position === 'all' || positionBadge.textContent.trim() === position) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        /**
         * Sort athletes by different criteria
         */
        function sortAthletes(criteria) {
            const athletesGrid = document.querySelector('.athletes-grid');
            const athleteCards = Array.from(document.querySelectorAll('.athlete-card'));
            
            athleteCards.sort((a, b) => {
                let valueA, valueB;
                
                switch(criteria) {
                    case 'name':
                        valueA = a.querySelector('.athlete-name').textContent.toLowerCase();
                        valueB = b.querySelector('.athlete-name').textContent.toLowerCase();
                        return valueA.localeCompare(valueB);
                        
                    case 'rating':
                        valueA = parseInt(a.querySelector('.rating-badge').textContent.match(/\d+/)[0]);
                        valueB = parseInt(b.querySelector('.rating-badge').textContent.match(/\d+/)[0]);
                        return valueB - valueA; // Descending order
                        
                    case 'age':
                        valueA = parseInt(a.querySelector('.athlete-details:nth-child(3)').textContent.match(/\d+/)[0]);
                        valueB = parseInt(b.querySelector('.athlete-details:nth-child(3)').textContent.match(/\d+/)[0]);
                        return valueA - valueB; // Ascending order
                        
                    default:
                        return 0;
                }
            });
            
            // Re-append sorted cards
            athleteCards.forEach(card => athletesGrid.appendChild(card));
        }

        // Add some visual feedback for card interactions
        document.addEventListener('DOMContentLoaded', function() {
            const athleteCards = document.querySelectorAll('.athlete-card');
            
            athleteCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px) scale(1.02)';
                    this.style.transition = 'all 0.3s ease';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        });
    </script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>