<?php
// session_start();
 $success = true;

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // // Verify CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed");
    }
    
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if ($success){
        include "head.inc.php";
        echo "<div class='warning-mesage'>";
        echo"<article class='col-sm'>";
        echo "<hr/>"; //divider
        echo "<h1>Login Successful!</b></h1>";
        //echo "<p>Email" . $email;
        echo "<h4>Welcome Back, " . $fname . " " . $lname . "</h4>";
        echo"<a href='index.php' class='btn btn-success' role='button' aria-pressed='true'>Return to Home</a>";
        echo"</article>";
        echo "</div><br/>";
        include "footer.inc.php";
    }
    else{
        include "head.inc.php";
        echo "<div class='warning-mesage'>";
        echo"<article class='col-sm'>";
        echo "<hr/>"; //divider
        echo "<h1>Oops!</h1>";
        echo "<h4>The following errors were detected:</h4>";
        echo "<p>" . $errorMsg . "</p>";
        echo"<a href='register.php' class='btn btn-danger' role='button' aria-pressed='true'>Return to Sign Up</a>";
        echo"</article>";
        echo "</div><br/>";
        include "footer.inc.php";
    }

    //Helper function that checks input for malicious or unwanted content.
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function authenticateUser(){
        global $username, $pwd_hashed, $errorMsg, $success;

        $config = parse_ini_file('/var/www/private/db-config.ini');
        if (!$config)
        {
            $errorMsg = "Failed to read database config file.";
            $success = false;
        }
        else {
            $conn = mysqli(
            $config['servername'],
            $config['username'],
            $config['password'],
            $config['dbname']
            );
        }

        // Check connection
        if ($conn->connect_error)
        {
            $errorMsg = "Connection failed: " . $conn->connect_error;
            $success = false;
        }
        else {
            $stmt = $conn->prepare("SELECT * FROM Memorial_Map_Admins WHERE Admin_Username=?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $pwd_hashed = $row["password"];
                // Check if the password matches:
                if (!password_verify($_POST["pwd"], $pwd_hashed))
                {
                    $errorMsg = "Email not found or password doesn't match...";
                    $success = false;
                }
                else {
                    $errorMsg = "Email not found or password doesn't match...";
                    $success = false;
                }

                $stmt->close();
            }
            $conn->close();
        }
    
    }
}
?>