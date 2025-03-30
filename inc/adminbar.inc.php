<div class="side-menu" id="side-menu">
    <button id="toggle-sidebar" onclick="toggleSidebar()">☰</button>
    <a href= "admin.php"><h2>Menu</h2></a>
    <ul>
        <li><a href="addgraves.php">Add Grave</a></li>
        <li><a href="viewgraves.php">View Graves</a></li>
        <li><a href="viewfeedback.php">View Feedback</a></li>
    </ul>
</div>

<style>
    /* Sidebar Styles */
    .side-menu {
        width: 200px;
        background-color: #333;
        color: white;
        padding: 20px;
        height: calc(100vh - 100px); /* Full height minus header height */
        position: fixed;
        left: -200px; /* Hide sidebar by default */
        top: 105px; /* Position below header */
        transition: left 0.3s ease;
        z-index: 1000;
    }

    .side-menu.active {
        left: 0; /* Show sidebar */
    }

    .side-menu h2 {
        color: white;
        top: 50px;
        margin-bottom: 20px;
        margin-left: 40px; /* Add space for the toggle button */
    }

    .side-menu ul {
        list-style: none;
        position: center;
        padding: 0;
    }

    .side-menu ul li {
        margin: 15px 0;
    }

    .side-menu ul li a {
        color: white;
        text-decoration: none;
        font-size: 16px;
    }

    .side-menu ul li a:hover {
        color: #3498db; /* Highlight color on hover */
    }

    /* Toggle Button Styles */
    #toggle-sidebar {
        background-color: #333;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        position: fixed; /* Fixed position */
        left: 0; /* Position on the left edge of the screen */
        top: 105px; /* Align with the top of the sidebar */
        padding: 5px 10px;
        z-index: 1001; /* Ensure it's above the sidebar */
        transition: left 0.3s ease; /* Smooth transition */
    }

    /* Adjust the toggle button position when the sidebar is active */
    .side-menu.active + #toggle-sidebar {
        left: 200px; /* Move the button to the right of the sidebar */
    }
</style>
<script src="js/admin.js"></script>