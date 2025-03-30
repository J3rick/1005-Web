<?php
    require_once __DIR__ . '/inc/cookie_public.php';
?>


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
            <h3>Our Services</h3>
        </div>
        <!-- Row 1 -->
        <div class="row">
            <div class="column-small">
            <div class="memorial-card">
                <img src="assets/cemetery_1.jpg" alt="test" style="width: 500px; height: auto;">
                </div>
            </div>
            <div class="column-large">
                <div class="memorial-card">
                    <h3>Ongoing Maintenance and Care</h3>
                    <p>Professional maintenance services to keep memorial sites clean,
                        well-kept, and dignified year-round. This includes regular cleaning of headstones and monuments,
                        debris removal, lawn care, flower bed maintenance, and seasonal decorations.
                        The service ensures that families can have peace of mind knowing their loved ones'
                        final resting places are being respectfully maintained regardless of distance or personal
                        time constraints.</p>
                </div>
            </div>
        </div>

        <!-- Row 2 -->
        <div class="row">
            <div class="column-large">
                <div class="memorial-card">
                    <h3>Restoration and Preservation</h3>
                    <p>Specialized restoration services for aging or damaged memorials,
                        including professional cleaning, re-engraving of weathered inscriptions,
                        repair of cracks or chips, and preservation treatments to protect against future deterioration.
                        Trained technicians use appropriate conservation methods based on the memorial's material
                        (granite, marble, bronze, etc.) to restore the monument's appearance while preserving
                        its historical and sentimental integrity.</p>
                </div>
            </div>
            <div class="column-small">
            <div class="memorial-card">
                <img src="assets/cemetery_2.jpg" alt="test" style="width: 500px; height: auto;">
                </div>
            </div>
        </div>

        <!-- Row 3 -->
        <div class="row">
            <div class="column-small">
            <div class="memorial-card">
                <img src="assets/cemetery_3.jpg" alt="test" style="width: 500px; height: auto;">
                </div>
            </div>
            <div class="column-large">
                <div class="memorial-card">
                    <h3>Custom Memorial Design and Installation</h3>
                    <p>Comprehensive design and installation services for new memorials,
                        working closely with families to create personalized tributes.
                        This service guides customers through selecting appropriate materials,
                        dimensions, inscriptions, and decorative elements that best honor their loved one
                        while adhering to cemetery regulations. The company handles all aspects from initial
                        concept to final installation, including foundation preparation, delivery,
                        and proper placement at the cemetery site.</p>
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