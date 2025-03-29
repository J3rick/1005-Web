<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'inc/sql.inc.php';

try {
    $conn = getDatabaseConnection();
    
    // Validate ID
    if (empty($_POST['id'])) {
        throw new Exception("No record ID specified for deletion");
    }
    
    $id = (int)$_POST['id'];
    $image_path = $_POST['image_path'] ?? null;

    // Get record before deletion (for confirmation message)
    $stmt = $conn->prepare("SELECT * FROM Memorial_Map_Data WHERE Memorial_MapID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $grave = $result->fetch_assoc();

    if (!$grave) {
        throw new Exception("Record not found");
    }

    // Delete the record
    $deleteStmt = $conn->prepare("DELETE FROM Memorial_Map_Data WHERE Memorial_MapID = ?");
    $deleteStmt->bind_param("i", $id);
    
    if ($deleteStmt->execute()) {
        echo "<script>alert('Grave deleted successfully!');
        window.location.href = 'viewgraves.php';
        </script>";
        exit();
    }
    else {
        throw new Exception("Database error: " . $deleteStmt->error);
    }

    // Delete associated image file if exists
    if ($image_path && file_exists($image_path)) {
        unlink($image_path);
    }

    // Redirect with success message
    header("Location: viewgraves.php?success=1&action=delete&plot=" . urlencode($grave['PlotNumber']));
    exit();

} catch (Exception $e) {
    // Log error
    error_log("[".date('Y-m-d H:i:s')."] Delete Grave Error: " . $e->getMessage());
    
    // Redirect back with error
    header("Location: viewgraves.php?error=" . urlencode($e->getMessage()));
    exit();
}