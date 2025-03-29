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

try {
    $conn = getDatabaseConnection();
    
    // Validate required fields
    $required = ['name', 'dob', 'dop', 'plotno', 'latitude', 'longitude'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    // Sanitize inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $plotNo = htmlspecialchars(trim($_POST['plotno']));
    $latitude = (float)$_POST['latitude'];
    $longitude = (float)$_POST['longitude'];

    // Validate coordinates
    if ($latitude < -90 || $latitude > 90) {
        throw new Exception("Invalid latitude: must be between -90 and 90");
    }
    if ($longitude < -180 || $longitude > 180) {
        throw new Exception("Invalid longitude: must be between -180 and 180");
    }

    // Validate dates
    $dob = new DateTime($_POST['dob']);
    $dop = new DateTime($_POST['dop']);
    if ($dop < $dob) {
        throw new Exception("Invalid dates: Date of passing cannot be before date of birth");
    }
    $age = $dob->diff($dop)->y;

    // Process file upload
    $imagePath = null;
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
        
        $imagePath = $targetPath;
    }

    // Check plot availability
    $checkStmt = $conn->prepare("SELECT Memorial_MapID FROM Memorial_Map_Data WHERE PlotNumber = ?");
    $checkStmt->bind_param("s", $plotNo);
    $checkStmt->execute();
    if ($checkStmt->get_result()->num_rows > 0) {
        throw new Exception("Plot number $plotNo is already in use.");
    }

    // Insert record
    $stmt = $conn->prepare("
        INSERT INTO Memorial_Map_Data (Name, DateOfBirth, DateOfPassing, Age, Religion, PlotNumber, Image, RestingType, Latitude, Longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sssissssss",
        $name,
        $_POST['dob'],
        $_POST['dop'],
        $age,
        $_POST['religion'],
        $plotNo,
        $imagePath,
        $_POST['restingtype'],
        $latitude,
        $longitude
    );

    if ($stmt->execute()) {
        echo "<script>alert('Grave added successfully!');
        window.location.href = 'admin.php';
        </script>";
        exit();
    } else {
        throw new Exception("Database error: " . $stmt->error);
    }

} catch (Exception $e) {
    // Log error
    error_log("[".date('Y-m-d H:i:s')."] Add Grave Error: " . $e->getMessage());
    
    // Redirect back with error
    header("Location: addgraves.php?error=" . urlencode($e->getMessage()));
    exit();
}