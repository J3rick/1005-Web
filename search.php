<?php
// Database connection configuration - adjust these values to match your setup
$servername = "localhost";
$username = "inf1005-sqldev";
$password = "P@ssw0rd123";
$dbname = "Memorial_Map";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search parameters from URL
$name = isset($_GET['name']) ? $_GET['name'] : '';
$dob_from = isset($_GET['dob_from']) ? $_GET['dob_from'] : '';
$dob_to = isset($_GET['dob_to']) ? $_GET['dob_to'] : '';
$dod_from = isset($_GET['dod_from']) ? $_GET['dod_from'] : ''; // Fixed typo from dop_from
$dod_to = isset($_GET['dod_to']) ? $_GET['dod_to'] : '';       // Fixed typo from dop_to
$religion = isset($_GET['religion']) ? $_GET['religion'] : '';
$resting_type = isset($_GET['resting_type']) ? $_GET['resting_type'] : [];
$location = isset($_GET['location']) ? $_GET['location'] : '';

// Build SQL query with search filters
$sql = "SELECT * FROM Memorial_Map_Data WHERE 1=1";

// Add name filter
if (!empty($name)) {
    $sql .= " AND Name LIKE '%" . $conn->real_escape_string($name) . "%'";
}

// Add date of birth range filter
if (!empty($dob_from)) {
    $sql .= " AND DateOfBirth >= '" . $conn->real_escape_string($dob_from) . "'";
}
if (!empty($dob_to)) {
    $sql .= " AND DateOfBirth <= '" . $conn->real_escape_string($dob_to) . "'";
}

// Add date of death range filter
if (!empty($dod_from)) {
    $sql .= " AND DateOfPassing >= '" . $conn->real_escape_string($dod_from) . "'";
}
if (!empty($dod_to)) {
    $sql .= " AND DateOfPassing <= '" . $conn->real_escape_string($dod_to) . "'";
}

// Add religion filter
if (!empty($religion)) {
    $sql .= " AND Religion = '" . $conn->real_escape_string($religion) . "'";
}

// Add resting type filter
if (!empty($resting_type) && is_array($resting_type)) {
    $escapedTypes = array_map(function ($type) use ($conn) {
        return "'" . $conn->real_escape_string($type) . "'";
    }, $resting_type);
    $sql .= " AND RestingType IN (" . implode(',', $escapedTypes) . ")";
}

// Add sorting
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';
switch ($sort) {
    case 'name_desc':
        $sql .= " ORDER BY Name DESC";
        break;
    case 'dob_asc':
        $sql .= " ORDER BY DateOfBirth ASC";
        break;
    case 'dob_desc':
        $sql .= " ORDER BY DateOfBirth DESC";
        break;
    case 'dod_asc':
        $sql .= " ORDER BY DateOfPassing ASC";
        break;
    case 'dod_desc':
        $sql .= " ORDER BY DateOfPassing DESC";
        break;
    case 'name_asc':
    default:
        $sql .= " ORDER BY Name ASC";
        break;
}

// Pagination setup
$results_per_page = 12;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $results_per_page;

// Get total count for pagination
$count_sql = str_replace("SELECT *", "SELECT COUNT(*)", $sql);
$count_result = $conn->query($count_sql);
$total_results = $count_result->fetch_row()[0];
$total_pages = ceil($total_results / $results_per_page);

// Add pagination limits
$sql .= " LIMIT $offset, $results_per_page";

// Execute the query
$result = $conn->query($sql);
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <style>
        /* Additional styles for search page */
        .filter-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .filter-title {
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .result-tile {
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
            padding: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .result-tile:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .result-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
            border-radius: 3px;
        }

        .result-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 18px;
        }

        .result-meta {
            color: #6c757d;
            font-size: 0.9em;
            margin-bottom: 10px;
        }

        .result-description {
            margin-bottom: 10px;
        }

        .results-header {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .filter-group {
            margin-bottom: 15px;
        }

        .checkbox-group {
            margin-left: 5px;
        }

        .filter-reset {
            text-align: center;
            margin-top: 20px;
        }

        .date-range {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .date-range input {
            width: 45%;
        }

        .date-range span {
            width: 10%;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row content">
            <!-- Left side - Filters -->
            <div class="col-sm-3 sidenav">
                <h3>Filter Results</h3>

                <form action="search.php" method="GET" id="searchForm">
                    <div class="filter-section">
                        <div class="filter-title">Name</div>
                        <div class="filter-group">
                            <input type="text" class="form-control" name="name" placeholder="Enter name"
                                value="<?php echo htmlspecialchars($name); ?>">
                        </div>
                    </div>

                    <div class="filter-section">
                        <div class="filter-title">Date of Birth</div>
                        <div class="filter-group">
                            <div class="date-range">
                                <input type="date" class="form-control" name="dob_from"
                                    value="<?php echo htmlspecialchars($dob_from); ?>">
                                <span>to</span>
                                <input type="date" class="form-control" name="dob_to"
                                    value="<?php echo htmlspecialchars($dob_to); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="filter-section">
                        <div class="filter-title">Date of Death</div>
                        <div class="filter-group">
                            <div class="date-range">
                                <input type="date" class="form-control" name="dod_from"
                                    value="<?php echo htmlspecialchars($dod_from); ?>">
                                <span>to</span>
                                <input type="date" class="form-control" name="dod_to"
                                    value="<?php echo htmlspecialchars($dod_to); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="filter-section">
                        <div class="filter-title">Religion</div>
                        <div class="filter-group">
                            <select class="form-control" name="religion">
                                <option value="" <?php echo $religion == '' ? 'selected' : ''; ?>>All Religions</option>
                                <option value="Buddhism" <?php echo $religion == 'Buddhism' ? 'selected' : ''; ?>>Buddhism
                                </option>
                                <option value="Christianity" <?php echo $religion == 'Christianity' ? 'selected' : ''; ?>>
                                    Christianity</option>
                                <option value="Hinduism" <?php echo $religion == 'Hinduism' ? 'selected' : ''; ?>>Hinduism
                                </option>
                                <option value="Islam" <?php echo $religion == 'Islam' ? 'selected' : ''; ?>>Islam</option>
                                <option value="Judaism" <?php echo $religion == 'Judaism' ? 'selected' : ''; ?>>Judaism
                                </option>
                                <option value="Taoism" <?php echo $religion == 'Taoism' ? 'selected' : ''; ?>>Taoism
                                </option>
                                <option value="Other" <?php echo $religion == 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="filter-section">
                        <div class="filter-title">Resting Place Type</div>
                        <div class="filter-group">
                            <div class="checkbox-group">
                                <label><input type="checkbox" name="resting_type[]" value="Burial" <?php echo in_array('Burial', (array) $resting_type) ? 'checked' : ''; ?>>
                                    Burial</label>
                            </div>
                            <div class="checkbox-group">
                                <label><input type="checkbox" name="resting_type[]" value="Cremation" <?php echo in_array('Cremation', (array) $resting_type) ? 'checked' : ''; ?>>
                                    Cremation</label>
                            </div>
                            <div class="checkbox-group">
                                <label><input type="checkbox" name="resting_type[]" value="Mausoleum" <?php echo in_array('Mausoleum', (array) $resting_type) ? 'checked' : ''; ?>>
                                    Mausoleum</label>
                            </div>
                            <div class="checkbox-group">
                                <label><input type="checkbox" name="resting_type[]" value="Memorial Garden" <?php echo in_array('Memorial Garden', (array) $resting_type) ? 'checked' : ''; ?>>
                                    Memorial Garden</label>
                            </div>
                            <div class="checkbox-group">
                                <label><input type="checkbox" name="resting_type[]" value="Sea Burial" <?php echo in_array('Sea Burial', (array) $resting_type) ? 'checked' : ''; ?>>
                                    Sea Burial</label>
                            </div>
                        </div>
                    </div>

                    <div class="filter-section">
                        <div class="filter-title">Location</div>
                        <div class="filter-group">
                            <select class="form-control" name="location">
                                <option value="">All Locations</option>
                                <option value="north" <?php echo $location == 'north' ? 'selected' : ''; ?>>North Region
                                </option>
                                <option value="south" <?php echo $location == 'south' ? 'selected' : ''; ?>>South Region
                                </option>
                                <option value="east" <?php echo $location == 'east' ? 'selected' : ''; ?>>East Region
                                </option>
                                <option value="west" <?php echo $location == 'west' ? 'selected' : ''; ?>>West Region
                                </option>
                                <option value="central" <?php echo $location == 'central' ? 'selected' : ''; ?>>Central
                                    Region</option>
                            </select>
                        </div>
                    </div>

                    <!-- Hidden field for sort parameter -->
                    <input type="hidden" name="sort" id="currentSort" value="<?php echo htmlspecialchars($sort); ?>">

                    <div class="filter-group">
                        <button type="submit" class="btn btn-primary btn-block">Apply Filters</button>
                    </div>

                    <div class="filter-reset">
                        <button type="button" class="btn btn-default" id="resetFilters">Reset Filters</button>
                    </div>
                </form>
            </div>

            <!-- Right side - Results -->
            <div class="col-sm-9">
                <div class="results-header">
                    <h2>Search Results</h2>
                    <div class="form-group">
                        <select class="form-control" id="sortResults">
                            <option value="name_asc" <?php echo $sort == 'name_asc' ? 'selected' : ''; ?>>Sort by: Name
                                (A-Z)</option>
                            <option value="name_desc" <?php echo $sort == 'name_desc' ? 'selected' : ''; ?>>Name (Z-A)
                            </option>
                            <option value="dob_asc" <?php echo $sort == 'dob_asc' ? 'selected' : ''; ?>>Birth Date (Oldest
                                First)</option>
                            <option value="dob_desc" <?php echo $sort == 'dob_desc' ? 'selected' : ''; ?>>Birth Date
                                (Newest First)</option>
                            <option value="dod_asc" <?php echo $sort == 'dod_asc' ? 'selected' : ''; ?>>Death Date (Oldest
                                First)</option>
                            <option value="dod_desc" <?php echo $sort == 'dod_desc' ? 'selected' : ''; ?>>Death Date
                                (Newest First)</option>
                        </select>
                    </div>
                </div>

                <p>Showing <?php echo $result->num_rows; ?> of <?php echo $total_results; ?> results</p>

                <div class="row">
                    <?php
                    // Display results
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Calculate age (might need adjustment depending on your date format)
                            $birthDate = new DateTime($row['DateOfBirth']);
                            $deathDate = new DateTime($row['DateOfPassing']);
                            $age = $birthDate->diff($deathDate)->y;

                            // Format dates for display
                            $formattedBirthDate = date('M d, Y', strtotime($row['DateOfBirth']));
                            $formattedDeathDate = date('M d, Y', strtotime($row['DateOfPassing']));

                            // Get image path or use placeholder
                            $imagePath = !empty($row['Image']) ? "assets/portraits/" . $row['Image'] : "https://via.placeholder.com/300x150";

                            // Output the memorial card
                            echo '<div class="col-md-4">
                                <div class="result-tile">
                                    <img src="' . htmlspecialchars($imagePath) . '" alt="Memorial Photo" class="result-image">
                                    <div class="result-title">' . htmlspecialchars($row['Name']) . '</div>
                                    <div class="result-meta">
                                        <i class="fa fa-birthday-cake"></i> ' . $formattedBirthDate . ' &nbsp;|&nbsp;
                                        <i class="fa fa-cross"></i> ' . $formattedDeathDate . '
                                    </div>
                                    <div class="result-meta">
                                        <i class="fa fa-church"></i> ' . htmlspecialchars($row['Religion']) . ' &nbsp;|&nbsp;
                                        <i class="fa fa-map-marker"></i> ' . htmlspecialchars($row['PlotNumber']) . '
                                    </div>
                                    <div class="result-meta">
                                        <i class="fa fa-monument"></i> ' . htmlspecialchars($row['RestingType']) . '
                                    </div>
                                    <a href="memorial.php?id=' . $row['Memorial_MapID'] . '" class="btn btn-primary btn-sm">View Memorial</a>
                                </div>
                            </div>';
                        }
                    } else {
                        echo '<div class="col-md-12"><p>No results found. Please try different search criteria.</p></div>';
                    }
                    ?>
                </div>

                <!-- 
                PAGINATION SECTION
                This code creates numbered page links with Previous/Next buttons
                Only displays if we have more than one page of results 
                -->
                <?php
                // Only show pagination if we have multiple pages
                if ($total_pages > 1):
                    ?>
                    <nav aria-label="Search results pages">
                        <ul class="pagination">
                            <!-- PREVIOUS PAGE BUTTON -->
                            <?php
                            // Check if we're on the first page
                            $is_first_page = ($page <= 1);

                            // If we're on first page, disable the Previous button
                            if ($is_first_page) {
                                $prev_class = "disabled";
                                $prev_link = "#"; // Link doesn't go anywhere when disabled
                            } else {
                                $prev_class = "";
                                // Create link to previous page while keeping other URL parameters
                                $prev_link = "?" . http_build_query(
                                    array_merge($_GET, ['page' => $page - 1])
                                );
                            }
                            ?>
                            <li class="<?php echo $prev_class; ?>">
                                <a href="<?php echo $prev_link; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span> <!-- Left arrow symbol -->
                                </a>
                            </li>

                            <!-- NUMBERED PAGE BUTTONS -->
                            <?php
                            // Loop through all page numbers
                            for ($i = 1; $i <= $total_pages; $i++):
                                // Highlight the current page
                                $is_current = ($page == $i);

                                // Add URL parameters to the link but change the page number
                                $page_link = "?" . http_build_query(
                                    array_merge($_GET, ['page' => $i])
                                );
                                ?>
                                <li class="<?php echo $is_current ? 'active' : ''; ?>">
                                    <a href="<?php echo $page_link; ?>">
                                        <?php echo $i; ?>
                                        <?php
                                        // Add hidden text for screen readers on current page
                                        if ($is_current) {
                                            echo '<span class="sr-only">(current)</span>';
                                        }
                                        ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <!-- NEXT PAGE BUTTON -->
                            <?php
                            // Check if we're on the last page
                            $is_last_page = ($page >= $total_pages);

                            // If we're on last page, disable the Next button
                            if ($is_last_page) {
                                $next_class = "disabled";
                                $next_link = "#"; // Link doesn't go anywhere when disabled
                            } else {
                                $next_class = "";
                                // Create link to next page while keeping other URL parameters
                                $next_link = "?" . http_build_query(
                                    array_merge($_GET, ['page' => $page + 1])
                                );
                            }
                            ?>
                            <li class="<?php echo $next_class; ?>">
                                <a href="<?php echo $next_link; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span> <!-- Right arrow symbol -->
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?> <!-- End of "if multiple pages" condition -->
            </div>
        </div>
    </div>

    <?php
    include "inc/footer.inc.php"
        ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script>
        $(document).ready(function () {
            // Reset filter form
            $('#resetFilters').click(function () {
                $('#searchForm')[0].reset();
                // Submit the form after reset to refresh results
                $('#searchForm').submit();
            });

            // Sort results
            $('#sortResults').change(function () {
                // Update the hidden sort field
                $('#currentSort').val($(this).val());
                // Submit the form to apply the sort
                $('#searchForm').submit();
            });
        });
    </script>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>