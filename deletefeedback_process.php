<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'inc/sql.inc.php';
//require_once __DIR__ . '/inc/cookie_admin.php';
require_once __DIR__ . '/inc/csrf.php';

// Validate CSRF Token
if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
    $errorMsg = "CSRF token validation failed.<br>";
    header("Location: feedback.php?error=" . urlencode($errorMsg));
    exit();
}

try {
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
    $feedback = $result->fetch_assoc();

    if (!$feedback) {
        throw new Exception("Feedback record not found");
    }

    // Delete the record
    $deleteStmt = $conn->prepare("DELETE FROM Feedback WHERE Feedback_ID = ?");
    $deleteStmt->bind_param("i", $id);
    
    if ($deleteStmt->execute()) {
        echo "<script>alert('Feedback deleted successfully!');
        window.location.href = 'feedback.php';
        </script>";
        exit();
    }
    else {
        throw new Exception("Database error: " . $deleteStmt->error);
    }

    // Redirect with success message
    header("Location: feedback.php?success=1&action=delete&name=" . urlencode($feedback['Feedback_Name']));
    exit();

} catch (Exception $e) {
    // Log error
    error_log("[".date('Y-m-d H:i:s')."] Delete Feedback Error: " . $e->getMessage());
    
    // Redirect back with error
    header("Location: feedback.php?error=" . urlencode($e->getMessage()));
    exit();
}