<?php
// Start with all PHP processing first, before any HTML output
// 1. Check if logout action is requested before any output
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logout();
    header("Location: index.php");
    exit();
}

// Define the logout function
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
}

// Load required files for database and other functions
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'inc/sql.inc.php';
$conn = getDatabaseConnection();
//require_once __DIR__ . '/inc/cookie_admin.php'; 

// Buffer the output to manipulate it before sending
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/feedback.css">
    <script src="js/admin.js"></script>
    <style>
        #logout-container {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 1000;
        }
        .logout-link {
            text-decoration: none;
            background: #c00; 
            color: #fff; 
            padding: 8px 16px; 
            border-radius: 4px;
            display: inline-block;
        }
        /* Fix for the center positioning - instead of position: center */
        .centered-element {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
        
        /* Copy of sidebar styles to avoid the style tag in body */
        .side-menu {
            width: 200px;
            background-color: #333;
            color: white;
            padding: 20px;
            height: calc(100vh - 100px);
            position: fixed;
            left: -220px;
            top: 105px;
            transition: left 0.3s ease;
            z-index: 1000;
        }

        .side-menu.active {
            left: 0; /* Show sidebar */
        }

        .table-container {
            overflow-x: auto;
            width: 100%;
            transition : all 0.3s ease;
        }

        .sidebar-open .table-container {
            width: calc(100% - 220px); /* Adjust width when sidebar is open */
        }

        .sidebar-open table {
            width: 100%;
            min-width: 1000px;
        }

        .side-menu h2 {
            color: white;
            top: 50px;
            margin-bottom: 20px;
            margin-left: 40px; /* Add space for the toggle button */
        }

        .side-menu ul {
            list-style: none;
            position: relative; /* Changed from center to a valid value */
            padding: 0;
        }

        .side-menu ul li {
            margin: 15px 0;
        }

        .side-menu ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }

        .side-menu ul li a:hover {
            color: #3498db; /* Highlight color on hover */
        }

        /* Toggle Button Styles */
        #toggle-sidebar {
            background-color: #333;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            position: fixed; /* Fixed position */
            left: 0; /* Position on the left edge of the screen */
            top: 105px; /* Align with the top of the sidebar */
            padding: 5px 10px;
            z-index: 1001; /* Ensure it's above the sidebar */
            transition: left 0.3s ease; /* Smooth transition */
        }

        @media (max-width: 768px) {
            .side-menu {
                width: 100% !important; /* Full width on mobile */
                left: -100% !important; /* Hide completely off-screen */
                height: 100vh !important; /* Full viewport height */
                z-index: 10000 !important; /* Ensure it's above everything */
            }

            .side-menu.active {
                left: 0 !important; /* Slide in from left */
            }
            
            #toggle-sidebar {
                position: fixed !important;
                left: 20px !important;
                top: 20px !important;
                z-index: 1001 !important;
                transition: left 0.3s ease !important;
            }

            .side-menu.active ~ #toggle-sidebar {
                left: 300px !important; /* Keep button visible */
            }

            .side-menu.active ~ .sidebar-overlay {
                display: none !important; 
            }
        }
    </style>
</head>

<body>
<?php include "inc/head.inc.php"; ?>

<!-- Include just the HTML part of adminbar, not the style tag -->
<button id="toggle-sidebar" onclick="toggleSidebar()">☰</button>
<div class="side-menu" id="side-menu">
    <a href= "admin.php"><h2>Menu</h2></a>
    <ul>
        <li><a href="addgraves.php">Add Grave</a></li>
        <li><a href="viewgraves.php">View Graves</a></li>
        <li><a href="feedback.php">View Feedback</a></li>
    </ul>
</div>

<div class="sidebar-overlay" onclick="toggleSidebar()"></div>
<script src="js/admin.js"></script>

<main>
    <div id="logout-container">
        <a href="admin.php?action=logout" class="logout-link">Logout</a>
    </div>
    
    <div class="content">
        <div class="header-container">
            <h1>View Feedback</h1>
            <div class="search-container">
                <form action="feedback.php" method="GET">
                    <input type="text" name="search" id="search-input" placeholder="Search feedback..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="search-btn">Search</button>
                    <?php if(isset($_GET['search']) && !empty($_GET['search'])): ?>
                        <a href="feedback.php" class="clear-btn">Clear Search</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <table class="feedback-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                // Debug output (temporary)
                if(isset($_GET['search'])) {
                    echo "<!-- Search term: ".htmlspecialchars($_GET['search'])." -->";
                }
                
                try {
                    // Base SQL query for viewfeedback
                    $sql = 'SELECT * FROM Feedback';
                    
                    // Search function
                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                        $searchTerm = '%' . $_GET['search'] . '%';
                        $sql .= ' WHERE Feedback_Name LIKE ? OR Feedback_Email LIKE ? OR Feedback_Msg LIKE ?';
                    }

                    // Prepare statement after knowing if search or js view total
                    $query = $conn->prepare($sql);
                    
                    if ($query === false) {
                        throw new Exception("Prepare failed: " . $conn->error);
                    }
                    
                    // Bind parameters if searching
                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                        $query->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);
                    }
                    
                    $query->execute();
                    $query->store_result();
                    $query->bind_result($id, $name, $email, $message, $submittedAt);
                    
                    if($query->num_rows == 0){
                        echo "<tr><td colspan='6'>No feedback found" . 
                             (isset($_GET['search']) ? " matching '" . htmlspecialchars($_GET['search']) . "'" : " in database") . 
                             "</td></tr>";
                    }
                    
                    while ($query->fetch()){
                        echo "<tr>
                        <td data-label='ID'>".htmlspecialchars($id)."</td>
                        <td data-label='Name'>".htmlspecialchars($name)."</td>
                        <td data-label='Email'>".htmlspecialchars($email)."</td>
                        <td data-label='Message'>".htmlspecialchars($message)."</td>
                        <td data-label='Submitted At'>".htmlspecialchars($submittedAt)."</td>
                        <td data-label='Actions'>
                            <a href='deletefeedback.php?id=".htmlspecialchars($id)."' class='action-btn'>Delete</a>
                        </td>          
                        </tr>";
                    }
                } catch (Exception $e) {
                    echo "<tr><td colspan='6'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>

<?php include "inc/footer.inc.php"; ?>
</body>
</html>
<?php
// End output buffering and send the page
ob_end_flush();
?>