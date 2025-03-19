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
                <h3>Row 1, Column 1 (1/3)</h3>
                <p>This column takes up 1/3 of the row width.</p>
            </div>
            <div class="column-large">
                <h3>Row 1, Column 2 (2/3)</h3>
                <p>This column takes up 2/3 of the row width.</p>
            </div>
        </div>

        <!-- Row 2 -->
        <div class="row">
            <div class="column-large">
                <h3>Row 2, Column 1 (1/3)</h3>
                <p>This column takes up 1/3 of the row width.</p>
            </div>
            <div class="column-small">
                <h3>Row 2, Column 2 (2/3)</h3>
                <p>This column takes up 2/3 of the row width.</p>
            </div>
        </div>

        <!-- Row 3 -->
        <div class="row">
            <div class="column-small">
                <h3>Row 3, Column 1 (1/3)</h3>
                <p>This column takes up 1/3 of the row width.</p>
            </div>
            <div class="column-large">
                <h3>Row 3, Column 2 (2/3)</h3>
                <p>This column takes up 2/3 of the row width.</p>
            </div>
        </div>
    </section>

    <?php
    include "inc/footer.inc.php"
        ?>
    <script src="js/main.js"></script>
</body>

</html>