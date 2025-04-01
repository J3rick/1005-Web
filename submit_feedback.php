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

    // Set timezone to Singapore time
    date_default_timezone_set('Asia/Singapore');
    $singapore_time = date('Y-m-d H:i:s');

    // Prepare SQL statement using prepared statements
    $stmt = $conn->prepare("INSERT INTO Feedback (Feedback_Name, Feedback_Email, Feedback_Msg, Submitted_At) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        $errorMsg = "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        $success = false;
        return;
    }

    $stmt->bind_param("ssss", $name, $email, $feedback, $singapore_time);

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
    
    <style>
        .confirmation-container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .confirmation-header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .confirmation-error-header {
            background-color: #F44336;
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .confirmation-body {
            padding: 30px;
        }
        
        .feedback-details {
            background-color: #f9f9f9;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #4CAF50;
        }
        
        .feedback-item {
            margin-bottom: 15px;
        }
        
        .feedback-label {
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }
        
        .feedback-value {
            color: #333;
            background-color: #fff;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        .feedback-message {
            min-height: 80px;
            white-space: pre-wrap;
        }
        
        .redirect-button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 12px 30px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 15px;
        }
        
        .redirect-button:hover {
            background-color: #45a049;
        }
        
        .error-button {
            background-color: #F44336;
        }
        
        .error-button:hover {
            background-color: #d32f2f;
        }
        
        .countdown {
            margin-top: 10px;
            color: #666;
            font-size: 14px;
        }
        
        .error-message {
            color: #F44336;
            background-color: #FFEBEE;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            border-left: 4px solid #F44336;
        }
        
        .actions {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="confirmation-container">
    <?php if ($success): ?>
        <div class="confirmation-header">
            <h1>Thank You!</h1>
            <p>Your feedback has been successfully submitted.</p>
        </div>
        <div class="confirmation-body">
            <h2>Feedback Details</h2>
            <div class="feedback-details">
                <div class="feedback-item">
                    <div class="feedback-label">Name:</div>
                    <div class="feedback-value"><?php echo htmlspecialchars($name); ?></div>
                </div>
                
                <div class="feedback-item">
                    <div class="feedback-label">Email:</div>
                    <div class="feedback-value"><?php echo htmlspecialchars($email); ?></div>
                </div>
                
                <div class="feedback-item">
                    <div class="feedback-label">Your Message:</div>
                    <div class="feedback-value feedback-message"><?php echo htmlspecialchars($feedback); ?></div>
                </div>
            </div>
            
            <p>We appreciate your feedback and will review it promptly.</p>
            
            <div class="actions">
                <a href="index.php" class="redirect-button">Return to Homepage</a>
            </div>
            
            <div class="countdown">
                Redirecting to homepage in <span id="countdown">10</span> seconds...
            </div>
        </div>
        
        <script>
            // Auto-redirect countdown
            let seconds = 10;
            const countdownElement = document.getElementById('countdown');
            
            const countdown = setInterval(function() {
                seconds--;
                countdownElement.textContent = seconds;
                
                if (seconds <= 0) {
                    clearInterval(countdown);
                    window.location.href = 'index.php';
                }
            }, 1000);
        </script>
        
    <?php else: ?>
        <div class="confirmation-error-header">
            <h1>Oops!</h1>
            <p>There was a problem with your submission.</p>
        </div>
        <div class="confirmation-body">
            <div class="error-message">
                <?php echo $errorMsg; ?>
            </div>
            
            <div class="actions">
                <button onclick="history.back()" class="redirect-button error-button">Go Back and Try Again</button>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include "inc/footer.inc.php"; ?>

</body>
</html>