<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Ensure the Google2FA library is autoloaded

use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class Auth {
    private $google2fa;

    public function __construct() {
        $this->google2fa = new Google2FA();
    }

    /**
     * Generate a new secret key for 2FA.
     *
     * @return string The generated secret key.
     */
    public function generateSecretKey() {
        return $this->google2fa->generateSecretKey();
    }

    /**
     * Generate a QR code URL for Google Authenticator.
     *
     * @param string $accountName The account name (e.g., website name).
     * @param string $username The username or email of the user.
     * @param string $secretKey The user's secret key.
     * @return string The QR code URL.
     */
    public function getQRCodeUrl($accountName, $username, $secretKey) {
        if (empty($accountName) || empty($username) || empty($secretKey)) {
            throw new InvalidArgumentException("Account name, username, and secret key cannot be empty.");
        }
        return $this->google2fa->getQRCodeUrl($accountName, $username, $secretKey);
    }

    /**
     * Generate a base64-encoded QR code image for embedding in HTML.
     *
     * @param string $accountName The account name (e.g., website name).
     * @param string $username The username or email of the user.
     * @param string $secretKey The user's secret key.
     * @return string Base64-encoded QR code image.
     */
    public function generateQRCode($accountName, $username, $secretKey) {
        // Generate QR Code URL
        $qrCodeUrl = $this->getQRCodeUrl($accountName, $username, $secretKey);

        // Use BaconQrCode to create a QR code image
        $renderer = new ImageRenderer(
            new RendererStyle(400), // Size of the QR code
            new ImagickImageBackEnd()
        );
        $writer = new Writer($renderer);

        // Return as base64-encoded image
        return base64_encode($writer->writeString($qrCodeUrl));
    }

    /**
     * Verify the 2FA code entered by the user.
     *
     * @param string $secretKey The user's secret key stored in the database.
     * @param string $code The 6-digit code entered by the user.
     * @return bool True if the code is valid, false otherwise.
     */
    public function verifyCode($secretKey, $code) {
        if (empty($secretKey) || empty($code)) {
            throw new InvalidArgumentException("Secret key or code cannot be empty.");
        }
        return $this->google2fa->verifyKey($secretKey, trim($code));
    }

    /**
     * Generate backup codes for recovery purposes.
     *
     * @param int $count Number of backup codes to generate.
     * @return array An array of backup codes.
     */
    public function generateBackupCodes($count = 5) {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            // Generate random alphanumeric backup codes
            $codes[] = bin2hex(random_bytes(5));
        }
        return $codes;
    }
}
?>
