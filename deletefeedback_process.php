<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'inc/sql.inc.php';
require_once __DIR__ . '/inc/csrf.php';

// Initialize variables
$feedbackDetails = null;
$success = true;
$errorMsg = "";

// Validate CSRF Token
if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
    $errorMsg = "CSRF token validation failed.<br>";
    $success = false;
}

try {
    if ($success) {
        $conn = getDatabaseConnection();
        
        // Validate ID
        if (empty($_POST['id'])) {
            throw new Exception("No feedback ID specified for deletion");
        }
        
        $id = (int)$_POST['id'];

        // Get record before deletion (for confirmation message)
        $stmt = $conn->prepare("SELECT * FROM Feedback WHERE Feedback_ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $feedbackDetails = $result->fetch_assoc();

        if (!$feedbackDetails) {
            throw new Exception("Feedback record not found");
        }

        // Delete the record
        $deleteStmt = $conn->prepare("DELETE FROM Feedback WHERE Feedback_ID = ?");
        $deleteStmt->bind_param("i", $id);
        
        if (!$deleteStmt->execute()) {
            throw new Exception("Database error: " . $deleteStmt->error);
        }
    }
} catch (Exception $e) {
    // Log error
    error_log("[".date('Y-m-d H:i:s')."] Delete Feedback Error: " . $e->getMessage());
    $errorMsg = $e->getMessage();
    $success = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Feedback - Cemetery Management System</title>
    <?php
    include "inc/head.inc.php"
    ?>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/admin.css">
    <style>
        .confirmation-container {
            max-width: 90%;
            width: 550px;
            margin: 185px auto 190px; /* Top margin of 60px, centered horizontally, bottom margin of 20px */
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .confirmation-header {
            background-color: #2e7d32;
            color: white;
            padding: 12px;
            text-align: center;
        }
        
        .confirmation-header h1 {
            margin: 0;
            font-size: 20px;
        }
        
        .confirmation-header p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        
        .confirmation-error-header {
            background-color: #F44336;
            color: white;
            padding: 12px;
            text-align: center;
        }
        
        .confirmation-error-header h1 {
            margin: 0;
            font-size: 20px;
        }
        
        .confirmation-error-header p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        
        .confirmation-body {
            padding: 15px;
        }
        
        .feedback-details {
            background-color: #f9f9f9;
            border-radius: 6px;
            padding: 12px;
            margin: 10px 0;
            border-left: 4px solid #388E3C;
        }
        
        .feedback-item {
            margin-bottom: 10px;
        }
        
        .feedback-label {
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 3px;
            font-size: 14px;
        }
        
        .feedback-value {
            color: #333;
            background-color: #fff;
            padding: 6px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 14px;
            word-break: break-word;
        }
        
        .feedback-message {
            min-height: 50px;
            white-space: pre-wrap;
        }
        
        .redirect-button {
            display: inline-block;
            background-color: #2e7d32;
            color: white;
            padding: 8px 20px;
            text-align: center;
            text-decoration: none;
            font-size: 14px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            box-sizing: border-box;
        }
        
        .redirect-button:hover {
            background-color: #45a049;
        }
        
        .error-button {
            background-color: #F44336;
        }
        
        .error-button:hover {
            background-color: #d32f2f;
        }
        
        .countdown {
            margin-top: 8px;
            color: #666;
            font-size: 13px;
            text-align: center;
        }
        
        .error-message {
            color: #F44336;
            background-color: #FFEBEE;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
            border-left: 4px solid #F44336;
            font-size: 14px;
        }
        
        .actions {
            text-align: center;
            margin-top: 15px;
        }
        
        h2 {
            margin-top: 0;
            font-size: 16px;
            color: #333;
        }
        
        @media screen and (max-width: 600px) {
            .confirmation-container {
                width: 95%;
                margin: 10px auto;
            }
            
            .redirect-button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <main>

<div class="confirmation-container">
    <?php if ($success): ?>
        <div class="confirmation-header">
            <h1>Feedback Deleted</h1>
            <p>The feedback has been successfully deleted</p>
        </div>
        <div class="confirmation-body">
            <h2>Deleted Feedback Details</h2>
            <div class="feedback-details">
                <div class="feedback-item">
                    <div class="feedback-label">Name:</div>
                    <div class="feedback-value"><?php echo htmlspecialchars($feedbackDetails['Feedback_Name']); ?></div>
                </div>
                
                <div class="feedback-item">
                    <div class="feedback-label">Email:</div>
                    <div class="feedback-value"><?php echo htmlspecialchars($feedbackDetails['Feedback_Email']); ?></div>
                </div>
                
                <div class="feedback-item">
                    <div class="feedback-label">Message:</div>
                    <div class="feedback-value feedback-message"><?php echo htmlspecialchars($feedbackDetails['Feedback_Msg']); ?></div>
                </div>
                
                <div class="feedback-item">
                    <div class="feedback-label">Submitted At:</div>
                    <div class="feedback-value"><?php echo htmlspecialchars($feedbackDetails['Submitted_At']); ?></div>
                </div>
            </div>
            
            <div class="actions">
                <a href="feedback.php" class="redirect-button">Return to Feedback List</a>
            </div>
            
            <div class="countdown">
                Redirecting to feedback list in <span id="countdown">5</span> seconds...
            </div>
        </div>
        
        <script>
            // Auto-redirect countdown
            let seconds = 5;
            const countdownElement = document.getElementById('countdown');
            
            const countdown = setInterval(function() {
                seconds--;
                countdownElement.textContent = seconds;
                
                if (seconds <= 0) {
                    clearInterval(countdown);
                    window.location.href = 'feedback.php';
                }
            }, 1000);
        </script>
        
    <?php else: ?>
        <div class="confirmation-error-header">
            <h1>Error!</h1>
            <p>There was a problem deleting the feedback</p>
        </div>
        <div class="confirmation-body">
            <div class="error-message">
                <?php echo $errorMsg; ?>
            </div>
            
            <div class="actions">
                <a href="feedback.php" class="redirect-button error-button">Return to Feedback List</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include "inc/footer.inc.php"; ?>
    </main>
</body>
</html>