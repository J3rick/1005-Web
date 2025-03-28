<?php
// Include the configuration file
require_once 'db_config.php';

// Error handling mode
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Initialize connection variable
$conn = null;
$connection_source = "";

try {
    // First try remote connection
    $conn = new mysqli(
        $config['remote']['servername'],
        $config['remote']['username'],
        $config['remote']['password'],
        $config['remote']['dbname']
    );
    $connection_source = "remote";
} 
catch (Exception $e) {
    // If remote fails, try local
    try {
        $conn = new mysqli(
            $config['local']['servername'],
            $config['local']['username'],
            $config['local']['password'],
            $config['local']['dbname']
        );
        $connection_source = "local";
    } 
    catch (Exception $e) {
        // If both connections fail, display simple error
        echo "<div style='color:red; padding:10px;'>";
        echo "<strong>Database Connection Failed</strong><br>";
        echo "Please check your MySQL server and connection settings.";
        echo "</div>";
        die();
    }
}

// Get search parameters from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Build SQL query with search filters
$sql = "SELECT * FROM Memorial_Map_Data WHERE Memorial_MapID = $id";

// Execute the query
$result = $conn->query($sql);

// Check if record exists
if ($result->num_rows > 0) {
    // Fetch the data
    $memorial = $result->fetch_assoc();
} else {
    echo "No memorial found with ID: " . $id;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MemorialMap - Search Results</title>

    <?php
    include "inc/head.inc.php"
        ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="css/main.css">
    <style>
    </style>
</head>

<body>
    <section class="memorial-information">
        <div class="section-header">
            <h3>Memorial Information</h3>
        </div>
        <!-- Row 1 -->
        <div class="row">
            <div class="column-small">
            <div class="memorial-card">
                <img src="assets/portraits/<?= htmlspecialchars($memorial['Image']) ?>" alt="poop" style="width: 500px; height: auto;">
                </div>
            </div>
            <div class="column-large">
                <div class="memorial-card">
                    <p><strong>Name:</strong> <?= htmlspecialchars($memorial['Name']) ?></p>
                    <p><strong>Date of Birth:</strong> <?= htmlspecialchars($memorial['DateOfBirth']) ?></p>
                    <p><strong>Date of Passing:</strong> <?= htmlspecialchars($memorial['DateOfPassing']) ?></p>
                    <p><strong>Age:</strong> <?= htmlspecialchars($memorial['Age']) ?></p>
                    <p><strong>Religion:</strong> <?= htmlspecialchars($memorial['Religion']) ?></p>
                    <p><strong>Plot Number:</strong> <?= htmlspecialchars($memorial['PlotNumber']) ?></p>
                    <p><strong>Resting Type:</strong> <?= htmlspecialchars($memorial['RestingType']) ?></p>
                </div>
            </div>
        </div>
        

    </section>

    <?php
    include "inc/footer.inc.php"
        ?>
    <script src="js/main.js"></script>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>