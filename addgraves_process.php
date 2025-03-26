<head>
    <title>MemorialMap</title>
    <?php
        include "inc/head.inc.php"
    ?>
</head>
<?php
    //Helper function that checks input for malicious or unwanted content.
    function sanitize_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    $email = $errorMsg = "";
    $success = true;

    //Email validation checks
    if (empty($_POST["email"]))
    {
        $errorMsg .= "Email is required.<br>";
        $success = false;
    }
    else {
        $email = sanitize_input($_POST["email"]);
    // Additional check to make sure e-mail address is well-formed.
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $errorMsg .= "Invalid email format.";
            $success = false;
        }
    }

    //Name checks
    if (empty($_POST["fname"]))
    {
        $errorMsg .= "First name is required.<br>";
        $success = false;
    }
    elseif (empty($_POST["lname"])) {
        $errorMsg .= "Last name is required.<br>";
        $success = false;
    }
    else {
        $fname = sanitize_input($_POST["fname"]);
        $lname = sanitize_input($_POST["lname"]);
    }


    //Password check
    if (empty($_POST["pwd"]) || empty($_POST["pwd_confirm"]) )
    {
        $errorMsg .= "Password is required.<br>";
        $success = false;
    }
    else {
        $pwd = $_POST["pwd"];
        $pwd_confirm = $_POST["pwd_confirm"];

        if ($pwd_confirm != $pwd) {
            $errorMsg .= "Passwords are not the same.<br>";
            $success = false;
        }
    }

    if ($success)
    {
        include "inc/nav.inc.php";
        echo "<div class = 'warning-message'>";
        echo "<article class = 'col-sm'>";
        echo "<hr/>";
        echo "<h1>Registration successful!</h1>";
        echo "<h4>Thank you for signing up, " . $fname . " " . $lname."</h4>";
        echo "<a href='register.php' class='btn btn-success' role = 'button' aria-pressed = 'true'>Login</a>";
        echo "</article>";
        echo "</div> <br/>";
        include "inc/footer.inc.php";
    }
    else {
        include "inc/nav.inc.php";
        echo "<div class = 'warning-message'>";
        echo "<article class = 'col-sm'>";
        echo "<hr/>";
        echo "<h1>Oops!</h1>";
        echo "<h4>The following input errors were detected:</h4>";
        echo "<p>" . $errorMsg . "</p>";
        echo "<a href='register.php' class='btn btn-danger' role = 'button' aria-pressed = 'true'>Return to Sign Up</a>";
        echo "</article>";
        echo "</div> <br/>";
        include "inc/footer.inc.php";
    }

?>