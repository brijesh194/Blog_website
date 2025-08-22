<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            // ✅ यहां id और name भी session में save कर लो
            $_SESSION['user'] = $email;
            $_SESSION['user_id'] = $row['id'];  
            $_SESSION['username'] = $row['username']; 
            

            $_SESSION['message'] = "Login Successful!";
            $_SESSION['type'] = "success";
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['message'] = "Invalid Password!";
            $_SESSION['type'] = "error";
            header("Location: index.php");
            exit;
        }
    } else {
        $_SESSION['message'] = "User not found!";
        $_SESSION['type'] = "error";
        header("Location: index.php");
        exit;
    }
}
?>
