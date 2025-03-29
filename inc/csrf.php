<?php

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

 // Generates a CSRF token and store it in the session, if a token already exists, reuse it.

function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Regenerates CSRF token
function regenerateCSRFToken() {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}


// Validate the CSRF token from the request against the session token.

function validateCSRFToken($token) {
    if (!empty($token) && isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        return true;
    }
    error_log('CSRF validation failed: Invalid or missing token.');
    return false;
}

/**
 * Include this hidden input field in any forms fields 
 * E.g. 
 * 
 * <form action="login.php" method="post">
 * <?php csrfInputField(); ?>
 *  <!-- Other form fields -->
 * 
 */
function csrfInputField() {
    $csrfToken = generateCSRFToken();
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') . '">';
}
?>
