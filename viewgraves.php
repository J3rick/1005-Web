<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    include 'inc/head.inc.php';
    include 'inc/adminbar.inc.php';
    include 'inc/sql.inc.php';
    include 'inc/footer.inc.php';
    $conn = getDatabaseConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cemetery Management System</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/viewgraves.css">
    <script src = js/admin.js></script>
</head>

<body>
    <div class="content">
        <!-- Put both h1 and search into same container -->
        <div class = "header-container">
            <h1>View Graves</h1>
            <div class="search-container">
                <form action="" method="GET">
                    <input type="text" name="search" id="search-input" placeholder="Search graves..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="search-btn">Search</button>
            </form>
            </div>
        </div>

        
        <table class="graves-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Date of Birth</th>
                    <th>Date of Death</th>
                    <th>Age</th>
                    <th>Religion</th>
                    <th>Plot</th>
                    <th>Image</th>
                    <th>Resting Type</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                $query= $conn->prepare('SELECT * from Memorial_Map.Memorial_Map_Data'); 
                $query->execute();
                $query->store_result();
                $query->bind_result($id, $name, $dob, $dod, $age, $religion, $plot, $image, $restingType, $latitude, $longitude);
                if($query->num_rows>0){

                }
                else{
                    echo "No graves in database.";
                }
                while ($query->fetch()){
                    echo "<tr>
                    <td data-label='ID'>$id</td>
                    <td data-label='Name'>$name</td>
                    <td data-label='Birth'>$dob</td>
                    <td data-label='Death'>$dod</td>
                    <td data-label='Age'>$age</td>
                    <td data-label='Religion'>$religion</td>
                    <td data-label='Plot'>$plot</td>
                    <td data-label='Image'>$image</td>
                    <td data-label='RestingType'>$restingType</td>
                    <td data-label='Latitude'>$latitude</td>
                    <td data-label='Longtitude'>$longitude</td>
                    <td data-label='Actions'>
                        <a href='editgraves.php?id=$id' class='action-btn'>Edit</a>
                        <a href='deletegraves.php?id=$id' class='action-btn'>Delete</a>
                    </td>          
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
