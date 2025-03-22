
<?php 
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize variables
$name = $email = $feedback = $errorMsg = "";
$success = true;

<?php
// Database connection settings
$servername = "localhost";  // Change this to your database host, e.g., localhost or IP address
$username = "inf1005-sqldev";         // Your database username
$password = "P@ssw0rd123";             // Your database password
$dbname = "Memorial_Map";  // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Name
    if (empty($_POST["name"])) {
        $errorMsg .= "Name is required.<br>";
        $success = false;
    } else {
        $name = sanitize_input($_POST["name"]);
    }


    // Validate Email
    if (empty($_POST["email"])) {
        $errorMsg .= "Email is required.<br>";
        $success = false;
    // Get the current timestamp for Submitted_At
    $submitted_at = date('Y-m-d H:i:s');  // Format: YYYY-MM-DD HH:MM:SS
    
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO Feedback (Feedback_Name, Feedback_Email, Feedback_Msg, Submitted_At) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $message, $submitted_at);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo "Feedback submitted successfully!";
    } else {
        $email = sanitize_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg .= "Invalid email format.<br>";
            $success = false;
        }
    }

    // Validate Feedback
    if (empty($_POST["feedback"])) {
        $errorMsg .= "Feedback is required.<br>";
        $success = false;
    } else {
        $feedback = sanitize_input($_POST["feedback"]);
    }

    // If all validations pass, insert into database
    if ($success) {
        saveFeedbackToDB();
    }
}

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/* Function to save feedback data to the database */
function saveFeedbackToDB() {
    global $name, $email, $feedback, $errorMsg, $success;

    // Read database credentials from config file
    $config = parse_ini_file('/var/www/private/db-config.ini');
    
    if (!$config) {
        $errorMsg = "Failed to read database config file.";
        $success = false;
        return;
    }

    // Establish a connection
    $conn = new mysqli(
        $config['servername'],
        $config['username'],
        $config['password'],
        $config['dbname']
    );

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
        return;
    }

    // Prepare SQL statement using prepared statements
    $stmt = $conn->prepare("INSERT INTO Feedback (Feedback_Name, Feedback_Email, Feedback_Msg, Submitted_At) VALUES (?, ?, ?, NOW())");
    if (!$stmt) {
        $errorMsg = "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        $success = false;
        return;
    }

    $stmt->bind_param("sss", $name, $email, $feedback);

    // Execute the statement
    if (!$stmt->execute()) {
        $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $success = false;
    }

    // Close connections
    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "inc/head.inc.php"; ?>
</head>
<body>

<div class="container text-center mt-5">
    <?php if ($success): ?>
        <h2 class="text-success">Your feedback has been successfully submitted!</h2>
        <p>Thank you for your feedback, <strong><?php echo htmlspecialchars($name); ?></strong>.</p>
        <a href="feedback.php" class="btn btn-success">Return to Feedback Page</a>
    <?php else: ?>
        <h2 class="text-danger">Oops!</h2>
        <p class="text-danger">The following errors were detected:</p>
        <p class="text-danger"><?php echo $errorMsg; ?></p>
        <a href="submit_feedback.php" class="btn btn-danger">Return to Submit Feedback</a>
    <?php endif; ?>
</div>

<?php include "inc/footer.inc.php"; ?>

</body>
</html>
