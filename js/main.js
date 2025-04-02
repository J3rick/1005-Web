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
            
            // Calculate container width to fit exactly one card on mobile
            const containerWidth = carouselContainer.clientWidth;
            
            // Apply styles to cards
            cards.forEach(card => {
                // Full width for mobile (minus padding/margins)
                if (cardsPerView === 1) {
                    card.style.flex = '0 0 100%';
                    card.style.minWidth = '100%';
                    card.style.marginRight = '0';
                } else if (cardsPerView === 2) {
                    card.style.flex = '0 0 calc(50% - 10px)';
                    card.style.minWidth = 'calc(50% - 10px)';
                } else {
                    card.style.flex = '0 0 calc(33.333% - 20px)';
                    card.style.minWidth = 'calc(33.333% - 20px)';
                }
            });
            
            // Recalculate card width including margins
            const firstCard = cards[0];
            const cardStyle = window.getComputedStyle(firstCard);
            const cardMargin = parseFloat(cardStyle.marginRight) + parseFloat(cardStyle.marginLeft);
            
            // For single card view, make card width equal to container
            if (cardsPerView === 1) {
                cardWidth = containerWidth;
            } else {
                // For multi-card views, include the gap between cards
                const cardRect = firstCard.getBoundingClientRect();
                cardWidth = cardRect.width + cardMargin;
            }
            
            // Reset index and update display
            if (currentIndex > cards.length - cardsPerView) {
                currentIndex = Math.max(0, cards.length - cardsPerView);
            }
            updateCarousel();
        }
        
        // Function to update carousel position
        function updateCarousel() {
            // Fix: Calculate the true maximum index to show all cards
            const maxIndex = Math.max(0, cards.length - cardsPerView);
            
            // Make sure we don't scroll beyond the available cards
            if (currentIndex < 0) currentIndex = 0;
            if (currentIndex > maxIndex) currentIndex = maxIndex;
            
            // Update buttons visibility
            prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
            prevBtn.disabled = currentIndex === 0;
            nextBtn.style.opacity = currentIndex >= maxIndex ? '0.5' : '1';
            nextBtn.disabled = currentIndex >= maxIndex;
            
            // Move the carousel
            memorialCards.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
        }
        
        // Button event listeners
        prevBtn.addEventListener('click', function() {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        });
        
        nextBtn.addEventListener('click', function() {
            // Fix: Only advance if we haven't reached the maximum index
            const maxIndex = Math.max(0, cards.length - cardsPerView);
            if (currentIndex < maxIndex) {
                currentIndex++;
                updateCarousel();
            }
        });
        
        // Touch event listeners for mobile swipe
        memorialCards.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
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
        window.addEventListener('resize', updateCarouselLayout);
        
        // Auto-play functionality (optional)
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
        carouselContainer.addEventListener('touchstart', stopAutoplay);
        
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