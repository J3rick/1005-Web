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
    <section class="hero">
        <div class="hero-content">
            <h2>Find your loved ones today...</h2>
            <form class="search-form">
                <input type="text" placeholder="Name">
                <input type="text" placeholder="Place">
                <select>
                    <option value="">Religion</option>
                    <option value="christianity">Christianity</option>
                    <option value="islam">Islam</option>
                    <option value="judaism">Judaism</option>
                    <option value="hinduism">Hinduism</option>
                    <option value="buddhism">Buddhism</option>
                    <option value="other">Other</option>
                </select>
                <select>
                    <option value="">Cemetery/Grave</option>
                    <option value="cemetery1">Cemetery 1</option>
                    <option value="cemetery2">Cemetery 2</option>
                    <option value="cemetery3">Cemetery 3</option>
                </select>
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </section>

    <section class="memorials">
        <div class="section-header">
            <h3>Latest memorials</h3>
            <a href="#">Explore more »</a>
        </div>
        <div class="memorial-cards">
            <div class="memorial-card">
                <img src="" alt="Image1" class="memorial-img">
                <div class="memorial-info">
                    <div class="memorial-name">Name 1</div>
                    <div class="memorial-dates">07/07/1950 - 10/09/2024</div>
                    <div class="memorial-location">Location</div>
                    <div class="memorial-age">Age: 80</div>
                </div>
            </div>
            <div class="memorial-card">
                <img src="" alt="Image2" class="memorial-img">
                <div class="memorial-info">
                    <div class="memorial-name">Name 2</div>
                    <div class="memorial-dates">01/01/1944 - 15/09/2024</div>
                    <div class="memorial-location">Location</div>
                    <div class="memorial-age">Age: 87</div>
                </div>
            </div>
            <div class="memorial-card">
                <img src="" alt="Image3" class="memorial-img">
                <div class="memorial-info">
                    <div class="memorial-name">Name 3</div>
                    <div class="memorial-dates">01/01/1950 - 10/09/2024</div>
                    <div class="memorial-location">Location</div>
                    <div class="memorial-age">Age: 80</div>
                </div>
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

    <section class="contact">
        <h3>Contact Us</h3>
        <form class="contact-form">
            <div class="form-group">
                <input type="text" placeholder="Name" required>
            </div>
            <div class="form-group">
                <input type="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <textarea placeholder="Message" required></textarea>
            </div>
            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </section>

    <?php
      include "inc/footer.inc.php"
    ?>
<script src="js/main.js"></script>
</body>
</html>
