// admin.js - JavaScript functions for admin dashboard

// Function to toggle the sidebar
function toggleSidebar() {
    const sideMenu = document.getElementById('side-menu');
    sideMenu.classList.toggle('active');

    // Adjust the body content margin when the sidebar is toggled
    const content = document.querySelector('.content');
    if (sideMenu.classList.contains('active')) {
        content.style.marginLeft = '220px'; /* Sidebar width + padding */
    } else {
        content.style.marginLeft = '0';
    }
}

