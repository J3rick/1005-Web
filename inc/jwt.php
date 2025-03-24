<?php
    // Include the JWT library
    require_once 'vendor/autoload.php';
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    
    // Verify JWT token
    $secretKey = "your_secret_key"; // Replace with your actual secret key
    $token = $_COOKIE['jwt_token'] ?? '';

    try {
        $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
        // Token is valid, user is authenticated
        $username = $decoded->username;
    } catch (Exception $e) {
        // Token is invalid or expired
        header("Location: login.php");
        exit();
    }


?>