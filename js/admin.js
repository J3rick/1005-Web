function toggleSidebar() {
    const sideMenu = document.getElementById('side-menu');
    sideMenu.classList.toggle('active');
    document.body.classList.toggle('sidebar-open');

    // Get all content containers (prioritize common selectors)
    const contentContainers = [
        document.querySelector('.content'),
        document.querySelector('main'),
        document.querySelector('.main-content'),
        document.body
    ].filter(el => el); // Remove nulls

    // Apply to all found containers
    contentContainers.forEach(container => {
        if (sideMenu.classList.contains('active')) {
            container.style.marginLeft = '90px';
            container.style.width = 'calc(100% - 90px)';
            container.style.overflowX = 'auto';
            container.style.transition = 'all 0.3s ease';
        } else {
            container.style.marginLeft = '0';
            container.style.width = '100%';
            container.style.overflowX = '';
        }
    });

    // Special handling for tables
    document.querySelectorAll('table').forEach(table => {
        table.style.minWidth = sideMenu.classList.contains('active') ? '100%' : '';
    });
}

// Initialize with better event handling
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggle-sidebar');
    if (toggleButton) {
        toggleButton.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleSidebar();
        });
    }

    // Close sidebar when clicking overlay
    const overlay = document.querySelector('.sidebar-overlay');
    if (overlay) {
        overlay.addEventListener('click', toggleSidebar);
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768 && 
            !e.target.closest('.side-menu') && 
            !e.target.closest('#toggle-sidebar')) {
            document.getElementById('side-menu').classList.remove('active');
            document.body.classList.remove('sidebar-open');
        }
    });
});