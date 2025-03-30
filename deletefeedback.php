<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'inc/head.inc.php';
include 'inc/adminbar.inc.php';
include 'inc/sql.inc.php';
//require_once __DIR__ . '/inc/cookie_admin.php';
require_once __DIR__ . '/inc/csrf.php';

// Get feedback record to delete
$conn = getDatabaseConnection();
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: feedback.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM Feedback WHERE Feedback_ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$feedback = $result->fetch_assoc();

if (!$feedback) {
    header("Location: feedback.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Feedback - Cemetery Management System</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/deletefeedback.css">
</head>
<body>
    <section class="delete-confirm">
        <div class="delete-confirm-content">
            <h1>Confirm Deletion</h1>
            
            <div class="feedback-details">
                <p><strong>Name:</strong> <?= htmlspecialchars($feedback['Feedback_Name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($feedback['Feedback_Email']) ?></p>
                <p><strong>Message:</strong> <?= htmlspecialchars($feedback['Feedback_Msg']) ?></p>
                <p><strong>Submitted At:</strong> <?= htmlspecialchars($feedback['Submitted_At']) ?></p>
            </div>
            
            <p class="warning-message">Are you sure you want to permanently delete this feedback?</p>
            
            <form action="deletefeedback_process.php" method="POST">
                <input type="hidden" name="id" value="<?= $feedback['Feedback_ID'] ?>">
                
                <div class="delete-actions">
                    <button type="submit" class="confirm-delete-btn">Delete Permanently</button>
                    <a href="feedback.php" class="cancel-btn">Cancel</a>
                </div>
                
                <!-- CSRF token -->
                <?php csrfInputField(); ?>
            </form>
        </div>
    </section>

    <?php include "inc/footer.inc.php"; ?>
</body>
</html>