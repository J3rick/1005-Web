<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'inc/head.inc.php';
include 'inc/adminbar.inc.php';
include 'inc/sql.inc.php';
require_once __DIR__ . '/inc/cookie_admin.php';

// Get grave record to edit
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
    <title>Edit Grave Record - MemorialMap</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/editgrave.css">
</head>
<body>
    <section class="edit-form">
        <div class="edit-form-content">
            <h1>Edit Grave Record</h1>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="error-message"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>
            
            <form class="editgrave-form" action="editgraves_process.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $grave['Memorial_MapID'] ?>">
                
                <div class="editform-grp">
                    <label for="name">Deceased Name:*</label>
                    <input type="text" id="name" name="name" required 
                           value="<?= htmlspecialchars($grave['Name']) ?>">
                </div>

                <div class="editform-row">
                    <div class="editform-grp">
                        <label for="dob">Date of Birth:*</label>
                        <input type="date" id="dob" name="dob" required 
                               value="<?= htmlspecialchars($grave['DateOfBirth']) ?>">
                    </div>
                    <div class="editform-grp">
                        <label for="dop">Date of Passing:*</label>
                        <input type="date" id="dop" name="dop" required 
                               value="<?= htmlspecialchars($grave['DateOfPassing']) ?>">
                    </div>
                </div>

                <div class="editform-grp">
                    <label for="religion">Religion:</label>
                    <select id="religion" name="religion">
                        <option value="Christianity" <?= $grave['Religion'] == 'Christianity' ? 'selected' : '' ?>>Christianity</option>
                        <option value="Islam" <?= $grave['Religion'] == 'Islam' ? 'selected' : '' ?>>Islam</option>
                        <option value="Judaism" <?= $grave['Religion'] == 'Judaism' ? 'selected' : '' ?>>Judaism</option>
                        <option value="Buddhism" <?= $grave['Religion'] == 'Buddhism' ? 'selected' : '' ?>>Buddhism</option>
                        <option value="Hinduism" <?= $grave['Religion'] == 'Hinduism' ? 'selected' : '' ?>>Hinduism</option>
                        <option value="Other" <?= $grave['Religion'] == 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>

                <div class="editform-grp">
                    <label for="plotno">Plot Number:*</label>
                    <input type="text" id="plotno" name="plotno" required 
                           value="<?= htmlspecialchars($grave['PlotNumber']) ?>"
                           pattern="[A-Za-z0-9-]+" title="Letters, numbers, and hyphens only">
                </div>

                <div class="editform-grp">
                    <label for="restingtype">Resting Type:*</label>
                    <select id="restingtype" name="restingtype" required>
                        <option value="Burial" <?= $grave['RestingType'] == 'Burial' ? 'selected' : '' ?>>Burial</option>
                        <option value="Cremation" <?= $grave['RestingType'] == 'Cremation' ? 'selected' : '' ?>>Cremation</option>
                        <option value="Mausoleum" <?= $grave['RestingType'] == 'Mausoleum' ? 'selected' : '' ?>>Mausoleum</option>
                        <option value="Other" <?= $grave['RestingType'] == 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>

                <div class="editform-grp">
                    <label>Current Obituary:</label>
                    <p><?= htmlspecialchars(basename($grave['Image'])) ?></p>
                    <label for="image">Upload New Obituary (Image/PDF):</label>
                    <input type="file" id="image" name="image" accept="image/*,.pdf">
                    <small>Max 5MB (JPEG, PNG, or PDF). Leave empty to keep current file.</small>
                </div>

                <div class="editform-grp">
                    <label for="latitude">Latitude:*</label>
                    <input type="text" id="latitude" name="latitude" required
                           value="<?= htmlspecialchars($grave['Latitude']) ?>"
                           pattern="-?\d{1,3}\.\d{1,6}" 
                           title="Decimal format (e.g. 1.3521 or -12.345678)">
                </div>

                <div class="editform-grp">
                    <label for="longitude">Longitude:*</label>
                    <input type="text" id="longitude" name="longitude" required
                           value="<?= htmlspecialchars($grave['Longitude']) ?>"
                           pattern="-?\d{1,3}\.\d{1,6}"
                           title="Decimal format (e.g. 103.8198 or -120.123456)">
                </div>

                <div class="edit-actions">
                    <button type="submit" class="save-btn">Save Changes</button>
                    <a href="viewgraves.php" class="cancel-btn">Cancel</a>
                </div>
            </form>
        </div>
    </section>

    <?php include "inc/footer.inc.php"; ?>
</body>
</html>