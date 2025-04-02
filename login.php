<?php
require_once __DIR__ . '/inc/cookie_public.php'; 
require_once __DIR__ . '/inc/csrf.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MemorialMap</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<?php include "inc/head.inc.php"; ?>
<main class="container">
    <h1>Login</h1>

        <!-- Display an alert if there is an error message -->
        <?php if (isset($_SESSION['error_msg']) && !empty($_SESSION['error_msg'])): ?>
        <script>
            alert("<?php echo htmlspecialchars($_SESSION['error_msg']); ?>");
        </script>
        <?php unset($_SESSION['error_msg']); // Clear the error message after displaying it ?>
    <?php endif; ?>
    
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
      <?php csrfInputField(); ?>
      
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