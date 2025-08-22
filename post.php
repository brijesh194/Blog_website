<?php
session_start();
include('connect.php');

// Post fetch
$id = intval($_GET['id']);
$query = "SELECT * FROM posts WHERE id = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Comment submit
if (isset($_POST['add_comment']) && isset($_SESSION['user_id'])) {
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    mysqli_query($conn, "INSERT INTO comments (post_id, user_id, comment, created_at) 
                         VALUES ('$id', '$user_id', '$comment', NOW())");
    header("Location: post.php?id=$id");
    exit();
}

// Comment delete
if (isset($_GET['delete']) && isset($_SESSION['user_id'])) {
    $comment_id = intval($_GET['delete']);
    $user_id = $_SESSION['user_id'];
    mysqli_query($conn, "DELETE FROM comments WHERE id=$comment_id AND user_id=$user_id");
    header("Location: post.php?id=$id");
    exit();
}

// Comment edit
if (isset($_POST['edit_comment']) && isset($_SESSION['user_id'])) {
    $comment_id = intval($_POST['comment_id']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $user_id = $_SESSION['user_id'];
    mysqli_query($conn, "UPDATE comments SET comment='$comment' WHERE id=$comment_id AND user_id=$user_id");
    header("Location: post.php?id=$id");
    exit();
}

// Fetch all comments
$comments = mysqli_query($conn, "SELECT * FROM comments WHERE post_id=$id ORDER BY id DESC");
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php echo $row['title']; ?> - Full Post</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    * {margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif;}
    body, html {height: 100%; color: #fff;}
    #particles-js {position: fixed; width: 100%; height: 100%; background: black; z-index: -1; top: 0; left: 0;}
    nav {background: rgba(0, 0, 0, 0.7); padding: 15px 30px; display: flex; justify-content: space-between; align-items: center;}
    nav a {color: #fff; text-decoration: none; margin-left: 20px; font-weight: 600;}
    nav .logo {font-size: 24px; font-weight: bold;}
    .container {max-width: 800px; margin: 40px auto; padding: 20px; background: rgba(0, 0, 0, 0.65); border-radius: 10px; box-shadow: 0 0 10px rgba(255,255,255,0.1); animation: fadeIn 1s ease;}
    .container h1 {font-size: 30px; margin-bottom: 15px; color: #00ffdd;}
    .container img {max-width: 100%; margin: 20px 0; border-radius: 10px; animation: slideIn 1s ease;}
    .container p {font-size: 18px; text-align: justify; line-height: 1.7; margin-bottom: 30px;}
    footer {background: rgba(0,0,0,0.7); padding: 20px; text-align: center; color: #aaa; margin-top: 40px;}
    .comment-section {margin-top: 40px;}
    .comment-section textarea {width: 100%; height: 100px; padding: 10px; font-size: 16px; border-radius: 5px; border: none; resize: none;}
    .comment-section button {margin-top: 10px; padding: 10px 20px; background: #00ffdd; color: #000; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;}
    .comment {background: rgba(255,255,255,0.05); padding: 10px; border-radius: 5px; margin: 10px 0;}
    .comment strong {color: #00ffdd;}
    .comment small {color: #aaa; margin-left: 10px;}
    .actions a {margin-left: 10px; color: #ff6666; text-decoration:none; font-size:14px;}
    .edit-box {display:none; margin-top:10px;}
    @keyframes fadeIn {from {opacity: 0;} to {opacity: 1;}}
    @keyframes slideIn {from {transform: translateY(30px); opacity: 0;} to {transform: translateY(0); opacity: 1;}}
  </style>
</head>
<body>
<div id="particles-js"></div>

<nav>
  <div class="logo">BSSR Blog</div>
  <div>
    <a href="index.php">🏠 Home</a>
    <a href="newpost.php">📝 New Post</a>
  </div>
</nav>

<div class="container">
  <h1><?php echo $row['title']; ?></h1>
  <img src="uploads/<?php echo $row['image']; ?>" alt="Post Image" />
  <p><?php echo $row['content']; ?></p>

  <!-- Comment Section -->
  <div class="comment-section">
    <h2>💬 Comments</h2>

    <?php if (isset($_SESSION['user_id'])): ?>
      <form method="POST">
        <textarea name="comment" placeholder="Write your thoughts..." required></textarea>
        <button type="submit" name="add_comment">Submit Comment</button>
      </form>
    <?php else: ?>
      <p style="color:#ff8080;">⚠ Please <a href="login.php" style="color:#00ffdd;">login</a> to comment.</p>
    <?php endif; ?>

    <?php while ($c = mysqli_fetch_assoc($comments)): ?>
      <div class="comment">
        <strong><?php echo htmlspecialchars($c['user_id']); ?></strong>
        <small><?php echo date("d M Y H:i", strtotime($c['created_at'])); ?></small>
        <p id="text-<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['comment']); ?></p>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $c['user_id']): ?>
          <div class="actions">
            <a href="#" onclick="toggleEdit(<?php echo $c['id']; ?>);return false;">✏ Edit</a>
            <a href="post.php?id=<?php echo $id; ?>&delete=<?php echo $c['id']; ?>" onclick="return confirm('Delete this comment?')">🗑 Delete</a>
          </div>

          <!-- Hidden Edit Box -->
          <div class="edit-box" id="edit-<?php echo $c['id']; ?>">
            <form method="POST">
              <input type="hidden" name="comment_id" value="<?php echo $c['id']; ?>">
              <textarea name="comment" required><?php echo htmlspecialchars($c['comment']); ?></textarea>
              <button type="submit" name="edit_comment">Save</button>
            </form>
          </div>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<footer>
  &copy; <?php echo date("Y"); ?> BSSR Blog. All rights reserved.
</footer>

<!-- Particles.js -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script>
  particlesJS("particles-js", {
    "particles": {"number": {"value": 80,"density": {"enable": true,"value_area": 800}},"color": {"value": "#00ffcc"},"shape": {"type": "circle"},"opacity": {"value": 0.5},"size": {"value": 4,"random": true},"line_linked": {"enable": true,"distance": 150,"color": "#00ffcc","opacity": 0.4,"width": 1},"move": {"enable": true,"speed": 4,"bounce": true}},
    "interactivity": {"events": {"onhover": {"enable": true,"mode": "repulse"},"onclick": {"enable": true,"mode": "push"}},"modes": {"repulse": {"distance": 100,"duration": 0.4},"push": {"particles_nb": 4}}},
    "retina_detect": true
  });

  function toggleEdit(id){
    var box = document.getElementById("edit-"+id);
    box.style.display = (box.style.display==="block") ? "none" : "block";
  }
</script>
</body>
</html>
