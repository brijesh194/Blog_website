<?php
$servername = "localhost";   // XAMPP या WAMP के लिए localhost
$username = "root";          // default username
$password = "";              // default password (खाली होता है)
$dbname = "blog_app";            // अपने database का नाम यहां लिखें

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// echo "Database connected successfully"; // टेस्टिंग के लिए
?>
