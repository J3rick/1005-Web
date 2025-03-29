<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'inc/head.inc.php';
include 'inc/adminbar.inc.php';
require_once __DIR__ . '/inc/cookie_admin.php';
require_once __DIR__ . '/inc/csrf.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Grave Record</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/addgraves.css">
    <style>
        .coord-group {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        .coord-group .addform-grp {
            flex: 1;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
            padding: 10px;
            background: #ffeeee;
            border: 1px solid #ffcccc;
        }
    </style>
</head>
<body>
    <section class="addform">
        <div class="addform-content">
            <h1>Add New Grave Record</h1>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="error-message"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>
            
            <form class="addgraves-form" action="addgraves_process.php" method="POST" enctype="multipart/form-data">
                <div class="addform-grp">
                    <label for="name">Deceased Name:*</label>
                    <input type="text" id="name" name="name" required minlength="2" maxlength="100">
                </div>

                <div class="addform-row">
                    <div class="addform-grp">
                        <label for="dob">Date of Birth:*</label>
                        <input type="date" id="dob" name="dob" required>
                    </div>
                    <div class="addform-grp">
                        <label for="dop">Date of Passing:*</label>
                        <input type="date" id="dop" name="dop" required>
                    </div>
                </div>

                <div class="addform-grp">
                    <label for="religion">Religion:</label>
                    <select id="religion" name="religion">
                        <option value="Christianity">Christianity</option>
                        <option value="Islam">Islam</option>
                        <option value="Judaism">Judaism</option>
                        <option value="Buddhism">Buddhism</option>
                        <option value="Hinduism">Hinduism</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="addform-grp">
                    <label for="plotno">Plot Number:*</label>
                    <input type="text" id="plotno" name="plotno" required 
                           pattern="[A-Za-z0-9-]+" title="Letters, numbers, and hyphens only">
                    <small id="plot-status"></small>
                </div>

                <div class="addform-grp">
                    <label for="restingtype">Resting Type:*</label>
                    <select id="restingtype" name="restingtype" required>
                        <option value="Burial">Burial</option>
                        <option value="Cremation">Cremation</option>
                        <option value="Mausoleum">Mausoleum</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="addform-grp">
                    <label for="image">Obituary (Image/PDF):</label>
                    <input type="file" id="image" name="image" accept="image/*,.pdf">
                    <small>Max 5MB (JPEG, PNG, or PDF)</small>
                </div>

                <div class="coord-group">
                    <div class="addform-grp">
                        <label for="latitude">Latitude:*</label>
                        <input type="text" id="latitude" name="latitude" required
                               pattern="-?\d{1,3}\.\d{1,6}" 
                               title="Decimal format (e.g. 1.3521 or -12.345678)"
                               placeholder="e.g. 1.3521">
                    </div>
                    <div class="addform-grp">
                        <label for="longitude">Longitude:*</label>
                        <input type="text" id="longitude" name="longitude" required
                               pattern="-?\d{1,3}\.\d{1,6}"
                               title="Decimal format (e.g. 103.8198 or -120.123456)"
                               placeholder="e.g. 103.8198">
                    </div>
                </div>

                <div class="add-actions">
                    <button type="submit" class="add-btn">Add Record</button>
                    <button type="reset" class="cancel-btn">Reset Form</button>
                </div>

                      <!-- CSRF token -->
                    <?php csrfInputField(); ?>
            </form>
        </div>
    </section>

    <script>
        // Plot number availability check
        document.getElementById('plotno').addEventListener('blur', function() {
            const plotNo = this.value;
            if (plotNo.length < 2) return;
            
            fetch(`check_plot.php?plot=${encodeURIComponent(plotNo)}`)
                .then(response => response.json())
                .then(data => {
                    const statusEl = document.getElementById('plot-status');
                    if (data.available) {
                        statusEl.textContent = "✓ Available";
                        statusEl.style.color = "green";
                    } else {
                        statusEl.textContent = "✗ Already in use";
                        statusEl.style.color = "red";
                    }
                });
        });

        // Date validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const dob = new Date(document.getElementById('dob').value);
            const dop = new Date(document.getElementById('dop').value);
            
            if (dop < dob) {
                alert("Date of passing cannot be before date of birth");
                e.preventDefault();
            }
            
            // Validate coordinates
            const lat = parseFloat(document.getElementById('latitude').value);
            const lng = parseFloat(document.getElementById('longitude').value);
            
            if (isNaN(lat) || lat < -90 || lat > 90) {
                alert("Latitude must be between -90 and 90");
                e.preventDefault();
            }
            
            if (isNaN(lng) || lng < -180 || lng > 180) {
                alert("Longitude must be between -180 and 180");
                e.preventDefault();
            }
        });
    </script>

    <?php include "inc/footer.inc.php"; ?>
</body>
</html>