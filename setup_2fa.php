<?php
require_once __DIR__ . '/vendor/autoload.php';

$ga = new PHPGangsta_GoogleAuthenticator();

// Generate a secret key for the user
$secret = $ga->createSecret();

// Generate a QR code URL for Google Authenticator
// Replace 'MemorialMap' with your application name and include the user's email or username for identification.
$qrCodeUrl = $ga->getQRCodeGoogleUrl('MemorialMap', $secret);

echo "<h2>Setup Two-Factor Authentication</h2>";
echo "<p>Scan this QR code with your Google Authenticator app:</p>";
echo "<img src='{$qrCodeUrl}' alt='QR Code'><br>";

// Save the $secret in your database associated with the user for later verification.
echo "<p>Your secret key (store this securely): {$secret}</p>";
?>
