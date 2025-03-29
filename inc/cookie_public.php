<?php
    ini_set('session.cookie_secure', 1);  // Enforce HTTPS-only cookies
    ini_set('session.cookie_httponly', 1); // Reduces the risk of cross-site scripting (XSS) attacks, prevent access through client-side scripts

    // Session Cookies, Why? because imagine the user has stolen the admin's laptop and the identity as an admin is persistantly stored in the cookie
    ini_set('session.use_cookies', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_lifetime', 0); // Session expires when the browser closes

    // Add SameSite attribute, for an extra layer of protection against Cross-Site Request Forgery, it limits the scenarios in which cookies are sent to third-party websites.
    ini_set('session.cookie_samesite', 'Lax'); // Allow CSRF protection but permit cross-origin GET requests
   

    // Start the session securely
    session_start([
        'read_and_close' => true // Minimize session locking to prevent DoS attacks
    ]);

    // session regeneration, to prevent session fixation attacks
    if (!isset($_SESSION['regenerated'])) {
        session_regenerate_id(true);
        $_SESSION['regenerated'] = true;
    }


    // Content Security Policy (CSP)  to restrict the sources from which scripts can be loaded, further mitigating XSS risks
    header("Content-Security-Policy: default-src 'self'; script-src 'self' https://apis.example.com; style-src 'self' https://fonts.example.com; img-src 'self'; frame-ancestors 'none';");


?>