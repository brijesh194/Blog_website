<?php
$conn = new mysqli("localhost", "root", "", "blog_app");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$title   = $_POST['title'];
$content = $_POST['content'];

$image_name = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $image_name = time() . "_" . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image_name);
}

$stmt = $conn->prepare("INSERT INTO posts (title, content, image) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $title, $content, $image_name);

if ($stmt->execute()) {
    header("Location: index.php"); // home page
} else {
    echo "Error: " . $stmt->error;
}
?>
