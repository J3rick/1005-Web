<?php
    include 'inc/head.inc.php';
    include 'inc/adminbar.inc.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cemetery Management System - Admin Page</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/admin.css">
    <style>
 
    </style>
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