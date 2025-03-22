document.addEventListener('DOMContentLoaded', function() {
    // Existing FAQ functionality
    
    // Mobile menu functionality
    
    // Carousel functionality
    const initCarousel = () => {
        const carouselContainer = document.querySelector('.carousel-container');
        if (!carouselContainer) return; // Exit if no carousel container
        
        const memorialCards = document.querySelector('.memorial-cards');
        const cards = document.querySelectorAll('.memorial-card');
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        
        if (!memorialCards || !cards.length || !prevBtn || !nextBtn) return;
        
        let currentIndex = 0;
        const totalCards = cards.length;
        const cardsToShow = window.innerWidth <= 768 ? 1 : 3;
        
        // Change this value to control how many cards to move per click
        const cardsToMove = 1;
        
        const maxIndex = totalCards - cardsToShow;
        
        // Calculate the width of each card including gap
        const cardWidth = cards[0].offsetWidth + 30; // 30px is the gap
        
        // Function to update carousel position
        const updateCarousel = () => {
            const translateX = -currentIndex * cardWidth;
            memorialCards.style.transform = `translateX(${translateX}px)`;
            
            // Update button states
            prevBtn.disabled = currentIndex === 0;
            nextBtn.disabled = currentIndex >= maxIndex;
        };
        
        // Add event listeners to buttons
        prevBtn.addEventListener('click', () => {
            // Always decrease by cardsToMove as long as we're not at the start
            if (currentIndex > 0) {
                currentIndex = Math.max(0, currentIndex - cardsToMove);
                updateCarousel();
                
                // Debug info
                console.log("Previous clicked. Current index:", currentIndex);
            }
        });
        
        nextBtn.addEventListener('click', () => {
            // Always increase by cardsToMove as long as we're not at the end
            if (currentIndex < maxIndex) {
                currentIndex = Math.min(maxIndex, currentIndex + cardsToMove);
                updateCarousel();
                
                // Debug info
                console.log("Next clicked. Current index:", currentIndex);
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', () => {
            const newCardsToShow = window.innerWidth <= 768 ? 1 : 3;
            const newMaxIndex = totalCards - newCardsToShow;
            
            // Adjust currentIndex if necessary
            if (currentIndex > newMaxIndex) {
                currentIndex = newMaxIndex;
            }
            
            // Recalculate card width
            const newCardWidth = cards[0].offsetWidth + 30;
            
            // Update carousel
            memorialCards.style.transform = `translateX(${-currentIndex * newCardWidth}px)`;
            
            console.log("Resize. Cards to show:", newCardsToShow, "Max index:", newMaxIndex, "Current index:", currentIndex);
        });
        
        // Initialize carousel
        updateCarousel();
        console.log("Carousel initialized. Total cards:", totalCards, "Cards to show:", cardsToShow, "Max index:", maxIndex);
    };
    
    // Initialize carousel
    initCarousel();
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