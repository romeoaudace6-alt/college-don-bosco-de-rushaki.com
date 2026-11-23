<?php
session_start();
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
      background-color: #062f0b;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
      color: #fff;
    }
    .login-card {
      background-color: #133923;
      border: 1px solid #1f4a3d;
      padding: 3rem 2.5rem;
      border-radius: 24px;
      width: 100%;
      max-width: 440px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.5);
      text-align: center;
      position: relative;
    }
    .logo-container {
      width: 80px;
      height: 80px;
      background-color: #1b4332;
      border-radius: 20px;
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
      font-size: 2rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 0.5rem;
      letter-spacing: -0.5px;
    }
    .subtitle {
      color: #8ba89e;
      font-size: 1rem;
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
      padding: 1rem 1.2rem;
      background-color: transparent;
      border: 1px solid #2d6a4f;
      border-radius: 12px;
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
      box-shadow: 0 0 0 3px rgba(82, 183, 136, 0.15);
      background-color: #0d1f1a;
    }
    .toggle-password {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #8ba89e;
      font-size: 1.2rem;
      background: none;
      border: none;
      transition: 0.3s;
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
      gap: 0.5rem;
      color: #8ba89e;
      cursor: pointer;
    }
    .options input[type="checkbox"] {
      accent-color: #52b788;
      width: 16px;
      height: 16px;
    }
    .options a {
      color: #52b788;
      text-decoration: none;
      transition: 0.3s;
      font-weight: 500;
    }
    .options a:hover {
      color: #fff;
    }
    .btn-submit {
      width: 100%;
      padding: 1.1rem;
      background-color: #2d9e65;
      color: #fff;
      border: none;
      border-radius: 12px;
      font-weight: 700;
      font-size: 1.05rem;
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
      box-shadow: 0 10px 20px rgba(45, 158, 101, 0.2);
    }
    .switch-text {
      margin-top: 2rem;
      font-size: 0.95rem;
      color: #8ba89e;
    }
    .switch-text a {
      color: #fff;
      font-weight: 600;
      text-decoration: none;
      margin-left: 0.3rem;
      transition: 0.3s;
    }
    .switch-text a:hover {
      color: #52b788;
    }
    .bottom-arrow {
      position: fixed;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      color: #fff;
      background-color: #122a23;
      border: 1px solid #2d6a4f;
      width: 44px;
      height: 44px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0.7;
      text-decoration: none;
      transition: 0.3s;
    }
    .bottom-arrow:hover {
      opacity: 1;
      background-color: #1b4332;
    }
  </style>
</head>
<body>

  <div class="login-card">
    <div class="logo-container">
      <img src="assets/images/logo.png" alt="Logo">
    </div>

    <h2>Welcome back</h2>
    <p class="subtitle">Login into your account</p>

    <form action="api/login_process.php" method="POST">
      
      <div class="form-group">
        <label>Username</label>
        <div class="input-wrapper">
          <input type="text" name="username" placeholder="enter your username" required>
        </div>
      </div>

      <div class="form-group">
        <label>Password</label>
        <div class="input-wrapper">
          <input type="password" name="password" id="passwordInput" 
          placeholder="Enter your password" required>
          <button type="button" class="toggle-password" onclick="togglePassword()">
            👁️
          </button>
        </div>
      </div>

      <div class="options">
        <label>
          <input type="checkbox" name="remember"> remember me
        </label>
        <a href="#">forgot password?</a>
      </div>

      <button type="submit" class="btn-submit">
        Login <span>→</span>
      </button>
    </form>

    <p class="switch-text">
    don't have an account? <a href="#">Sign up</a>
    </p>
  </div>

  <a href="#" class="bottom-arrow">↓</a>

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