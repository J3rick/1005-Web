<?php
    ini_set('session.cookie_secure', 1);  // Enforce HTTPS-only cookies
    ini_set('session.cookie_httponly', 1); // Reduces the risk of cross-site scripting (XSS) attacks, prevent access through client-side scripts

    // Session Cookies, Why? because imagine the user has stolen the admin's laptop and the identity as an admin is persistantly stored in the cookie
    ini_set('session.use_cookies', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_lifetime', 0); // Session expires when the browser closes

    // Add SameSite attribute, for an extra layer of protection against Cross-Site Request Forgery, it limits the scenarios in which cookies are sent to third-party websites.
    ini_set('session.cookie_samesite', 'Lax'); // Allow CSRF protection but permit cross-origin GET requests
   

    // Start the session securely if there is no existing session
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start([
        ]);
    }

    // session regeneration, to prevent session fixation attacks
    if (!isset($_SESSION['regenerated'])) {
        session_regenerate_id(true);
        $_SESSION['regenerated'] = true;
    }

    // Content Security Policy (CSP)  to restrict the sources from which scripts can be loaded, further mitigating XSS risks
        // Content Security Policy (CSP) - Updated to include CSS sources and Mapbox
header("Content-Security-Policy: " . 
    "default-src 'self'; " . 
    "script-src 'self' https://www.google.com/recaptcha/ https://www.gstatic.com/recaptcha/ https://api.mapbox.com https://api.tiles.mapbox.com 'unsafe-inline'; " . 
    "style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://maxcdn.bootstrapcdn.com https://api.mapbox.com https://api.tiles.mapbox.com; " . 
    "font-src 'self' https://cdnjs.cloudflare.com; " . 
    "frame-src https://www.google.com/recaptcha/ https://recaptcha.google.com/recaptcha/; " . 
    "img-src 'self' data: https://www.gstatic.com/recaptcha/ https://*.mapbox.com https://api.mapbox.com https://api.tiles.mapbox.com; " . 
    "connect-src 'self' https://www.google.com/recaptcha/ https://www.gstatic.com/recaptcha/ https://api.mapbox.com https://*.tiles.mapbox.com https://events.mapbox.com; " . 
    "worker-src blob:;");

?>