<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'inc/head.inc.php';
include 'inc/adminbar.inc.php';
include 'inc/sql.inc.php';

// Get grave record to delete
$conn = getDatabaseConnection();
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: viewgraves.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM Memorial_Map_Data WHERE Memorial_MapID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$grave = $result->fetch_assoc();

if (!$grave) {
    header("Location: viewgraves.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Grave Record - MemorialMap</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/deletegraves.css">
</head>
<body>
    <section class="delete-confirm">
        <div class="delete-confirm-content">
            <h1>Confirm Deletion</h1>
            
            <div class="grave-details">
                <p><strong>Name:</strong> <?= htmlspecialchars($grave['Name']) ?></p>
                <p><strong>Plot Number:</strong> <?= htmlspecialchars($grave['PlotNumber']) ?></p>
                <p><strong>Resting Type:</strong> <?= htmlspecialchars($grave['RestingType']) ?></p>
            </div>
            
            <p class="warning-message">Are you sure you want to permanently delete this record?</p>
            
            <form action="deletegraves_process.php" method="POST">
                <input type="hidden" name="id" value="<?= $grave['Memorial_MapID'] ?>">
                <input type="hidden" name="image_path" value="<?= htmlspecialchars($grave['Image']) ?>">
                
                <div class="delete-actions">
                    <button type="submit" class="confirm-delete-btn">Delete Permanently</button>
                    <a href="viewgraves.php" class="cancel-btn">Cancel</a>
                </div>
            </form>
        </div>
    </section>

    <?php include "inc/footer.inc.php"; ?>
</body>
</html>