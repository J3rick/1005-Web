<?php
// 1. Check if logout action is requested before any output
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logout();
    header("Location: index.php");
    exit();
}
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    include 'inc/head.inc.php';
    include 'inc/adminbar.inc.php';
    include 'inc/sql.inc.php';
    include 'inc/footer.inc.php';
    $conn = getDatabaseConnection();
    require_once __DIR__ . '/inc/cookie_admin.php'; 

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
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/viewgraves.css">
    <script src = "js/admin.js"></script>
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
        <div class = "header-container">
            <h1>View Graves</h1>
            <div class="search-container">
                <form action="" method="GET">
                    <input type="text" name="search" id="search-input" placeholder="Search graves..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="search-btn">Search</button>
                    <?php if(isset($_GET['search']) && !empty($_GET['search'])): ?>
                        <a href="viewgraves.php" class="clear-btn">Clear Search</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <table class="graves-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Date of Birth</th>
                    <th>Date of Death</th>
                    <th>Age</th>
                    <th>Religion</th>
                    <th>Plot</th>
                    <th>Image</th>
                    <th>Resting Type</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
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
                    // Base SQL query for viewgraves
                    $sql = 'SELECT * FROM Memorial_Map.Memorial_Map_Data';
                    
                    // Search function
                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                        $searchTerm = '%' . $_GET['search'] . '%';
                        $sql .= ' WHERE Name LIKE ? OR PlotNumber LIKE ? OR Religion LIKE ? OR RestingType LIKE ?';
                    }

                    //Prepare statement after knowing if search or js view total
                    $query = $conn->prepare($sql);
                    
                    if ($query === false) {
                        throw new Exception("Prepare failed: " . $conn->error);
                    }
                    
                    // Bind parameters if searching
                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                        $query->bind_param('ssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm);
                    }
                    
                    $query->execute();
                    $query->store_result();
                    $query->bind_result($id, $name, $dob, $dod, $age, $religion, $plot, $image, $restingType, $latitude, $longitude);
                    
                    if($query->num_rows == 0){
                        echo "<tr><td colspan='12'>No graves found" . 
                             (isset($_GET['search']) ? " matching '" . htmlspecialchars($_GET['search']) . "'" : " in database") . 
                             "</td></tr>";
                    }
                    
                    while ($query->fetch()){
                        echo "<tr>
                        <td data-label='ID'><span>$id</span></td>
                        <td data-label='Name'><span>$name</span></td>
                        <td data-label='Birth'>$dob</td>
                        <td data-label='Death'>$dod</td>
                        <td data-label='Age'>$age</td>
                        <td data-label='Religion'>$religion</td>
                        <td data-label='Plot'>$plot</td>
                        <td data-label='Image'>$image</td>
                        <td data-label='RestingType'>$restingType</td>
                        <td data-label='Latitude'>$latitude</td>
                        <td data-label='Longtitude'>$longitude</td>
                        <td data-label='Actions'>
                            <a href='editgraves.php?id=$id' class='action-btn'>Edit</a>
                            <a href='deletegraves.php?id=$id' class='action-btn'>Delete</a>
                        </td>          
                        </tr>";
                    }
                } catch (Exception $e) {
                    echo "<tr><td colspan='12'>Error: " . $e->getMessage() . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>