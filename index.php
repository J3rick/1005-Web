<?php
// Include the configuration file
require_once 'db_config.php';
//require_once __DIR__ . '/inc/cookie_public.php';
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
} catch (Exception $e) {
    // If remote fails, try local
    try {
        $conn = new mysqli(
            $config['local']['servername'],
            $config['local']['username'],
            $config['local']['password'],
            $config['local']['dbname']
        );
        $connection_source = "local";
    } catch (Exception $e) {
        // If both connections fail, display simple error
        echo "<div style='color:red; padding:10px;'>";
        echo "<strong>Database Connection Failed</strong><br>";
        echo "Please check your MySQL server and connection settings.";
        echo "</div>";
        die();
    }
}

require_once __DIR__ . '/inc/cookie_public.php'; // Included public_cookie 

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// Function to safely display image

// Fetch memorial data for carousel
function getMemorialCards($conn, $limit = 5)
{
    $memorials = [];

    // Prepare SQL query to fetch memorial data
    $sql = "SELECT Name, DateOfBirth, DateOfPassing, PlotNumber, Age, Image, Religion, RestingType, Memorial_MapID 
            FROM Memorial_Map_Data 
            ORDER BY DateOfPassing DESC 
            LIMIT ?";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $memorials[] = $row;
            }
        }

        $stmt->close();
    }

    return $memorials;
}

// Usage:
$memorials = getMemorialCards($conn);

// Close connection when done
// $conn->close(); // Uncomment this if you're not using the connection further in the page
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MemorialMap</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
    <?php
    include "inc/head.inc.php"
        ?>
    <main>


        <section class="hero">
            <div class="hero-content">
                <h2>Find your loved ones today.</h2>

                <form class="cemetery-search-form" action="search.php" method="GET">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" placeholder="Enter name">
                    </div>

                    <div class="form-group">
                        <label for="dob_from">Date of Birth:</label>
                        <input type="date" id="dob_from" name="dob_from">
                        <label for="dob_to">to:</label>
                        <input type="date" id="dob_to" name="dob_to">
                    </div>

                    <div class="form-group">
                        <label for="dop_from">Date of Passing:</label>
                        <input type="date" id="dop_from" name="dop_from">
                        <label for="dop_to">to:</label>
                        <input type="date" id="dop_to" name="dop_to">
                    </div>

                    <div class="form-group">
                        <label for="religion">Religion:</label>
                        <select id="religion" name="religion">
                            <option value="">All Religions</option>
                            <option value="Christianity">Christianity</option>
                            <option value="Judaism">Judaism</option>
                            <option value="Islam">Islam</option>
                            <option value="Buddhism">Buddhism</option>
                            <option value="Hinduism">Hinduism</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="religion">Resting Type:</label>
                        <div class="checkbox-row">
                            <div class="checkbox-item">
                                <input type="checkbox" id="resting_type_burial" name="resting_type[]" value="Burial">
                                <label for="resting_type_burial">Burial</label>
                            </div>
                        </div>
                        <div class="checkbox-row">
                            <div class="checkbox-item">
                                <input type="checkbox" id="resting_type_cremation" name="resting_type[]"
                                    value="Cremation">
                                <label for="resting_type_cremation">Cremation</label>
                            </div>
                        </div>
                        <div class="checkbox-row">
                            <div class="checkbox-item">
                                <input type="checkbox" id="resting_type_mausoleum" name="resting_type[]"
                                    value="Mausoleum">
                                <label for="resting_type_mausoleum">Mausoleum</label>
                            </div>
                        </div>
                        <div class="checkbox-row">
                            <div class="checkbox-item">
                                <input type="checkbox" id="resting_type_other" name="resting_type[]" value="Other">
                                <label for="resting_type_other">Other</label>
                            </div>
                        </div>
                    </div>

                    <div class="search-actions">
                        <button type="submit" class="search-btn">Search Records</button>
                        <button type="reset" class="reset-btn">Reset</button>
                    </div>
                </form>
            </div>
        </section>


        <section class="memorials">
            <div class="section-header">
                <h3>Latest memorials</h3>
                <a href="search.php?name=&dob_from=&dob_to=&dop_from=&dop_to=&religion=" style="color: #1a5b92;">Explore
                    more »</a>
            </div>
            <div class="carousel-container">
                <div class="memorial-cards">
                    <?php
                    // Get memorials from database
                    $memorials = getMemorialCards($conn);

                    if (count($memorials) > 0):
                        foreach ($memorials as $memorial):
                            ?>
                            <div class="memorial-card">
                                <div class="memorial-img-wrapper">
                                    <?php
                                    $imagePath = !empty($memorial['Image'])
                                        ? "assets/portraits/" . $memorial['Image']
                                        : "https://via.placeholder.com/300x150";
                                    ?>

                                    <img src="<?php echo htmlspecialchars($imagePath); ?>"
                                        alt="<?php echo htmlspecialchars($memorial['Name']); ?>" class="memorial-image">
                                </div>
                                <div class="memorial-info">
                                    <div class="memorial-name"><?php echo htmlspecialchars($memorial['Name']); ?></div>
                                    <div class="memorial-dates">
                                        <?php echo htmlspecialchars($memorial['DateOfBirth']); ?> -
                                        <?php echo htmlspecialchars($memorial['DateOfPassing']); ?>
                                    </div>
                                    <div class="memorial-location">
                                        Plot: <?php echo htmlspecialchars($memorial['PlotNumber']); ?>
                                        <?php if (!empty($memorial['Religion'])): ?>
                                            <br>Religion: <?php echo htmlspecialchars($memorial['Religion']); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="memorial-age">Age: <?php echo htmlspecialchars($memorial['Age']); ?></div>
                                    <form action="https://memorialmap.duckdns.org/memorial.php" method="get">
                                        <input type="hidden" name="id"
                                            value="<?php echo htmlspecialchars($memorial['Memorial_MapID']); ?>" />
                                        <button type="submit" class="search-btn">View Memorial</button>
                                    </form>
                                </div>
                            </div>
                            <?php
                        endforeach;
                    else:
                        ?>
                        <!-- Fallback card if no memorials are found -->
                        <div class="memorial-card">
                            <img src="https://randomuser.me/api/portraits/lego/3.jpg" alt="No memorials"
                                class="memorial-img">
                            <div class="memorial-info">
                                <div class="memorial-name">No memorials found</div>
                                <div class="memorial-dates">Please check back later</div>
                                <div class="memorial-location">N/A</div>
                                <div class="memorial-age">N/A</div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="carousel-controls">
                    <button class="carousel-btn prev-btn" aria-label="Previous slide"><i
                            class="fas fa-chevron-left"></i></button>
                    <button class="carousel-btn next-btn" aria-label="Next slide"><i
                            class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </section>

        <section class="faq">
            <h3>Frequently Asked Questions</h3>
            <div class="faq-item">
                <div class="faq-question">
                    What is MemorialMap?
                    <i class="fas fa-plus"></i>
                </div>
                <div class="faq-answer">
                    MemorialMap is a service that helps users create and manage digital memorials for their loved ones.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    How do I search for a resting place?
                    <i class="fas fa-plus"></i>
                </div>
                <div class="faq-answer">
                    You can search for a resting place via the search boxes located at the top of the page.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    Is MemorialMap free to use?
                    <i class="fas fa-plus"></i>
                </div>
                <div class="faq-answer">
                    Yes. It is free to use. This is a Singapore Government service partnered with LifeSG.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    Can I create a memorial page for a loved one?
                    <i class="fas fa-plus"></i>
                </div>
                <div class="faq-answer">
                    Please submit an email to admin@sgcaskets.com for further enquiries.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    Can I report incorrect information on a post?
                    <i class="fas fa-plus"></i>
                </div>
                <div class="faq-answer">
                    Please submit an email to admin@sgcaskets.com for further enquiries.
                </div>
            </div>
        </section>

        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

        <section class="contact">
            <h3>Contact Us Now</h3>
            <form class="contact-form" method="POST" action="submit_feedback.php">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Name" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <textarea class="form-control" id="feedback" name="feedback" placeholder="Message" rows="4"
                        required></textarea>
                </div>
                <div class="g-recaptcha" data-sitekey="6LeCwQMrAAAAAJXDUke3bJ-9MdERoi86AAcPNlMF" style="margin-bottom: 30px; margin-top: 30px;"></div>
                <button type="submit" class="submit-btn">Submit</button>
            </form>
        </section>
    </main>
    <?php
    include "inc/footer.inc.php"
        ?>
    <script src="js/main.js"></script>
</body>

</html>