<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'inc/sql.inc.php';
require_once __DIR__ . '/inc/csrf.php';
require_once __DIR__ . '/inc/cookie_admin.php';

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
    <?php
    include "inc/head.inc.php"
    ?>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/admin.css">
    <style>
        header, .admin-bar, #admin-header, .navbar {
        position: static !important;
        }

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
            background-color: #D32F2F;
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
        
        .confirmation-body {
            padding: 15px;
        }
        
        .feedback-details {
            background-color: #f9f9f9;
            border-radius: 6px;
            padding: 12px;
            margin: 10px 0;
            border-left: 4px solid #F44336;
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
        
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        
        .action-btn {
            display: inline-block;
            padding: 8px 20px;
            text-align: center;
            text-decoration: none;
            font-size: 14px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .delete-btn {
            background-color: #D32F2F;
            color: white;
        }
        
        .delete-btn:hover {
            background-color: #D32F2F;
        }
        
        .cancel-btn {
            background-color: #757575;
            color: white;
        }
        
        .cancel-btn:hover {
            background-color: #616161;
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
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-btn {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <main>

<div class="confirmation-container">
    <div class="confirmation-header">
        <h1>Confirm Deletion</h1>
        <p>Are you sure you want to delete this feedback?</p>
    </div>
    <div class="confirmation-body">
        <h2>Feedback Details</h2>
        <div class="feedback-details">
            <div class="feedback-item">
                <div class="feedback-label">Name:</div>
                <div class="feedback-value"><?php echo htmlspecialchars($feedback['Feedback_Name']); ?></div>
            </div>
            
            <div class="feedback-item">
                <div class="feedback-label">Email:</div>
                <div class="feedback-value"><?php echo htmlspecialchars($feedback['Feedback_Email']); ?></div>
            </div>
            
            <div class="feedback-item">
                <div class="feedback-label">Message:</div>
                <div class="feedback-value feedback-message"><?php echo htmlspecialchars($feedback['Feedback_Msg']); ?></div>
            </div>
            
            <div class="feedback-item">
                <div class="feedback-label">Submitted At:</div>
                <div class="feedback-value"><?php echo htmlspecialchars($feedback['Submitted_At']); ?></div>
            </div>
        </div>
        
        <form action="deletefeedback_process.php" method="POST">
            <input type="hidden" name="id" value="<?= $feedback['Feedback_ID'] ?>">
            <?php csrfInputField(); ?>
            
            <div class="action-buttons">
                <button type="submit" class="action-btn delete-btn">Delete Permanently</button>
                <a href="feedback.php" class="action-btn cancel-btn">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include "inc/footer.inc.php"; ?>
    </main>
</body>
</html>