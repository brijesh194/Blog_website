<?php
session_start();
include 'db.php';
$message = '';
$type = '';

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $type = $_SESSION['type'];
    unset($_SESSION['message']);
    unset($_SESSION['type']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Blog</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <?php if ($message): ?>
  <div id="notification" class="fixed top-5 right-5 z-50 px-4 py-2 rounded text-white font-semibold 
      <?= $type === 'success' ? 'bg-green-500' : 'bg-red-500' ?>">
    <?= $message ?>
  </div>
<?php endif; ?>

  <!-- Navbar -->
  <nav class="fixed w-full bg-white shadow-md z-10">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-blue-600">MyBlog</h1>
      <ul class="hidden md:flex space-x-6 font-medium">
        <li><a href="#" class="hover:text-blue-500 transition">Home</a></li>
        <li><a href="#" class="hover:text-blue-500 transition">Posts</a></li>
        <li><a href="#" class="hover:text-blue-500 transition">About</a></li>
        <li><a href="#" class="hover:text-blue-500 transition">Contact</a></li>
      </ul>
 <?php if (isset($_SESSION['user'])): ?>
  <div class="flex gap-3">
    <a href="create_post.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">✍️ Create Post</a>
    <button onclick="window.location.href='logout.php'" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">Logout</button>
  </div>
<?php else: ?>
  <button id="openModal" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Login</button>
<?php endif; ?>

  </nav>

  <!-- Hero Section -->
  <section class="pt-24 bg-gradient-to-r from-blue-100 via-purple-100 to-pink-100 py-20 text-center">
    <div class="max-w-3xl mx-auto">
      <h2 class="text-4xl md:text-5xl font-bold mb-4">Welcome to My Blog</h2>
      <p class="text-lg text-gray-700">A space to share your thoughts, stories, and connect with the world.</p>
      
    </div>
  </section>

<!-- Modal -->
<div id="authModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 relative animate__animated animate__fadeInDown">
    
    <span id="closeModal" class="absolute top-2 right-4 text-xl cursor-pointer">&times;</span>

    <!-- Toggle Buttons -->
    <div class="flex justify-center gap-4 mb-4">
      <button id="showLogin" class="text-blue-600 font-bold">Login</button>
      <button id="showRegister" class="text-gray-600">Register</button>
    </div>

    <!-- Login Form -->
    <form id="loginForm" method="POST" action="login.php" class="">
      <input type="email" name="email" placeholder="Email" required class="w-full mb-3 p-2 border rounded" />
      <input type="password" name="password" placeholder="Password" required class="w-full mb-3 p-2 border rounded" />
      <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Login</button>
    </form>

    <!-- Register Form -->
    <form id="registerForm" method="POST" action="register.php" class="hidden">
      <input type="text" name="name" placeholder="Full Name" required class="w-full mb-3 p-2 border rounded" />
      <input type="email" name="email" placeholder="Email" required class="w-full mb-3 p-2 border rounded" />
      <input type="password" name="password" placeholder="Password" required class="w-full mb-3 p-2 border rounded" />
      <button type="submit" class="w-full bg-green-600 text-white py-2 rounded">Register</button>
    </form>
  </div>
</div>

  <!-- Post Section -->
  <section class="max-w-7xl mx-auto px-4 py-16">
    <h2 class="text-3xl font-semibold text-center mb-10">Latest Posts</h2>
    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
<?php
$result = $conn->query("SELECT * FROM posts ORDER BY id DESC");
while ($row = $result->fetch_assoc()) {
    echo "<div class='bg-white shadow-md rounded-xl overflow-hidden hover:shadow-lg transition'>";
    if (!empty($row['image'])) {
        echo "<img src='uploads/" . htmlspecialchars($row['image']) . "' alt='Post Image' class='w-full h-48 object-cover' />";
    }
    echo "<div class='p-5'>";
    echo "<h3 class='text-xl font-semibold mb-2'>" . htmlspecialchars($row['title']) . "</h3>";
    echo "<p class='text-gray-600 mb-4'>" . substr(strip_tags($row['content']), 0, 100) . "...</p>";
    echo "<a href='post.php?id=" . $row['id'] . "' class='inline-block px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition'>Read More</a>";
    echo "</div></div>";
}
?>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-900 text-gray-100 py-10">
    <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-3 gap-8">
      <div>
        <h4 class="text-xl font-semibold mb-4">About</h4>
        <p>MyBlog is a platform for everyone to share knowledge, ideas, and creativity with the world.</p>
      </div>
      <div>
        <h4 class="text-xl font-semibold mb-4">Quick Links</h4>
        <ul class="space-y-2">
          <li><a href="#" class="hover:underline">Home</a></li>
          <li><a href="#" class="hover:underline">Posts</a></li>
          <li><a href="#" class="hover:underline">About</a></li>
          <li><a href="#" class="hover:underline">Contact</a></li>
        </ul>
      </div>
      <div>
        <h4 class="text-xl font-semibold mb-4">Contact</h4>
        <p>Email: support@myblog.com</p>
        <p>Phone: +91 99999 99999</p>
        <div class="mt-4 flex space-x-4">
          <a href="#" class="hover:text-blue-400">Facebook</a>
          <a href="#" class="hover:text-blue-400">Twitter</a>
          <a href="#" class="hover:text-blue-400">Instagram</a>
        </div>
      </div>
    </div>
    <div class="text-center mt-8 text-sm text-gray-400">&copy; 2025 MyBlog. All rights reserved.</div>
  </footer>

 <script>
  const modal = document.getElementById('authModal');
  const openBtn = document.getElementById('openModal');
  const closeBtn = document.getElementById('closeModal');
  const loginForm = document.getElementById('loginForm');
  const registerForm = document.getElementById('registerForm');
  const showLogin = document.getElementById('showLogin');
  const showRegister = document.getElementById('showRegister');

  openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
  closeBtn.addEventListener('click', () => modal.classList.add('hidden'));

  showLogin.addEventListener('click', () => {
    loginForm.classList.remove('hidden');
    registerForm.classList.add('hidden');
    showLogin.classList.add('text-blue-600', 'font-bold');
    showRegister.classList.remove('text-blue-600', 'font-bold');
  });

  showRegister.addEventListener('click', () => {
    loginForm.classList.add('hidden');
    registerForm.classList.remove('hidden');
    showRegister.classList.add('text-blue-600', 'font-bold');
    showLogin.classList.remove('text-blue-600', 'font-bold');
  });
</script>
<script>
  const notification = document.getElementById('notification');
  if (notification) {
    setTimeout(() => {
      notification.style.display = 'none';
    }, 5000);
  }
</script>
</body>
</html>
