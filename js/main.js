    // Existing FAQ functionality
    
    // Mobile menu functionality
    
    // Carousel functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Get carousel elements
        const carouselContainer = document.querySelector('.carousel-container');
        const memorialCards = document.querySelector('.memorial-cards');
        const cards = Array.from(document.querySelectorAll('.memorial-card'));
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        
        // Initialize variables
        let currentIndex = 0;
        let cardWidth = 0;
        let cardsPerView = 3;
        let touchStartX = 0;
        let touchEndX = 0;
        
        // Function to update carousel display based on screen size
        function updateCarouselLayout() {
            // Reset any inline styles that might affect calculation
            memorialCards.style.width = '';
            
            // Determine cards per view based on screen width
            if (window.innerWidth < 768) {
                cardsPerView = 1;
            } else if (window.innerWidth < 992) {
                cardsPerView = 2;
            } else {
                cardsPerView = 3;
            }
            
            // Calculate container width
            const containerWidth = carouselContainer.clientWidth;
            
            // Set width for the entire card container to ensure proper flow
            memorialCards.style.width = `${cards.length * containerWidth / cardsPerView}px`;
            
            // Apply styles to cards with improved margin handling
            cards.forEach(card => {
                if (cardsPerView === 1) {
                    card.style.flex = '0 0 100%';
                    card.style.width = `${containerWidth}px`;
                    card.style.marginRight = '0';
                } else if (cardsPerView === 2) {
                    // For 2 cards, ensure each takes exactly 50% minus a fixed margin
                    card.style.flex = '0 0 calc(50% - 10px)';
                    card.style.width = `${(containerWidth / 2) - 10}px`;
                    card.style.marginRight = '10px';
                } else {
                    // For 3 cards, ensure each takes exactly 33.333% minus a fixed margin
                    card.style.flex = '0 0 calc(33.333% - 14px)';
                    card.style.width = `${(containerWidth / 3) - 14}px`;
                    card.style.marginRight = '10px';
                }
            });
            
            // Calculate the exact card width including margins for scrolling
            const firstCard = cards[0];
            const cardRect = firstCard.getBoundingClientRect();
            const cardStyle = window.getComputedStyle(firstCard);
            const marginRight = parseFloat(cardStyle.marginRight);
            
            // Set card width including margin for scroll calculations
            cardWidth = cardRect.width + marginRight;
            
            // Ensure we don't exceed the valid index range
            const maxIndex = Math.max(0, cards.length - cardsPerView);
            if (currentIndex > maxIndex) {
                currentIndex = maxIndex;
            }
            
            updateCarousel();
        }
        
        // Function to update carousel position
        function updateCarousel() {
            // Calculate the maximum valid index
            const maxIndex = Math.max(0, cards.length - cardsPerView);
            
            // Ensure current index is within valid range
            if (currentIndex < 0) currentIndex = 0;
            if (currentIndex > maxIndex) currentIndex = maxIndex;
            
            // Update button states
            prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
            prevBtn.disabled = currentIndex === 0;
            nextBtn.style.opacity = currentIndex >= maxIndex ? '0.5' : '1';
            nextBtn.disabled = currentIndex >= maxIndex;
            
            // Calculate precise position to ensure cards are fully visible
            const scrollPosition = currentIndex * cardWidth;
            memorialCards.style.transform = `translateX(-${scrollPosition}px)`;
        }
        
        // Button event listeners
        prevBtn.addEventListener('click', function() {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        });
        
        nextBtn.addEventListener('click', function() {
            const maxIndex = Math.max(0, cards.length - cardsPerView);
            if (currentIndex < maxIndex) {
                currentIndex++;
                updateCarousel();
            }
        });
        
        // Touch event listeners for mobile swipe
        memorialCards.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
            stopAutoplay(); // Stop autoplay on touch
        });
        
        memorialCards.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
        
        // Handle swipe direction
        function handleSwipe() {
            const swipeThreshold = 50; // Minimum distance for a swipe
            const maxIndex = Math.max(0, cards.length - cardsPerView);
            
            if (touchEndX < touchStartX - swipeThreshold) {
                // Swipe left (next)
                if (currentIndex < maxIndex) {
                    currentIndex++;
                    updateCarousel();
                }
            } else if (touchEndX > touchStartX + swipeThreshold) {
                // Swipe right (previous)
                if (currentIndex > 0) {
                    currentIndex--;
                    updateCarousel();
                }
            }
        }
        
        // Initialize carousel and update on window resize
        updateCarouselLayout();
        
        // Add debounced resize handler to prevent performance issues
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(updateCarouselLayout, 250);
        });
        
        // Auto-play functionality
        let autoplayInterval;
        
        function startAutoplay() {
            autoplayInterval = setInterval(() => {
                const maxIndex = Math.max(0, cards.length - cardsPerView);
                if (currentIndex < maxIndex) {
                    currentIndex++;
                } else {
                    currentIndex = 0;
                }
                updateCarousel();
            }, 5000); // Change slide every 5 seconds
        }
        
        function stopAutoplay() {
            clearInterval(autoplayInterval);
        }
        
        // Start autoplay
        startAutoplay();
        
        // Pause autoplay on hover or touch
        carouselContainer.addEventListener('mouseenter', stopAutoplay);
        
        // Resume autoplay when mouse leaves
        carouselContainer.addEventListener('mouseleave', startAutoplay);
    });

document.addEventListener('DOMContentLoaded', function() {
    // Existing FAQ functionality
    const faqQuestions = document.querySelectorAll('.faq-question');
    
    faqQuestions.forEach(question => {
        question.addEventListener('click', () => {
            const parent = question.parentElement;
            parent.classList.toggle('active');
            
            faqQuestions.forEach(otherQuestion => {
                const otherParent = otherQuestion.parentElement;
                if (otherParent !== parent) {
                    otherParent.classList.remove('active');
                }
            });
        });
    });
    
    // Mobile menu functionality
    const createMobileMenu = () => {
        // Create hamburger menu button
        const hamburger = document.createElement('div');
        hamburger.classList.add('hamburger-menu');
        hamburger.innerHTML = '<div class="bar"></div><div class="bar"></div><div class="bar"></div>';
        
        // Add hamburger to header
        const header = document.querySelector('header');
        const nav = document.querySelector('nav');
        
        header.insertBefore(hamburger, nav);
        
        // Add event listener to hamburger
        hamburger.addEventListener('click', () => {
            nav.classList.toggle('active');
            hamburger.classList.toggle('active');
        });
    };
    
    // Only create mobile menu if screen width is below 768px
    if (window.innerWidth < 768) {
        createMobileMenu();
    }
    
    // Handle resize events
    window.addEventListener('resize', () => {
        const hamburger = document.querySelector('.hamburger-menu');
        
        if (window.innerWidth < 768 && !hamburger) {
            createMobileMenu();
        } else if (window.innerWidth >= 768 && hamburger) {
            hamburger.remove();
            document.querySelector('nav').classList.remove('active');
        }
    });
});