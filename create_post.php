<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create New Post</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body, html {
      font-family: 'Poppins', sans-serif;
      height: 100%;
      overflow-x: hidden;
      color: #fff;
    }

    header {
      background: rgba(0, 0, 0, 0.6);
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: relative;
      z-index: 999;
    }

    .logo {
      font-size: 24px;
      font-weight: bold;
    }

    nav {
      display: flex;
      gap: 20px;
    }

    nav a {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
    }

    .hamburger {
      display: none;
      font-size: 24px;
      cursor: pointer;
      color: white;
    }

    @media (max-width: 768px) {
      nav {
        display: none;
        flex-direction: column;
        position: absolute;
        top: 70px;
        right: 30px;
        background: rgba(0, 0, 0, 0.9);
        padding: 15px;
        border-radius: 10px;
        z-index: 999;
      }

      nav.active {
        display: flex;
      }

      .hamburger {
        display: block;
      }
    }

    .form-container {
      max-width: 800px;
      margin: 100px auto;
      background: rgba(255, 255, 255, 0.1);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0,0,0,0.4);
      backdrop-filter: blur(10px);
    }

    .form-container h2 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 28px;
    }

    input[type="text"],
    input[type="file"],
    input[type="submit"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: none;
      border-radius: 6px;
      font-size: 16px;
    }

    input[type="submit"] {
      background: #00adb5;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }

    input[type="submit"]:hover {
      background: #008891;
    }

    #editor-container {
      background: white;
      color: black;
      border-radius: 6px;
      min-height: 500px;
      margin-bottom: 15px;
    }

    .upload-image-section {
      margin-top: 20px;
    }

    footer {
      margin-top: 60px;
      padding: 20px;
      text-align: center;
      background: rgba(0, 0, 0, 0.8);
      color: #fff;
    }

    .ql-toolbar {
      border-radius: 6px 6px 0 0;
      background: #ffffff;
    }

    .ql-container {
      border-radius: 0 0 6px 6px;
      background: white;
      color: black;
    }
  </style>
</head>
<body>

  <!-- VANTA Background -->
  <div id="vanta-bg" style="position: fixed; width: 100%; height: 100%; z-index: -1;"></div>

  <!-- Navbar -->
  <header>
    <div class="logo">MyBlog</div>
    <i class="fas fa-bars hamburger" onclick="toggleMenu()"></i>
    <nav id="menu">
      <a href="index.php">Home</a>
      <a href="newpost.php">New Post</a>
      <a href="logout.php">Logout</a>
    </nav>
  </header>

  <!-- Form -->
  <div class="form-container">
    <h2>Create a New Post</h2>
    <form method="POST" action="save_post.php" enctype="multipart/form-data" onsubmit="return submitPost()">
      <input type="text" name="title" placeholder="Enter Post Title" required />
      <input type="hidden" name="content" id="hiddenContent">
      <div id="editor-container"></div>

      <div class="upload-image-section">
        <label for="post_image">Upload Image:</label>
        <input type="file" name="image" accept="image/*">
      </div>

      <input type="submit" value="Publish Post">
    </form>
  </div>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 MyBlog. All rights reserved.</p>
  </footer>

  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>
  <script>
    VANTA.WAVES({
      el: "#vanta-bg",
      mouseControls: true,
      touchControls: true,
      gyroControls: false,
      scale: 1.0,
      scaleMobile: 1.0,
      color: 0x000000,
      shininess: 50,
      waveHeight: 15,
      waveSpeed: 1.0,
      zoom: 0.85
    });

    function toggleMenu() {
      document.getElementById("menu").classList.toggle("active");
    }
  </script>

  <!-- Quill JS -->
  <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
  <script>
    const quill = new Quill('#editor-container', {
      theme: 'snow',
      placeholder: 'Write your post here...',
      modules: {
        toolbar: [
          [{ header: [1, 2, 3, false] }],
          ['bold', 'italic', 'underline', 'strike'],
          [{ 'color': [] }, { 'background': [] }],
          [{ list: 'ordered' }, { list: 'bullet' }],
          ['link', 'image', 'video'],
          ['clean']
        ]
      }
    });

    function submitPost() {
      const content = document.querySelector('#hiddenContent');
      content.value = quill.root.innerHTML;
      return true;
    }
  </script>
</body>
</html>
