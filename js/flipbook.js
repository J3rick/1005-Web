// flipbook.js
document.addEventListener('DOMContentLoaded', function() {
    // Flipbook configuration
    const flipbook = document.getElementById('flipbook');
    let currentPage = 0;
    let startX = 0;
    let isDragging = false;
    let pageWidth = flipbook.offsetWidth / 2;
    const totalPages = document.querySelectorAll('.flipbook-page').length;
    
    // Initialize the flipbook
    function initFlipbook() {
        const pages = document.querySelectorAll('.flipbook-page');
        
        // Set initial page positions
        pages.forEach((page, index) => {
            page.style.zIndex = totalPages - index;
            if (index > 0) {
                page.style.transform = 'rotateY(0deg)';
                page.classList.add('hidden-page');
            }
        });
        
        // Set up event listeners
        flipbook.addEventListener('mousedown', startDrag);
        flipbook.addEventListener('mousemove', drag);
        flipbook.addEventListener('mouseup', endDrag);
        flipbook.addEventListener('mouseleave', endDrag);
        
        // Prevent default behavior on mousedown to avoid text selection
        flipbook.addEventListener('mousedown', function(e) {
            e.preventDefault();
            return false;
        });
        
        // Touch events
        flipbook.addEventListener('touchstart', handleTouchStart);
        flipbook.addEventListener('touchmove', handleTouchMove);
        flipbook.addEventListener('touchend', handleTouchEnd);
        
        // Prevent default behavior on touchstart to avoid text selection
        flipbook.addEventListener('touchstart', function(e) {
            e.preventDefault();
            return false;
        }, { passive: false });
        
        // Window resize handler
        window.addEventListener('resize', function() {
            pageWidth = flipbook.offsetWidth / 2;
        });
    }
    
    // Start drag
    function startDrag(e) {
        if (e.button === 0) { // Left mouse button
            isDragging = true;
            startX = e.clientX;
            flipbook.classList.add('dragging');
            document.body.classList.add('dragging-active'); // Add class to body
            
            // Disable text selection globally during drag
            document.onselectstart = function() { return false; };
        }
    }
    
    // Handle dragging
    function drag(e) {
        if (!isDragging) return;
        
        e.preventDefault(); // Prevent default behavior during drag
        
        const currentX = e.clientX;
        const diffX = currentX - startX;
        const pages = document.querySelectorAll('.flipbook-page');
        const currentPageElem = pages[currentPage];
        
        if (currentPageElem) {
            // Calculate rotation based on drag distance
            let rotation = 0;
            
            if (diffX < 0 && currentPage < totalPages - 1) {
                // Dragging left (turning forward)
                rotation = Math.max(-180, Math.min(0, (diffX / pageWidth) * 180));
                currentPageElem.style.transform = `rotateY(${rotation}deg)`;
                
                // Show the next page as we're turning
                if (currentPage < totalPages - 1) {
                    pages[currentPage + 1].classList.remove('hidden-page');
                }
            } else if (diffX > 0 && currentPage > 0) {
                // Dragging right (turning backward)
                pages[currentPage - 1].style.transform = `rotateY(${180 + (diffX / pageWidth) * 180}deg)`;
            }
        }
    }
    
    // End drag
    function endDrag(e) {
        if (!isDragging) return;
        
        isDragging = false;
        flipbook.classList.remove('dragging');
        document.body.classList.remove('dragging-active'); // Remove class from body
        
        // Re-enable text selection
        document.onselectstart = null;
        
        const currentX = e.clientX || (e.changedTouches && e.changedTouches[0].clientX);
        const diffX = currentX - startX;
        const pages = document.querySelectorAll('.flipbook-page');
        
        // Determine if we should complete the page turn
        if (diffX < -50 && currentPage < totalPages - 1) {
            // Turn forward
            pages[currentPage].style.transform = 'rotateY(-180deg)';
            pages[currentPage].classList.add('hidden-page');
            currentPage++;
        } else if (diffX > 50 && currentPage > 0) {
            // Turn backward
            pages[currentPage - 1].style.transform = 'rotateY(0deg)';
            pages[currentPage - 1].classList.remove('hidden-page');
            currentPage--;
        } else {
            // Reset to original position
            if (diffX < 0 && currentPage < totalPages - 1) {
                pages[currentPage].style.transform = 'rotateY(0deg)';
                if (currentPage < totalPages - 1) {
                    pages[currentPage + 1].classList.add('hidden-page');
                }
            } else if (diffX > 0 && currentPage > 0) {
                pages[currentPage - 1].style.transform = 'rotateY(180deg)';
            }
        }
    }
    
    // Touch event handlers
    function handleTouchStart(e) {
        startX = e.touches[0].clientX;
        isDragging = true;
        flipbook.classList.add('dragging');
        document.body.classList.add('dragging-active'); // Add class to body
        
        // Disable text selection globally during drag
        document.onselectstart = function() { return false; };
    }
    
    function handleTouchMove(e) {
        if (!isDragging) return;
        e.preventDefault();
        
        const currentX = e.touches[0].clientX;
        const diffX = currentX - startX;
        const pages = document.querySelectorAll('.flipbook-page');
        const currentPageElem = pages[currentPage];
        
        if (currentPageElem) {
            // Calculate rotation based on drag distance
            let rotation = 0;
            
            if (diffX < 0 && currentPage < totalPages - 1) {
                // Dragging left (turning forward)
                rotation = Math.max(-180, Math.min(0, (diffX / pageWidth) * 180));
                currentPageElem.style.transform = `rotateY(${rotation}deg)`;
                
                // Show the next page as we're turning
                if (currentPage < totalPages - 1) {
                    pages[currentPage + 1].classList.remove('hidden-page');
                }
            } else if (diffX > 0 && currentPage > 0) {
                // Dragging right (turning backward)
                pages[currentPage - 1].style.transform = `rotateY(${180 + (diffX / pageWidth) * 180}deg)`;
            }
        }
    }
    
    function handleTouchEnd(e) {
        if (!isDragging) return;
        
        isDragging = false;
        flipbook.classList.remove('dragging');
        document.body.classList.remove('dragging-active'); // Remove class from body
        
        // Re-enable text selection
        document.onselectstart = null;
        
        const currentX = e.changedTouches[0].clientX;
        const diffX = currentX - startX;
        const pages = document.querySelectorAll('.flipbook-page');
        
        // Determine if we should complete the page turn
        if (diffX < -50 && currentPage < totalPages - 1) {
            // Turn forward
            pages[currentPage].style.transform = 'rotateY(-180deg)';
            pages[currentPage].classList.add('hidden-page');
            currentPage++;
        } else if (diffX > 50 && currentPage > 0) {
            // Turn backward
            pages[currentPage - 1].style.transform = 'rotateY(0deg)';
            pages[currentPage - 1].classList.remove('hidden-page');
            currentPage--;
        } else {
            // Reset to original position
            if (diffX < 0 && currentPage < totalPages - 1) {
                pages[currentPage].style.transform = 'rotateY(0deg)';
                if (currentPage < totalPages - 1) {
                    pages[currentPage + 1].classList.add('hidden-page');
                }
            } else if (diffX > 0 && currentPage > 0) {
                pages[currentPage - 1].style.transform = 'rotateY(180deg)';
            }
        }
    }
    
    // Initialize the flipbook
    initFlipbook();
});