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
                    <label for="resting_type">Resting Type:</label>
                    <select id="resting_type" name="resting_type">
                        <option value="">All Types</option>
                        <option value="Burial">Burial</option>
                        <option value="Cremation">Cremation</option>
                        <option value="Mausoleum">Mausoleum</option>
                        <option value="Other">Other</option>
                    </select>
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
                Can I report incorrect information on a post? Test hello
                <i class="fas fa-plus"></i>
            </div>
            <div class="faq-answer">
                Please submit an email to admin@sgcaskets.com for further enquiries.
            </div>
        </div>
    </section>

    <section class="contact">
        <h3>Contact Us</h3>
        <form class="contact-form" method="POST" action="submit_feedback.php">
            <div class="form-group">
                <input type="text" name="name" placeholder="Name" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <textarea name="message" placeholder="Message" required></textarea>
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