<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    include 'inc/head.inc.php';
    include 'inc/adminbar.inc.php';
    include 'inc/sql.inc.php';
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
    <script src = js/admin.js></script>
</head>
<style>
    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 10px;
        border-bottom: 2px solid #333;
    }

    h1 {
        margin-left: 30px;
        color: #333;
        font-size: 28px;
    }

    /* Search container styling */
    .search-container {
        display: flex;
        align-items: center;
        margin-left: 20px;
    }

    #search-input {
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 4px 0 0 4px;
        font-size: 14px;
        width: 200px;
        outline: none;
    }

    #search-input:focus {
        border-color: #3498db;
        box-shadow: 0 0 3px rgba(52, 152, 219, 0.5);
    }

    .search-btn {
        background-color: #333;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 0 4px 4px 0;
        cursor: pointer;
        font-size: 14px;
    }

    .search-btn:hover {
        background-color: #555;
    }

    /* Table styling */
    .graves-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 4px;
        overflow: hidden;
    }

    .graves-table th {
        background-color: #333;
        color: white;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        position: sticky;
        top: 0;
    }

    .graves-table td {
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
        color: #444;
    }

    .graves-table tr:hover {
        background-color: #f5f5f5;
    }

    .graves-table tr:last-child td {
        border-bottom: none;
    }

    /* Zebra striping for better readability */
    .graves-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    /* ID column styling */
    .graves-table td:first-child {
        font-weight: 600;
        color: #555;
    }

    /* Add some color coding for resting type */
    .graves-table td:last-child {
        font-weight: 600;
    }
    
    /* Responsive adjustments */
    @media screen and (max-width: 1200px) {
        .graves-table {
            font-size: 14px;
        }
        
        .graves-table th, .graves-table td {
            padding: 8px 10px;
        }
    }
    
    /* Optional: Add action buttons styling */
    .action-btn {
        display: inline-block;
        padding: 6px 10px;
        background-color: #333;
        color: white;
        text-decoration: none;
        border-radius: 3px;
        margin-right: 5px;
        font-size: 12px;
    }
    
    .action-btn:hover {
        background-color: #555;
    }
    
</style>

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
                </tr>
            </thead>

            <tbody>
                <?php 
                $query= $conn->prepare('SELECT * from Memorial_Map.Memorial_Map_Data'); 
                $query->execute();
                $query->store_result();
                $query->bind_result($id, $name, $dob, $dod, $age, $religion, $plot, $image, $restingType);
                if($query->num_rows>0){

                }
                else{
                    echo "No graves in database.";
                }
                while ($query->fetch()){
                    echo
                    "<tr>
                <td>$id</td>
                <td>$name</td>
                <td>$dob</td>
                <td>$dod</td>
                <td>$age</td>
                <td>$religion</td>
                <td>$plot</td>
                <td>$image</td>
                <td>$restingType</td>          
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
