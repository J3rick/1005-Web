<?php
// For error debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session (assumed to be started in inc/cookie_public.php)
require_once __DIR__ . '/inc/cookie_public.php';
require_once __DIR__ . '/inc/csrf.php';
// Include Composer's autoloader for PHPGangsta_GoogleAuthenticator
require_once __DIR__ . '/vendor/autoload.php';

// For login attempt limiter
define('MAX_LOGIN_ATTEMPTS', 3);     // Maximum allowed attempts
define('LOCKOUT_DURATION', 900);       // Lockout duration in seconds (15 minutes)

$username = $errorMsg = "";
$success = true;

// Check for lockout status
if (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) {
    $remainingTime = $_SESSION['lockout_time'] - time();
    die("You are temporarily locked out due to too many failed login attempts. Please try again in $remainingTime seconds.");
}

// Validate CSRF Token
if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
    $errorMsg .= "CSRF token validation failed.<br>";
    $success = false;
}

// ---------------------------
// 2. reCAPTCHA Validation
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
// 3. Validate Username, Password, and TOTP Code
// ---------------------------
if (empty($_POST["username"])) {
    $errorMsg .= "Username is required.<br>";
    $success = false;
} else {
    $username = sanitize_input($_POST["username"]);
}

if (empty($_POST["pwd"])) {
    $errorMsg .= "Password is required.<br>";
    $success = false;
} else {
    $pwd = $_POST["pwd"]; // Do not sanitize passwords
}

if (empty($_POST["2fa_code"])) {
    $errorMsg .= "Google Authenticator code is required.<br>";
    $success = false;
}

// ---------------------------
// 4. Authenticate User (if all validations pass)
// ---------------------------
if ($success) {
    authenticateUser();
}

// Process the login result
if ($success) {
    // Reset failed attempts after successful login
    unset($_SESSION['failed_attempts']);
    unset($_SESSION['lockout_time']);

    // Regenerate session ID for security
    session_regenerate_id(true);

    // (Session should already be started by inc/cookie_public.php)
    $_SESSION['username'] = $username;
    $_SESSION['admin'] = true;
    $_SESSION['last_activity'] = time();

    // Redirect to admin.php
    header("Location: admin.php");
    exit();
} else {
    // Increment failed attempts counter
    if (!isset($_SESSION['failed_attempts'])) {
        $_SESSION['failed_attempts'] = 0;
    }
    $_SESSION['failed_attempts']++;

    if ($_SESSION['failed_attempts'] >= MAX_LOGIN_ATTEMPTS) {
        // Lock out the user
        $_SESSION['lockout_time'] = time() + LOCKOUT_DURATION;
        unset($_SESSION['failed_attempts']); // Reset failed attempts after lockout
        die("Too many failed login attempts. You are temporarily locked out for " . (LOCKOUT_DURATION / 60) . " minutes.");
    }

    // Store the error message in a session variable for display on the login page
    $_SESSION['error_msg'] = $errorMsg;

    // Redirect back to login.php
    header("Location: login.php");
    exit();
}

// Helper function: Sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to authenticate user, including TOTP verification using Admin_2FA column.
function authenticateUser() {
    global $username, $pwd, $errorMsg, $success;
    
    // Include the database connection file
    require_once "inc/sql.inc.php";
    $conn = getDatabaseConnection();
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Prepare and execute the query to fetch admin details.
    $stmt = $conn->prepare("SELECT * FROM Memorial_Map_Admins WHERE Admin_Username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pwd_hashed = $row["Admin_Password"];
        
        // Verify the password.
        if (!password_verify($pwd, $pwd_hashed)) {
            $errorMsg = "Username or password is incorrect.";
            $success = false;
        } else {
            // Password is correct – now verify the TOTP code using Admin_2FA column.
            if (!empty($row['Admin_2FA'])) {
                $ga = new PHPGangsta_GoogleAuthenticator();
                $user2fa = trim($_POST['2fa_code']);
                // Allow a tolerance of 2 time intervals (2 * 30 seconds, usually)
                if (!$ga->verifyCode($row['Admin_2FA'], $user2fa, 2)) {
                    $errorMsg = "Invalid Google Authenticator code.";
                    $success = false;
                }
            } else {
                // Optionally, block login if 2FA is mandatory.
                $errorMsg = "Two-Factor Authentication is not set up for this account.";
                $success = false;
            }
        }
    } else {
        $errorMsg = "Username or password is incorrect.";
        $success = false;
    }

    $stmt->close();
    $conn->close();
}
?>
