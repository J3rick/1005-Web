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

// Build SQL query with search filters - MAKE SURE TO INCLUDE LATITUDE AND LONGITUDE
$sql = "SELECT *, Latitude, Longitude FROM Memorial_Map_Data WHERE Memorial_MapID = $id";

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

    <!-- Mapbox CSS -->
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.10.0/mapbox-gl.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="css/main.css">
    <style>
        /* Map container styling */
        .map-container {
            width: 100%;
            height: 400px;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        #map {
            width: 100%;
            height: 100%;
            border-radius: 5px;
        }
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
                <img src="assets/portraits/<?= htmlspecialchars($memorial['Image']) ?>" alt="Memorial Image" style="width: 500px; height: auto;">
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
        <div class="row">
        <div class="map-container">
            <div id="map"></div>
        </div>
        
        </div>
    </section>
    
    <?php
    include "inc/footer.inc.php"
    ?>
    
    <!-- Mapbox JS -->
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.10.0/mapbox-gl.js"></script>
    <script src="js/main.js"></script>
    
    <script>
    // Initialize map only if coordinates exist
    <?php if (isset($memorial['Latitude']) && isset($memorial['Longitude']) && 
             !empty($memorial['Latitude']) && !empty($memorial['Longitude'])) : ?>
    
    // Set Mapbox access token
    mapboxgl.accessToken = 'pk.eyJ1IjoiYXlkaWxraGFpcjkiLCJhIjoiY204czlpZjM4MTFhZzJpc2FzdWFyYWg4MiJ9.zbnLvW0qIwRuxE74jf4QIw';
    
    // Convert coordinates to numbers
    const lat = parseFloat('<?= $memorial['Latitude'] ?>');
    const lng = parseFloat('<?= $memorial['Longitude'] ?>');
    
    // Initialize map
    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v12',
        center: [lng, lng],
        zoom: 17
    });
    
    // Add navigation controls
    map.addControl(new mapboxgl.NavigationControl());
    
    // Add a marker for the memorial location
    const popup = new mapboxgl.Popup({ offset: 25 })
        .setHTML(`
            <strong><?= htmlspecialchars($memorial['Name']) ?></strong><br>
            Plot: <?= htmlspecialchars($memorial['PlotNumber']) ?>
        `);
    
    // Create a marker
    new mapboxgl.Marker({ color: '#000000' })
        .setLngLat([lng, lat])
        .setPopup(popup)
        .addTo(map);
    
    <?php else: ?>
        // Display message if coordinates not available
        document.querySelector('.map-container').innerHTML = '<p class="text-center">Location coordinates not available for this memorial.</p>';
    <?php endif; ?>
    </script>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>