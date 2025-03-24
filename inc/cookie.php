<?php
    //ini_set('session.cookie_secure', 1); // If I can set up HTTPS then uncomment this - Derrick
    ini_set('session.cookie_httponly', 1); // Reduces the risk of cross-site scripting (XSS) attacks, prevent access through client-side scripts

    // Session Cookies, Why? because imagine the user has stolen the admin's laptop and the identity as an admin is persistantly stored in the cookie
    ini_set('session.use_cookies', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_lifetime', 0);

    // Add SameSite attribute, for an extra layer of protection against Cross-Site Request Forgery, it limits the scenarios in which cookies are sent to third-party websites.
    ini_set('session.cookie_samesite', 'Lax'); // for a balance of security and user experience, strict might break some requests

    session_start();

    // Check if user is logged in and is an admin
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
        header("Location: index.php"); // right now if its not admin, user will be redirected to index.php 
        exit();
    }
    
    // Check for session timeout
    $timeout = 1800; // 30 minutes
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
        session_unset();
        session_destroy();
        header("Location: index.php");   // right now if its not admin, user will be redirected to index.php 
        exit();
    }
    $_SESSION['last_activity'] = time();

?>