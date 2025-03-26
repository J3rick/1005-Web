<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    include 'inc/head.inc.php';
    include 'inc/adminbar.inc.php';
    include 'inc/sql.inc.php';
    $conn = getDatabaseConnection();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cemetery Management System - Admin Page</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/addgraves.css">
</head>

<style>

</style>
<body>
    <section class="addform">
        <div class="addform-content">
            <h1>Adding new grave</h1>
            
            <form class="addgraves-form" action="addgraves_process.php" method="POST">
                <div class="addform-grp">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" placeholder="Enter name">
                </div>

                <div class="addform-grp">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob">
                </div>

                <div class="addform-grp">
                    <label for="dop">Date of Passing:</label>
                    <input type="date" id="dop" name="dop">
                </div>

                <div class="addform-group">
                    <label for="religion">Religion:</label>
                    <select id="religion" name="religion">
                        <option value="Christianity">Christianity</option>
                        <option value="Judaism">Judaism</option>
                        <option value="Islam">Islam</option>
                        <option value="Buddhism">Buddhism</option>
                        <option value="Hinduism">Hinduism</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="addform-grp">
                    <label for="plotno">Plot Number:</label>
                    <input type="text" id="plotno" name="plotno">
                </div>

                <div class="addform-grp">
                    <label for="image">Obituary:</label>
                    <input type="file" id="image" name="image">
                </div>

                <div class="addform-group">
                    <label for="restingtype">Resting Type:</label>
                    <select id="restingtype" name="restingtype">
                        <option value="Burial">Burial</option>
                        <option value="Cremation">Cremation</option>
                        <option value="Mausoleum">Mausoleum</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="add-actions">
                    <button type="submit" class="add-btn">Add record</button>
                </div>
            </form>
        </div>
    </section>

    <?php
        include "inc/footer.inc.php";
    ?>

</body>
