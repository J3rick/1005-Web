document.addEventListener('DOMContentLoaded', function() {
    // Get all slideshow images
    const slideshowImages = document.querySelectorAll('.slideshow-image');
    
    if (slideshowImages.length === 0) return; // Exit if no images found
    
    let currentImageIndex = 0;
    const slideshowDelay = 5000; // 5 seconds between slides
    
    // Function to show the next image
    function showNextImage() {
        // Remove active class from current image
        slideshowImages[currentImageIndex].classList.remove('active');
        
        // Update index to next image (loop back to first image if at the end)
        currentImageIndex = (currentImageIndex + 1) % slideshowImages.length;
        
        // Add active class to next image
        slideshowImages[currentImageIndex].classList.add('active');
    }
    
    // Start the slideshow
    setInterval(showNextImage, slideshowDelay);
});