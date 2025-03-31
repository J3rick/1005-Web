<?php
// 1. Check if logout action is requested before any output
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logout();
    header("Location: index.php");
    exit();
}

// 2. Include your required files
include 'inc/head.inc.php';
include 'inc/adminbar.inc.php';
include 'inc/sql.inc.php';
include 'inc/footer.inc.php';
// require_once __DIR__ . '/inc/cookie_admin.php';  // Uncomment if needed

// 3. Define the logout function
function logout() {
    session_start();
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    
    // Clear JWT token
    setcookie("jwt_token", "", time() - 3600, "/");
}

// NOTE: If you add any POST requests (forms), remember to include CSRF protection

// 4. Fetch recent feedback from the database
$conn = getDatabaseConnection();
$query = $conn->prepare("SELECT * FROM Memorial_Map.Feedback ORDER BY Submitted_At DESC LIMIT 3"); 
$query->execute();
$recentFeedback = $query->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cemetery Management System - Admin Page</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/admin.css">
    <script src="js/admin.js"></script>
</head>
<body>
    <!-- Top Menu / Navbar (with Logout button on the right) -->
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px;">
        <!-- Left side could be your logo, site name, or menu -->
        <div>
            <strong>MemorialMap Admin</strong>
        </div>
        
        <!-- Right side: Logout button -->
        <div>
            <a 
                href="admin.php?action=logout" 
                style="text-decoration: none; background: #c00; color: #fff; padding: 8px 16px; border-radius: 4px;"
            >
                Logout
            </a>
        </div>
    </div>

    <div class="content">
        <div class="main-content">
            <!-- Large Rectangle (Top) -->
            <div class="large-rectangle">
                <!-- Pie Chart -->
                <div class="pie-chart">
                    <h3>Cemetery Occupancy</h3>
                    <div id="pie-chart"></div> <!-- Placeholder for pie chart -->
                </div>

                <!-- Stats -->
                <div class="stats">
                    <h3>Statistics</h3>
                    <p>Total Graves: 500</p>
                    <p>Occupied Graves: 350</p>
                    <p>Available Graves: 150</p>
                    <p>Percentage Full: 70%</p>
                </div>
            </div>

            <!-- Two Smaller Rectangles (Bottom) -->
            <div class="small-rectangles">
                <!-- Rectangle 1 -->
                <div class="small-rectangle">
                    <h3>Recent Activity</h3>
                    <p>No recent activity.</p>
                </div>

                <!-- Rectangle 2 -->
                <div class="small-rectangle">
                    <h3>Notifications</h3>
                    <?php if (!empty($recentFeedback)): ?>
                        <div class="feedback-notifications" tabindex="0" role="region" aria-label="Feedback Notifications">
                            <?php foreach ($recentFeedback as $feedback): ?>
                                <div class="feedback-item">
                                    <br>
                                    <strong>User:</strong> <?= htmlspecialchars($feedback['Feedback_Name']) ?> 
                                    <strong>Email:</strong> <?= htmlspecialchars($feedback['Feedback_Email']) ?>
                                    <br>
                                    <strong>Received at:</strong>
                                    <small><?= htmlspecialchars($feedback['Submitted_At']) ?></small>
                                    <p><strong>Feedback message:</strong> 
                                       <?= htmlspecialchars(substr($feedback['Feedback_Msg'], 0, 50)) ?>...
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>No new notifications.</p>
                    <?php endif; ?>
                    <br>
                    <p><a href="feedback.php">View All Feedback</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
