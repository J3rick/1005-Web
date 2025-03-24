<link rel="stylesheet" href="css/main.css">

<!-- For error debugging -->
<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
?>

<?php

// Start secure session
//ini_set('session.cookie_secure', 1); // If I can set up HTTPS then uncomment this - Derrick

ini_set('session.cookie_httponly', 1);

 // Session Cookies
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_lifetime', 0);

session_start();

$username = $errorMsg = "";
$success = true;

// CSRF token validation
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $errorMsg .= "CSRF token validation failed.<br>";
    $success = false;
}

// Validate username
if (empty($_POST["username"])) {
    $errorMsg .= "Username is required.<br>";
    $success = false;
} else {
    $username = sanitize_input($_POST["username"]);
}

// Validate password
if (empty($_POST["pwd"])) {
    $errorMsg .= "Password is required.<br>";
    $success = false;
} else {
    $pwd = $_POST["pwd"]; // Don't sanitize passwords
}

if ($success) {
    authenticateUser();
}

// Process the login result
if ($success) {

     // Regenerate session ID for security
    session_regenerate_id(true);

    // Start a session to store user information
    session_start();
    $_SESSION['username'] = $username;
    $_SESSION['admin'] = true;
    $_SESSION['last_activity'] = time();

    // Generate a new CSRF token for the next request
     $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    // Redirect to admin.php
    header("Location: admin.php");
    exit();
} else {
    // Store the error message in a session variable
    session_start();
    $_SESSION['error_msg'] = $errorMsg;

    // Redirect back to login.php
    header("Location: login.php");
    exit();
}

// Helper function that checks input for malicious or unwanted content.
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to authenticate user
function authenticateUser() {
    global $username, $pwd, $errorMsg, $success;

    // Include the database connection file
    require_once "inc/sql.inc.php";
    $conn = getDatabaseConnection();
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT * FROM Memorial_Map_Admins WHERE Admin_Username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pwd_hashed = $row["Admin_Password"];
        // Since password is not hashed, directly compare
        if (!password_verify($_POST["pwd"], $pwd_hashed))  {
            $errorMsg = "Username or password is incorrect.";
            $success = false;
        }
    } else {
        $errorMsg = "Username or password is incorrect.";
        $success = false;
    }

    $stmt->close();
    $conn->close();
}
?>