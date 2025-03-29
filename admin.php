<?php
    include 'inc/head.inc.php';
    include 'inc/adminbar.inc.php';
    require_once __DIR__ . '/inc/cookie_admin.php';  //For y'alls to work on that page without a hassle
    //include 'inc/jwt.php'; // This is used for authentication


    // If there is a logout button being implemented the in future
    // Here is the generic logout function, this will destroy the session cookies, jwt token
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

    // INCASE I FORGET, TELL ME IF YOU'RE ADDING ANY POST REQUEST(FORMS) BECAUSE I NEED TO ADD IN CSRF

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cemetery Management System - Admin Page</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
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
                    <p>No new notifications.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to toggle the sidebar
        function toggleSidebar() {
            const sideMenu = document.getElementById('side-menu');
            sideMenu.classList.toggle('active');

            // Adjust the body content margin when the sidebar is toggled
            const content = document.querySelector('.content');
            if (sideMenu.classList.contains('active')) {
                content.style.marginLeft = '220px'; /* Sidebar width + padding */
            } else {
                content.style.marginLeft = '0';
            }
        }
    </script>
</body>
</html>