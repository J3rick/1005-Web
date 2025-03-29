<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MemorialMap</title>

    <?php
    include "inc/head.inc.php"
        ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
    <section class="services">
        <div class="section-header">
            <h3>Important Announcements</h3>
        </div>
        <!-- Row 1 -->
        <div class="row">
            <div class="column-small">
            <div class="memorial-card">
                <img src="assets/chapel.jpg" alt="test" style="width: 500px; height: auto;">
                </div>
            </div>
            <div class="column-large">
                <div class="memorial-card">
                    <h3>Memorial Service Schedule</h3>
                    <p>Join us for the memorial service on <strong>April 15, 2025</strong> at <strong>St. Peter's Chapel</strong>. The service will begin promptly at <strong>10:00 AM</strong>.</p>
                </div>
            </div>
        </div>

        <!-- Row 2 -->
        <div class="row">
            <div class="column-large">
                <div class="memorial-card">
                    <h3>Live Streaming Available</h3>
                    <p>For those unable to attend in person, a live stream will be available. Visit the "Live" section on the memorial website 30 minutes before the service begins.</p>
                </div>
            </div>
            <div class="column-small">
            <div class="memorial-card">
                <img src="assets/livestreaming.jpg" alt="test" style="width: 500px; height: auto;">
                </div>
            </div>
        </div>

        <!-- Row 3 -->
        <div class="row">
            <div class="column-small">
            <div class="memorial-card">
                <img src="assets/memorywall.jpg" alt="test" style="width: 500px; height: auto;">
                </div>
            </div>
            <div class="column-large">
                <div class="memorial-card">
                    <h3>Memory Wall</h3>
                    <p>Share your heartfelt messages and stories on our  Memory Wall to celebrate and cherish memories together.</p>
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