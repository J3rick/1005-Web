<?php
// Database connection settings
$servername = "localhost";  // Change this to your database host, e.g., localhost or IP address
$username = "root";         // Your database username
$password = "";             // Your database password
$dbname = "your_database";  // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Get the current timestamp for Submitted_At
    $submitted_at = date('Y-m-d H:i:s');  // Format: YYYY-MM-DD HH:MM:SS
    
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO feedback (Feedback_Name, Feedback_Email, Feedback_Msg, Submitted_At) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $name, $email, $message, $submitted_at);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo "Feedback submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>
