<?php
// 1. Check if logout action is requested before any output
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logout();
    header("Location: index.php");
    exit();
}
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    include 'inc/adminbar.inc.php';
    include 'inc/sql.inc.php';
    include 'inc/footer.inc.php';
    $conn = getDatabaseConnection();
    //require_once __DIR__ . '/inc/cookie_admin.php'; 
    
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cemetery Management System</title>
    <?php
    include "inc/head.inc.php"
    ?>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/feedback.css">
    <script src="js/admin.js"></script>
</head>

<body>
    <!-- Fixed Logout Button at the Top Right -->
    <div id="logout-container" style="position: fixed; top: 10px; right: 10px; z-index: 1000;">
        <a 
            href="admin.php?action=logout" 
            style="text-decoration: none; background: #c00; color: #fff; padding: 8px 16px; border-radius: 4px;"
        >
            Logout
        </a>
    </div>
    <div class="content">
        <div class="header-container">
            <h1>View Feedback</h1>
            <div class="search-container">
                <form action="" method="GET">
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
                        <td data-label='ID'>$id</td>
                        <td data-label='Name'>$name</td>
                        <td data-label='Email'>$email</td>
                        <td data-label='Message'>$message</td>
                        <td data-label='Submitted At'>$submittedAt</td>
                        <td data-label='Actions'>
                            <a href='deletefeedback.php?id=$id' class='action-btn'>Delete</a>
                        </td>          
                        </tr>";
                    }
                } catch (Exception $e) {
                    echo "<tr><td colspan='6'>Error: " . $e->getMessage() . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>