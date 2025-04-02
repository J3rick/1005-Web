<?php
require_once __DIR__ . '/inc/cookie_public.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - MemorialMap</title>



    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/about.css">
    <link rel="stylesheet" href="css/flipbook.css">
</head>

<body>
    <?php
    include "inc/head.inc.php"
        ?>
    <!-- Hero Banner Section -->
    <section class="hero-banner">
        <div class="slideshow-container">
            <div class="slideshow-images">
                <img src="/assets/cemetery_1.jpg" alt="Memorial Image 1" class="slideshow-image active">
                <img src="/assets/cemetery_2.jpg" alt="Memorial Image 2" class="slideshow-image">
                <img src="/assets/cemetery_3.jpg" alt="Memorial Image 3" class="slideshow-image">
            </div>
        </div>
        <div class="overlay"></div>
        <h1 class="hero-title">Redefining Farewell</h1>
    </section>

    <!-- Flipbook Section -->
    <div class="flipbook-container">
        <div id="flipbook">
            <!-- Page 1 - Cover -->
            <div class="flipbook-page">
                <div class="flipbook-page-content">
                    <h2>About MemorialMap</h2>
                    <img src="/assets/Logo.jpg" alt="MemorialMap Logo">
                    <p>Drag this page to explore our story</p>
                    <div class="page-corner"></div>
                    <div class="page-number">1</div>
                </div>
            </div>

            <!-- Page 2 - Vision -->
            <div class="flipbook-page">
                <div class="flipbook-page-content">
                    <h2>Our Vision</h2>
                    <img src="/assets/livestreaming.jpg" alt="Our Vision">
                    <p>We envision a world where no one is forgotten, where every life is honored and every grave is
                        easy to find, bringing comfort, connection and continuity across generations.</p>
                    <div class="page-corner"></div>
                    <div class="page-number">2</div>
                </div>
            </div>

            <!-- Page 3 - Mission -->
            <div class="flipbook-page">
                <div class="flipbook-page-content">
                    <h2>Our Mission</h2>
                    <img src="/assets/memorywall.jpg" alt="Our Mission">
                    <p>To help individuals and families find the graves of their loved ones through a simple, respectful
                        and accurate digital platform that makes remembrance accessible to all.</p>
                    <div class="page-corner"></div>
                    <div class="page-number">3</div>
                </div>
            </div>

            <!-- Page 4 - Contact Information -->
            <div class="flipbook-page">
                <div class="flipbook-page-content">
                    <h2>Connect With Us</h2>
                    <img src="/assets/Logo.jpg" alt="MemorialMap Logo">
                    <!-- <p>We're here to support you through every step of your journey.</p> -->
                    <div class="contact-info">
                        <p><i class="fas fa-phone"></i> Phone: (65) 9876 5432</p>
                        <p><i class="fas fa-envelope"></i> Email: admin@sgcaskets.com</p>
                        <p><i class="fas fa-map-marker-alt"></i> Address: 123 Memorial Lane, Singapore</p>
                    </div>
                    <div class="page-corner"></div>
                    <div class="page-number">4</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vision and Mission Sections -->
    <div class="about-sections">
        <div class="about-section">
            <h2>Our Vision</h2>
            <p>To be the world's most trusted and comprehensive digital guide for honouring and remembering loved ones,
                making it easy for families, friends and communities to
                locate, visit and celebrate the lives of those who came before us.
            </p>
        </div>

        <div class="about-section">
            <h2>Our Mission</h2>
            <p>We aim to bring clarity and connection to moments of remembrance. Memorial Map helps users easily find
                the gravesites of their loved ones, offering location tools,
                memorial information and a respectful space for a digital tribute. Whether near or far, we make it
                possible to never lose the path to someone's memory.
            </p>
        </div>
    </div>

    <!-- Core Values Section -->
    <section class="core-values">
        <h2>Our Core Values</h2>
        <p>At Memorial Map, our work is rooted in respect for the lives remembered, the families searching and stories
            preserved. We believe in providing accurate, reliable
            information because navigating grief is hard enough without confusion or errors. Compassion drives every
            design decision, ensuring our platform is not only useful
            but also sensitive to the emotional weight of loss. We are commited to accessibility, making it easy for
            anyone, anywhere to locate and honour their loved ones. And
            finally we embrace innovation, using technology not just to find gravesites but to preserve legacies and
            keep memories alive for generations to come.
        </p>
    </section>

    <?php
    include "inc/footer.inc.php"
        ?>
    <script src="js/main.js"></script>
    <script src="js/flipbook.js"></script>
    <script src="js/slideshow.js"></script>
    </main>
</body>

</html>