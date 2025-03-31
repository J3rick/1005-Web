<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session (assumed to be started in inc/cookie_public.php)
require_once __DIR__ . '/inc/cookie_public.php';
require_once __DIR__ . '/inc/csrf.php';
// Include Composer's autoloader for PHPGangsta_GoogleAuthenticator
require_once __DIR__ . '/vendor/autoload.php';

// For login attempt limiter (if needed)
define('MAX_LOGIN_ATTEMPTS', 3);
define('LOCKOUT_DURATION', 900);

$username = $errorMsg = "";
$success = true;

// Validate CSRF Token
if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
    $errorMsg .= "CSRF token validation failed.<br>";
    $success = false;
}

// reCAPTCHA Validation
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

// Validate Username, Password, and TOTP Code
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
    $pwd = $_POST["pwd"];
}

if (empty($_POST["2fa_code"])) {
    $errorMsg .= "Google Authenticator code is required.<br>";
    $success = false;
}

// If validations pass, authenticate user
if ($success) {
    authenticateUser();
}

// Process login result
if ($success) {
    // Reset failed attempts
    unset($_SESSION['failed_attempts']);
    unset($_SESSION['lockout_time']);
    session_regenerate_id(true);
    $_SESSION['username'] = $username;
    $_SESSION['admin'] = true;
    $_SESSION['last_activity'] = time();
    header("Location: admin.php");
    exit();
} else {
    if (!isset($_SESSION['failed_attempts'])) {
        $_SESSION['failed_attempts'] = 0;
    }
    $_SESSION['failed_attempts']++;
    if ($_SESSION['failed_attempts'] >= MAX_LOGIN_ATTEMPTS) {
        $_SESSION['lockout_time'] = time() + LOCKOUT_DURATION;
        unset($_SESSION['failed_attempts']);
        die("Too many failed login attempts. You are temporarily locked out for " . (LOCKOUT_DURATION / 60) . " minutes.");
    }
    $_SESSION['error_msg'] = $errorMsg;
    header("Location: login.php");
    exit();
}

// Helper: Sanitize input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Authenticate user including TOTP verification
function authenticateUser() {
    global $username, $pwd, $errorMsg, $success;
    require_once "inc/sql.inc.php";
    $conn = getDatabaseConnection();
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }
    $stmt = $conn->prepare("SELECT * FROM Memorial_Map_Admins WHERE Admin_Username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pwd_hashed = $row["Admin_Password"];
        if (!password_verify($pwd, $pwd_hashed)) {
            $errorMsg = "Username or password is incorrect.";
            $success = false;
        } else {
            // Verify TOTP code from Admin_2FA column
            if (!empty($row['Admin_2FA'])) {
                $ga = new PHPGangsta_GoogleAuthenticator();
                $user2fa = trim($_POST['2fa_code']);
                if (!$ga->verifyCode($row['Admin_2FA'], $user2fa, 2)) {
                    $errorMsg = "Invalid Google Authenticator code.";
                    $success = false;
                }
            } else {
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
