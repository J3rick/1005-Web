//Add javascript here
document.addEventListener('DOMContentLoaded', function() {
    // Select all FAQ questions
    const faqQuestions = document.querySelectorAll('.faq-question');
    
    // Add click event listener to each question
    faqQuestions.forEach(question => {
        question.addEventListener('click', () => {
            // Get the parent faq-item
            const parent = question.parentElement;
            
            // Toggle active class
            parent.classList.toggle('active');
            
            // Optional: Close other items when one is opened
            faqQuestions.forEach(otherQuestion => {
                const otherParent = otherQuestion.parentElement;
                if (otherParent !== parent) {
                    otherParent.classList.remove('active');
                }
            });
        });
    });
});