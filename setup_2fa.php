<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include Composer's autoloader
$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    die("Autoloader not found at: $autoloadPath");
}
require_once $autoloadPath;

// Start session and check if user is logged in
session_start();
if (!isset($_SESSION['username'])) {
    die("You must be logged in to set up 2FA.");
}

$currentUsername = $_SESSION['username'];

// Include your database connection file
require_once __DIR__ . '/inc/sql.inc.php';
$conn = getDatabaseConnection();
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$ga = new PHPGangsta_GoogleAuthenticator();

// Generate a new secret key
$secret = $ga->createSecret();

// Save the secret in your database (Admin_2FA column)
$stmt = $conn->prepare("UPDATE Memorial_Map_Admins SET Admin_2FA=? WHERE Admin_Username=?");
$stmt->bind_param("ss", $secret, $currentUsername);
if (!$stmt->execute()) {
    die("Error updating 2FA secret: " . $stmt->error);
}
$stmt->close();
$conn->close();

// Generate a QR code URL for Google Authenticator
// The label can include your app name and the username for clarity.
$label = "MemorialMap ({$currentUsername})";
$qrCodeUrl = $ga->getQRCodeGoogleUrl($label, $secret);

// Output the setup information
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Setup Two-Factor Authentication</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <h2>Setup Two-Factor Authentication</h2>
    <p>Scan this QR code with your Google Authenticator app:</p>
    <img src="<?php echo htmlspecialchars($qrCodeUrl); ?>" alt="QR Code">
    <p>If you cannot scan the QR code, manually enter this secret in your app:</p>
    <p><strong><?php echo htmlspecialchars($secret); ?></strong></p>
    <p>After setting up, use this code on the login page to complete authentication.</p>
</body>
</html>
