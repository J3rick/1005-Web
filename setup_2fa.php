<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if vendor autoload exists
$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    die("Autoloader not found at: $autoloadPath");
}

require_once $autoloadPath;

$ga = new PHPGangsta_GoogleAuthenticator();

// Generate a secret key for the user
$secret = $ga->createSecret();

// Generate a QR code URL for Google Authenticator
// Replace 'MemorialMap' with your application name and you may include a unique identifier for the user.
$qrCodeUrl = $ga->getQRCodeGoogleUrl('MemorialMap', $secret);

// Output the setup information
echo "<h2>Setup Two-Factor Authentication</h2>";
echo "<p>Scan this QR code with your Google Authenticator app:</p>";
echo "<img src='" . htmlspecialchars($qrCodeUrl) . "' alt='QR Code'><br>";
echo "<p>Your secret key (store this securely): <strong>" . htmlspecialchars($secret) . "</strong></p>";
?>
