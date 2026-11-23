<?php
session_start();
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin/index.php');
        exit;
    } elseif ($_SESSION['role'] === 'editor') {
        header('Location: editor/index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="rw">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Injira | College Don Bosco de Rushaki</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Inter', sans-serif;
      background-color: #0d1f1a;
      background-image: url('assets/images/hero.png');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
      color: #fff;
    }
    .overlay {
      position: fixed;
      inset: 0;
      background: rgba(13, 31, 26, 0.85);
      z-index: 1;
    }
    .login-card {
      background-color: #122a23;
      border: 1px solid #1f4a3d;
      padding: 3rem 2.5rem;
      border-radius: 24px;
      width: 100%;
      max-width: 440px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.5);
      text-align: center;
      position: relative;
      z-index: 2;
    }
    .logo-container {
      width: 70px;
      height: 70px;
      background-color: #1b4332;
      border-radius: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      border: 1px solid #2d6a4f;
      overflow: hidden;
    }
    .logo-container img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      padding: 5px;
    }
    h2 {
      font-size: 1.8rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 0.5rem;
    }
    .subtitle {
      color: #8ba89e;
      font-size: 0.95rem;
      margin-bottom: 2.5rem;
    }
    .form-group {
      text-align: left;
      margin-bottom: 1.5rem;
    }
    .form-group label {
      display: block;
      font-size: 0.9rem;
      font-weight: 500;
      color: #d1e0d9;
      margin-bottom: 0.5rem;
    }
    .input-wrapper {
      position: relative;
    }
    .input-wrapper input {
      width: 100%;
      padding: 0.9rem 1rem;
      background-color: transparent;
      border: 1px solid #2d6a4f;
      border-radius: 10px;
      color: #fff;
      font-size: 0.95rem;
      font-family: inherit;
      transition: 0.3s;
    }
    .input-wrapper input::placeholder {
      color: #5a7a6d;
    }
    .input-wrapper input:focus {
      outline: none;
      border-color: #52b788;
      box-shadow: 0 0 0 2px rgba(82, 183, 136, 0.2);
    }
    .toggle-password {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #8ba89e;
      font-size: 1.1rem;
      background: none;
      border: none;
    }
    .toggle-password:hover {
      color: #fff;
    }
    .options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      font-size: 0.85rem;
    }
    .options label {
      display: flex;
      align-items: center;
      gap: 0.4rem;
      color: #8ba89e;
      cursor: pointer;
    }
    .options input[type="checkbox"] {
      accent-color: #52b788;
      width: 15px;
      height: 15px;
    }
    .options a {
      color: #52b788;
      text-decoration: none;
      transition: 0.3s;
    }
    .options a:hover {
      color: #fff;
    }
    .btn-submit {
      width: 100%;
      padding: 1rem;
      background-color: #2d9e65;
      color: #fff;
      border: none;
      border-radius: 10px;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }
    .btn-submit:hover {
      background-color: #238553;
      transform: translateY(-2px);
    }
    .switch-text {
      margin-top: 2rem;
      font-size: 0.9rem;
      color: #8ba89e;
    }
    .switch-text a {
      color: #fff;
      font-weight: 600;
      text-decoration: none;
    }
    .switch-text a:hover {
      text-decoration: underline;
    }
    .error-msg {
      background-color: #4a1c1c;
      color: #ff8a8a;
      border: 1px solid #7a2e2e;
      padding: 0.8rem;
      border-radius: 8px;
      font-size: 0.85rem;
      margin-bottom: 1.5rem;
      text-align: left;
    }
    .hint {
      margin-top: 1.5rem;
      padding: 0.8rem;
      background: rgba(255,255,255,0.05);
      border-radius: 8px;
      font-size: 0.7rem;
      color: #8ba89e;
      text-align: left;
    }
    .hint strong { color: #d1e0d9; }
  </style>
</head>
<body>

  <div class="overlay"></div>

  <div class="login-card">
    <div class="logo-container">
      <img src="assets/images/logo.png" alt="Logo" onerror="this.src='https://via.placeholder.com/50/1b4332/52b788?text=DB'">
    </div>

    <h2>Murakaza Neza</h2>
    <p class="subtitle">Injira muri konti yawe</p>

    <?php if (isset($_GET['error'])): ?>
      <div class="error-msg">❌ Username cyangwa Password si byo!</div>
    <?php endif; ?>

    <form action="api/login_process.php" method="POST">
      
      <div class="form-group">
        <label>Username</label>
        <div class="input-wrapper">
          <input type="text" name="username" placeholder="Andika username yawe" required>
        </div>
      </div>

      <div class="form-group">
        <label>Password</label>
        <div class="input-wrapper">
          <input type="password" name="password" id="passwordInput" placeholder="Andika password yawe" required>
          <button type="button" class="toggle-password" onclick="togglePassword()">👁️</button>
        </div>
      </div>

      <div class="options">
        <label>
          <input type="checkbox" name="remember"> Nukunde
        </label>
        <a href="#">Wibagiwe password?</a>
      </div>

      <button type="submit" class="btn-submit">
        Injira <span>→</span>
      </button>
    </form>

    <p class="switch-text">
      Nta konti ufite? <a href="#">Saba konti</a>
    </p>

    <div class="hint">
      <strong>Admin:</strong> donbosco_admin / Admin@2026<br>
      <strong>Editor:</strong> donbosco_editor / Editor@2026
    </div>
  </div>

  <script>
    function togglePassword() {
      const input = document.getElementById('passwordInput');
      if (input.type === 'password') {
        input.type = 'text';
      } else {
        input.type = 'password';
      }
    }
  </script>

</body>
</html>