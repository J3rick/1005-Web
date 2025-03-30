<?php
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
    <title>Cemetery Management System - View Feedback</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/viewfeedback.css">
</head>
<body>
    <div class="content">
        <h1>View Feedback</h1>
        
        <table class="feedback-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $conn->prepare('SELECT * FROM Memorial_Map.Feedback ORDER BY Submitted_At DESC');
                $query->execute();
                $query->store_result();
                $query->bind_result($id, $name, $email, $message, $submitted_at);
                
                if($query->num_rows == 0) {
                    echo "<tr><td colspan='5'>No feedback records found</td></tr>";
                }
                
                while ($query->fetch()) {
                    echo "<tr>
                        <td data-label='ID'>$id</td>
                        <td data-label='Name'>$name</td>
                        <td data-label='Email'>$email</td>
                        <td data-label='Message'>$message</td>
                        <td data-label='Submitted'>$submitted_at</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>