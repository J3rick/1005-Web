// Add this to your main.js file

// main.js - Add this code to your existing main.js file

document.addEventListener('DOMContentLoaded', function() {
    // Memorial Cards Carousel
    const memorialCardsContainer = document.querySelector('.memorial-cards');
    const cards = document.querySelectorAll('.memorial-card');
    
    // Create navigation arrows
    const createCarouselControls = () => {
        // Create container for the controls
        const controlsContainer = document.createElement('div');
        controlsContainer.className = 'carousel-controls';
        
        // Create left arrow
        const leftArrow = document.createElement('button');
        leftArrow.className = 'carousel-control carousel-prev';
        leftArrow.innerHTML = '<i class="fas fa-chevron-left"></i>';
        leftArrow.setAttribute('aria-label', 'Previous memorial');
        
        // Create right arrow
        const rightArrow = document.createElement('button');
        rightArrow.className = 'carousel-control carousel-next';
        rightArrow.innerHTML = '<i class="fas fa-chevron-right"></i>';
        rightArrow.setAttribute('aria-label', 'Next memorial');
        
        // Add arrows to container
        controlsContainer.appendChild(leftArrow);
        controlsContainer.appendChild(rightArrow);
        
        // Insert controls after the memorial-cards container
        memorialCardsContainer.parentNode.insertBefore(controlsContainer, memorialCardsContainer.nextSibling);
        
        return { leftArrow, rightArrow };
    };
    
    // Only create carousel if there are multiple cards
    if (cards.length > 1) {
        // Set up carousel
        let currentIndex = 0;
        memorialCardsContainer.classList.add('carousel-container');
        
        // Hide all cards except the first one
        cards.forEach((card, index) => {
            card.classList.add('carousel-item');
            if (index !== currentIndex) {
                card.style.display = 'none';
            }
        });
        
        // Create and setup navigation arrows
        const { leftArrow, rightArrow } = createCarouselControls();
        
        // Function to show card at specific index
        const showCard = (index) => {
            cards.forEach(card => card.style.display = 'none');
            cards[index].style.display = 'block';
            
            // Add animation class
            cards[index].classList.add('fade-in');
            setTimeout(() => {
                cards[index].classList.remove('fade-in');
            }, 500);
        };
        
        // Previous button click handler
        leftArrow.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + cards.length) % cards.length;
            showCard(currentIndex);
        });
        
        // Next button click handler
        rightArrow.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % cards.length;
            showCard(currentIndex);
        });
        
        // Optional: Auto-rotate the carousel
        let carouselInterval = setInterval(() => {
            currentIndex = (currentIndex + 1) % cards.length;
            showCard(currentIndex);
        }, 5000); // Change slide every 5 seconds
        
        // Pause auto-rotation when hovering over carousel
        memorialCardsContainer.addEventListener('mouseenter', () => {
            clearInterval(carouselInterval);
        });
        
        memorialCardsContainer.addEventListener('mouseleave', () => {
            carouselInterval = setInterval(() => {
                currentIndex = (currentIndex + 1) % cards.length;
                showCard(currentIndex);
            }, 5000);
        });
    }
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