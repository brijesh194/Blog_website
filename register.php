<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // hashed password

    // Check if email already exists
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['message'] = "Email already registered!";
$_SESSION['type'] = "error";
header("Location: index.php");
exit;

    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        
        if ($stmt->execute()) {
            // $_SESSION['user'] = $email;
            $_SESSION['message'] = "Registered Successfully!";
            
$_SESSION['type'] = "success";
$_SESSION['show_login'] = true;
header("Location: index.php");
exit;

        } else {
            echo "<script>alert('Registration Failed!');window.location.href='index.php';</script>";
        }
    }
}
?>
