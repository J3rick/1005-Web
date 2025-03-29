<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'inc/sql.inc.php';
require_once __DIR__ . '/inc/cookie_admin.php';
require_once __DIR__ . '/inc/csrf.php';


// Validate CSRF Token

if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
    $errorMsg .= "CSRF token validation failed.<br>";
    $success = false;
}

/**
 * Sanitizes input data
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

try {
    $conn = getDatabaseConnection();
    
    // Validate required fields
    $required = ['id', 'name', 'dob', 'dop', 'plotno', 'latitude', 'longitude'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    // Sanitize inputs
    $id = (int)$_POST['id'];
    $name = sanitize_input($_POST['name']);
    $plotNo = sanitize_input($_POST['plotno']);
    $latitude = sanitize_input($_POST['latitude']);
    $longitude = sanitize_input($_POST['longitude']);

    // Validate coordinates
    if (!is_numeric($latitude) || $latitude < -90 || $latitude > 90) {
        throw new Exception("Invalid latitude value");
    }
    if (!is_numeric($longitude) || $longitude < -180 || $longitude > 180) {
        throw new Exception("Invalid longitude value");
    }

    // Validate dates
    $dob = new DateTime($_POST['dob']);
    $dop = new DateTime($_POST['dop']);
    if ($dop < $dob) {
        throw new Exception("Date of passing cannot be before date of birth");
    }
    $age = $dob->diff($dop)->y;

    // Get current image path
    $currentStmt = $conn->prepare("SELECT Image FROM Memorial_Map_Data WHERE Memorial_MapID = ?");
    $currentStmt->bind_param("i", $id);
    $currentStmt->execute();
    $currentResult = $currentStmt->get_result();
    $currentData = $currentResult->fetch_assoc();
    $imagePath = $currentData['Image'];

    // Process file upload if present
    if (!empty($_FILES['image']['name'])) {
        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'application/pdf' => 'pdf'
        ];
        
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $_FILES['image']['tmp_name']);
        
        if (!array_key_exists($mimeType, $allowedTypes)) {
            throw new Exception("Invalid file type. Only JPG, PNG, or PDF allowed.");
        }

        if ($_FILES['image']['size'] > 5000000) { // 5MB
            throw new Exception("File too large. Maximum 5MB allowed.");
        }

        $extension = $allowedTypes[$mimeType];
        $filename = uniqid() . '.' . $extension;
        $targetPath = 'assets/portraits/' . $filename;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            throw new Exception("Failed to save uploaded file.");
        }
        
        // Delete old file if it exists
        if ($imagePath && file_exists($imagePath)) {
            unlink($imagePath);
        }
        
        $imagePath = $targetPath;
    }

    // Update record
    $stmt = $conn->prepare("UPDATE Memorial_Map_Data SET Name = ?, DateOfBirth = ?, DateOfPassing = ?, Age = ?, Religion = ?, PlotNumber = ?, Image = ?, RestingType = ?, Latitude = ?, Longitude = ?
        WHERE Memorial_MapID = ? ");

    $stmt->bind_param(
        "sssissssssi",
        $name,
        $_POST['dob'],
        $_POST['dop'],
        $age,
        $_POST['religion'],
        $plotNo,
        $imagePath,
        $_POST['restingtype'],
        $latitude,
        $longitude,
        $id
    );

    if (!$stmt->execute()) {
        throw new Exception("Database error: " . $stmt->error);
    }

    // Success - redirect with success message
    header("Location: viewgraves.php?success=1&action=edit&plot=" . urlencode($plotNo));
    exit();

} catch (Exception $e) {
    // Log error
    error_log("[".date('Y-m-d H:i:s')."] Edit Grave Error: " . $e->getMessage());
    
    // Redirect back with error
    header("Location: editgraves.php?id=" . $_POST['id'] . "&error=" . urlencode($e->getMessage()));
    exit();
}