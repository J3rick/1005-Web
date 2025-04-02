<?php
$email = $fname = $lastName = $pwd = $pwd_confirm = $lname = "";
$errorMsg = "";
$success = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["fname"])) {

    } else {
        $fname = sanitize_input($_POST["fname"]);
    }
    // Validate Last Name 
    if (empty($_POST["lname"])) {
        $errorMsg .= "Last name is required.<br>";
        $success = false;
    } else {
        $lname = sanitize_input($_POST["lname"]);
    }

    // Validate Email 
    if (empty($_POST["email"])) {
        $errorMsg .= "Email is required.<br>";
        $success = false;
    } else {
        $email = sanitize_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg .= "Invalid email format.<br>";
            $success = false;
        }
    }

    // Validate Password 
    if (empty($_POST["pwd"]) || empty($_POST["pwd_confirm"])) {
        $errorMsg .= "Both password fields are required.<br>";
        $success = false;
    } else {
        $pwd = $_POST["pwd"];
        $pwd_confirm = $_POST["pwd_confirm"];

        // Ensure passwords match 
        if ($pwd !== $pwd_confirm) {
            $errorMsg .= "Passwords do not match.<br>";
            $success = false;
        } else {
            // Hash the password for security 
            $password = password_hash($pwd, PASSWORD_DEFAULT);
        }
    }
}

function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>



<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body>
    <?php
    include "inc/head.inc.php";
    ?>

    <?php
    include "inc/nav.inc.php";
    ?>

    <div class="container text-center mt-5">
        <?php if ($success): ?>
            <h2 class="text-success">Your registration is successful!</h2>
            <p>Thank you for signing up,
                <strong><?php echo htmlspecialchars($_POST["fname"] . " " . $_POST["lname"]); ?></strong>.</p>
            <a href="login.php" class="btn btn-success">Log-in</a>
        <?php else: ?>
            <h2 class="text-danger">Oops!</h2>
            <p class="text-danger">The following errors were detected:</p>
            <p class="text-danger"><?php echo $errorMsg; ?></p>
            <a href="register.php" class="btn btn-danger">Return to Sign Up</a>
        <?php endif; ?>
    </div>

    <?php
    include "inc/footer.inc.php";
    ?>

    </div>


</body>


</html>