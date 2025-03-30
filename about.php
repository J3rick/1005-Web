<?php
    require_once __DIR__ . '/inc/cookie_public.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - MemorialMap</title>

    <?php
    include "inc/head.inc.php"
    ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/about.css">
    <link rel="stylesheet" href="css/flipbook.css">
</head>

<body>
    <!-- Hero Banner Section -->
    <section class="hero-banner">
        <h1>Redefining Farewell</h1>
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
                    <p>We envision a future where we empower and educate individuals and communities through open conversations about death, grieving and healing.</p>
                    <div class="page-corner"></div>
                    <div class="page-number">2</div>
                </div>
            </div>
            
            <!-- Page 3 - Mission -->
            <div class="flipbook-page">
                <div class="flipbook-page-content">
                    <h2>Our Mission</h2>
                    <img src="../assets/mission.jpg" alt="Our Mission">
                    <p>As the life celebrant, we strive to celebrate and commemorate how you lived and leave your life, by providing customised and meaningful funeral and pre-planning services for individuals and families; and to do so with integrity and compassion.</p>
                    <div class="page-corner"></div>
                    <div class="page-number">3</div>
                </div>
            </div>
            
            <!-- Page 4 - Core Values Introduction -->
            <div class="flipbook-page">
                <div class="flipbook-page-content">
                    <h2>Our 5 Core Values</h2>
                    <img src="../assets/values.jpg" alt="Our Core Values">
                    <p>MemorialMap is passionate in our commitment to provide our professional knowledge and guidance for the memorialisation of a unique life while facilitating moments that lead to the healing of grief.</p>
                    <div class="page-corner"></div>
                    <div class="page-number">4</div>
                </div>
            </div>
            
            <!-- Page 5 - Core Value 1 & 2 -->
            <div class="flipbook-page">
                <div class="flipbook-page-content">
                    <h2>Core Values</h2>
                    <h3>1. Compassion</h3>
                    <p>We serve with empathy and understanding during life's most difficult moments.</p>
                    <h3>2. Integrity</h3>
                    <p>We uphold the highest ethical standards in all our interactions.</p>
                    <div class="page-corner"></div>
                    <div class="page-number">5</div>
                </div>
            </div>
            
            <!-- Page 6 - Core Value 3, 4 & 5 -->
            <div class="flipbook-page">
                <div class="flipbook-page-content">
                    <h3>3. Respect</h3>
                    <p>We honor the dignity of each individual and their unique life story.</p>
                    <h3>4. Innovation</h3>
                    <p>We continuously seek better ways to serve and support our community.</p>
                    <h3>5. Excellence</h3>
                    <p>We strive for exceptional service in every aspect of our work.</p>
                    <div class="page-corner"></div>
                    <div class="page-number">6</div>
                </div>
            </div>
            
            <!-- Page 7 - Contact Information -->
            <div class="flipbook-page">
                <div class="flipbook-page-content">
                    <h2>Connect With Us</h2>
                    <p>We're here to support you through every step of your journey.</p>
                    <div class="contact-info">
                        <p><i class="fas fa-phone"></i> Phone: (123) 456-7890</p>
                        <p><i class="fas fa-envelope"></i> Email: info@memorialmap.com</p>
                        <p><i class="fas fa-map-marker-alt"></i> Address: 123 Memorial Lane, Singapore</p>
                    </div>
                    <div class="page-corner"></div>
                    <div class="page-number">7</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Vision and Mission Sections (Keeping these for users who prefer non-interactive content) -->
    <div class="about-sections">
        <div class="about-section">
            <h2>Our Vision</h2>
            <p>We envision a future where we empower and educate individuals and communities through open conversations about death, grieving and healing.</p>
        </div>
        
        <div class="about-section">
            <h2>Our Mission</h2>
            <p>As the life celebrant, we strive to celebrate and commemorate how you lived and leave your life, by providing customised and meaningful funeral and pre-planning services for individuals and families; and to do so with integrity and compassion.</p>
        </div>
    </div>
    
    <!-- Core Values Section -->
    <section class="core-values">
        <h2>Our 5 Core Values</h2>
        <p>MemorialMap is passionate in our commitment to provide our professional knowledge and guidance for the memorialisation of a unique life while facilitating moments that lead to the healing of grief. Our dedication to superior value and compassionate service defines and differentiates us.</p>
    </section>  

    <?php
    include "inc/footer.inc.php"
    ?>
    <script src="js/main.js"></script>
    <script src="js/flipbook.js"></script>
</body>

</html>