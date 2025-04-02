<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/inc/cookie_public.php';

// Initialize variables
$name = $email = $feedback = $errorMsg = "";
$success = true;

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ---------------------------
    // 1. Verify reCAPTCHA
    // ---------------------------
    if (empty($_POST['g-recaptcha-response'])) {
        $errorMsg .= "Please complete the reCAPTCHA.<br>";
        $success = false;
    } else {
        $recaptcha_secret = '6LeCwQMrAAAAALobYbZlQmuNyjZU7tgaMaFABs4z'; 
        $recaptcha_response = $_POST['g-recaptcha-response'];
        $verify_url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret'   => $recaptcha_secret,
            'response' => $recaptcha_response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($verify_url, false, $context);
        $resultJson = json_decode($result, true);
        if (!$resultJson["success"]) {
            $errorMsg .= "reCAPTCHA verification failed. Please try again.<br>";
            $success = false;
        }
    }

    // ---------------------------
    // 2. Validate Form Fields
    // ---------------------------
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
    
</head>
<body>
<?php include "inc/head.inc.php"; ?>
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
