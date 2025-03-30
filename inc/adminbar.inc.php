<button id="toggle-sidebar" onclick="toggleSidebar()">☰</button>
<div class="side-menu" id="side-menu">
    <a href= "admin.php"><h2>Menu</h2></a>
    <ul>
        <li><a href="addgraves.php">Add Grave</a></li>
        <li><a href="viewgraves.php">View Graves</a></li>
        <li><a href="feedback.php">View Feedback</a></li>
    </ul>
</div>

<div class="sidebar-overlay" onclick="toggleSidebar()"></div>

<style>
    /* Sidebar Styles */
    .side-menu {
        width: 200px;
        background-color: #333;
        color: white;
        padding: 20px;
        height: calc(100vh - 100px);
        position: fixed;
        left: -220px;
        top: 105px;
        transition: left 0.3s ease;
        z-index: 1000;
    }

    .side-menu.active {
        left: 0; /* Show sidebar */
    }

    .table-container {
        overflow-x: auto;
        width: 100%;
        transition : all 0.3s ease;
    }

    .sidebar-open .table-container {
        width: calc(100% - 220px); /* Adjust width when sidebar is open */
    }

    .sidebar-open table {
        width: 100%;
        min-width: 1000px;
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

    @media (max-width: 768px) {
        .sidebar-open table {
            width: 100% !important;
            margin-left: 0 !important;
            overflow-x: auto;
            display: block;
        }
    }

</style>
<script src="js/admin.js"></script>