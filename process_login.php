<link rel="stylesheet" href="css/main.css">

<!-- For error debugging -->
<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
?>

<?php
$username = $errorMsg = "";
$success = true;

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

// Display results
if ($success) {
    include "inc/head.inc.php";
    echo "<div class='warning-message'>";
    echo "<article class='col-sm'>";
    echo "<hr/>"; //divider
    echo "<h1>Login Successful!</h1>";
    echo "<h4>Welcome Back, " . $username . "</h4>";
    echo "<a href='index.php' class='btn btn-success' role='button' aria-pressed='true'>Return to Home</a>";
    echo "</article>";
    echo "</div><br/>";
    include "inc/footer.inc.php";
} else {
    include "inc/head.inc.php";
    echo "<div class='warning-message'>";
    echo "<article class='col-sm'>";
    echo "<hr/>"; //divider
    echo "<h1>Oops!</h1>";
    echo "<h4>The following errors were detected:</h4>";
    echo "<p>" . $errorMsg . "</p>";
    echo "<a href='register.php' class='btn btn-danger' role='button' aria-pressed='true'>Return to Sign Up</a>";
    echo "</article>";
    echo "</div><br/>";
    include "inc/footer.inc.php";
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
        $stored_pwd = $row["Admin_Password"];
        // Since password is not hashed, directly compare
        if ($pwd !== $stored_pwd) {
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