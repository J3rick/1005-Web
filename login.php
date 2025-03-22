<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MemorialMap</title>
    
    <?php
      include "inc/head.inc.php"
    ?>

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
      
      <div class="form-group">
        <input type="submit" value="Login" class="btn btn-primary">
      </div>
      
      <div class="form-group">
        <a href="forgot-password.php">Forgot Password?</a>
      </div>
    </form>
  
  </main>
    
    <?php
        include "inc/footer.inc.php"
        ?>

</body>
</html>
