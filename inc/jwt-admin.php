<!-- Used to authenticate for admin on admin pages -->
<?php
require_once __DIR__ . '/jwt.php'; 

class AdminAuth {
    private $jwtHandler;

    public function __construct() {
        $this->jwtHandler = new JWTHandler(); // Initialize the JWT handler
    }

    public function validateAdminAccess() {
        // Get the Authorization header
        $headers = getallheaders();
        $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : null;

        if (!$authHeader) {
            http_response_code(401);
            die(json_encode(["status" => "error", "message" => "Authorization header missing"]));
        }

        // Extract the token from the Bearer scheme
        list($bearer, $token) = explode(' ', $authHeader);

        if (strcasecmp($bearer, 'Bearer') !== 0 || empty($token)) {
            http_response_code(401);
            die(json_encode(["status" => "error", "message" => "Invalid Authorization header format"]));
        }

        // Validate the token
        $decodedToken = $this->jwtHandler->validateToken($token);

        if (!$decodedToken) {
            http_response_code(401);
            die(json_encode(["status" => "error", "message" => "Invalid or expired token"]));
        }

        // Check if the user has admin privileges (e.g., based on a claim in the token)
        if (!isset($decodedToken['data']['user_id'])) {
            http_response_code(403);
            die(json_encode(["status" => "error", "message" => "Access denied"]));
        }

        // Optionally, can perform additional checks here if needed (e.g., database validation)

        return $decodedToken; // Return decoded token data for further use
    }
}

// Instantiate and validate admin access
$adminAuth = new AdminAuth();
$decodedToken = $adminAuth->validateAdminAccess();


?>
