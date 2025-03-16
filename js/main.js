// Add this to your main.js file

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