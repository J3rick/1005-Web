<?php
// Start the session to access session variables
session_start(); 

// Generate CSRF token if it doesn't exist
if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check if there's an error message stored in the session
if (isset($_SESSION['error_msg'])) {
  // Display the error message (needs beautification)
  echo "<p class='error'>" . htmlspecialchars($_SESSION['error_msg']) . "</p>";
  // Clear the error message after displaying it
  unset($_SESSION['error_msg']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MemorialMap</title>
    
    <?php include "inc/head.inc.php"; ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<main class="container">
    <h1>Login</h1>
    
    <form action="process_login.php" method="post" class="login-form">
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
      </div>
      
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="pwd" required>
      </div>

      <!-- CSRF token -->
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
      
      <!-- Add reCAPTCHA widget -->
      <div class="form-group">
          <div class="g-recaptcha" data-sitekey="6LeCwQMrAAAAAJXDUke3bJ-9MdERoi86AAcPNlMF"></div>
      </div>
      
      <div class="form-group">
        <input type="submit" value="Login" class="btn btn-primary">
      </div>
      
      <div class="form-group">
        <a href="forgot-password.php">Forgot Password?</a>
      </div>
    </form>
</main>
    
<?php include "inc/footer.inc.php"; ?>

<!-- Include the reCAPTCHA API script -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>
