<?php
session_start();
require_once '../api/auth.php';
requireLogin('admin');
?>
<!DOCTYPE html>
<html lang="rw">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | College Don Bosco</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;800;900&display=swap" rel="stylesheet">
  <style>
 
    :root { --green-darkest: #024612;
     --green-dark: #1B5E20;
      --green-medium: #2E7D32;
       --gold: #d5ad59;
        --white: #FFFFFF; 
        --bg: #f4f7f4;
         --text: #1A1A1A; 
         --text-light: #5A6B5A;
          --border: #E0E8E0;
           --sidebar-width: 260px;
            --header-height: 70px; 
            --shadow: 0 4px 20px rgba(0,0,0,0.05);
             --radius: 12px; --transition: all 0.3s ease; }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif;
     background: var(--bg); color: var(--text);
      line-height: 1.6; }
    a { text-decoration: none; 
    color: inherit; }
    ul { list-style: none; }
    button { font: inherit; 
    cursor: pointer; border: none; 
    background: none; }
    input, textarea, select { font: inherit; }
    
  
    .admin-layout { display: flex; min-height: 100vh; }
    .sidebar { width: var(--sidebar-width);
     background: var(--green-darkest); color: var(--white);
      height: 100vh; position: fixed; top: 0; left: 0;
       z-index: 100; overflow-y: auto;
        transition: var(--transition); }
    .sidebar-header { padding: 1.5rem;
     border-bottom: 1px solid rgba(255,255,255,0.1);
      display: flex; align-items: center; gap: 0.8rem; }
    .sidebar-header .logo-small { width: 40px; height: 40px; 
    background: var(--white);
     border-radius: 50%;
      display: flex; 
      align-items: center; 
      justify-content: center; 
      color: var(--green-darkest); 
      font-family: 'Playfair Display', serif; font-weight: 900; 
      font-size: 0.9rem; border: 2px solid var(--gold); }
    .sidebar-header h1 { font-family: 'Playfair Display', serif;
     font-size: 0.95rem; font-weight: 800; line-height: 1.2; }
    .sidebar-header small { display: block;
     font-size: 0.6rem; color: var(--gold); 
     letter-spacing: 1px; margin-top: 2px; }
    .sidebar-nav { padding: 1rem 0; }
    .sidebar-nav a { display: flex;
     align-items: center; gap: 0.8rem;
      padding: 0.8rem 1.5rem;
       color: rgba(255,255,255,0.7);
        font-size: 0.85rem;
         font-weight: 500;
          transition: var(--transition);
           border-left: 3px solid transparent; }
    .sidebar-nav a:hover, .sidebar-nav a.active { background: rgba(255,255,255,0.08);
     color: var(--white); border-left-color: var(--gold); }
    .sidebar-nav a svg { width: 18px; height: 18px;
     stroke: currentColor; fill: none; 
     stroke-width: 2;
      stroke-linecap: round; 
      stroke-linejoin: round; }
    
    .main-content { margin-left: var(--sidebar-width);
     flex: 1; min-height: 100vh;
      transition: var(--transition);
     }
    .topbar { height: var(--header-height);
     background: var(--white);
      border-bottom: 1px solid var(--border);
       display: flex; align-items: center; 
       justify-content: space-between;
        padding: 0 2rem; position: sticky; top: 0; z-index: 50; }
    .topbar-left h2 { font-family: 'Playfair Display', serif;
     color: var(--green-darkest); font-size: 1.1rem; font-weight: 800; }
    .topbar-left p { font-size: 0.75rem; color: var(--text-light); }
    .topbar-right { display: flex; align-items: center; gap: 1rem; }
    .user-badge { background: var(--gold); color: var(--green-darkest);
     padding: 0.3rem 0.8rem; border-radius: 20px; 
     font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
    .logout-btn { padding: 0.5rem 1rem; background: #fce4e4;
     color: #d32f2f; border-radius: 6px; font-size: 0.8rem;
      font-weight: 700; transition: var(--transition); }
    .logout-btn:hover { background: #d32f2f; color: var(--white); }
    
    .dashboard-content { padding: 2rem; }
    .tab-content { display: none; }
    .tab-content.active { display: block; animation: fadeIn 0.4s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); 
  } to { opacity: 1; transform: translateY(0); } }
    
    
    .form-card { background: var(--white); padding: 2rem;
     border-radius: var(--radius); box-shadow: var(--shadow); margin-bottom: 2rem; }
    .form-card h3 { font-family: 'Playfair Display', serif;
     color: var(--green-darkest); font-size: 1.1rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border); }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    .form-group { margin-bottom: 1.2rem; }
    .form-group.full { grid-column: 1 / -1; }
    .form-group label { display: block; font-size: 0.75rem; 
    font-weight: 700; color: var(--text-light);
     margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 1px; }
    .form-group input, .form-group textarea, .form-group select { width: 100%;
     padding: 0.7rem 1rem; border: 1px solid var(--border); border-radius: 6px;
      font-size: 0.9rem; transition: var(--transition); background: var(--bg); }
    .form-group input:focus, .form-group textarea:focus { outline: none;
     border-color: var(--green-dark); background: var(--white);
      box-shadow: 0 0 0 3px rgba(27,94,32,0.1); }
    .form-group textarea { resize: vertical; min-height: 100px; }
    .btn-save { padding: 0.8rem 2rem; background: var(--green-darkest); 
    color: var(--white); border-radius: 6px; font-weight: 700; 
    font-size: 0.85rem; transition: var(--transition);
     display: inline-flex; align-items: center; gap: 0.5rem; }
    .btn-save:hover { background: var(--green-medium); }
    
   
    .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
     gap: 1.5rem; margin-bottom: 2rem; }
    .stat-card { background: var(--white);
     padding: 1.5rem; border-radius: var(--radius); box-shadow: var(--shadow);
      border-left: 4px solid var(--gold); }
    .stat-card h3 { font-size: 0.8rem; text-transform: uppercase; 
    letter-spacing: 1px; color: var(--text-light); margin-bottom: 0.5rem; }
    .stat-card .num { font-family: 'Playfair Display', serif;
     font-size: 2rem; font-weight: 900; color: var(--green-darkest); line-height: 1; }
    
   
    .alert-success { background: #e8f5e9; color: #2e7d32;
     padding: 1rem; border-radius: 8px; margin-bottom: 1rem;
      border-left: 4px solid #2e7d32; display: none; font-size: 0.85rem; }
    .alert-success.show { display: block; animation: fadeIn 0.3s ease; }
    
   
    .mobile-toggle { display: none; background: var(--green-darkest); color: var(--white); padding: 0.5rem 0.8rem; border-radius: 6px; font-size: 0.8rem; }
    @media (max-width: 1024px) {
      .sidebar { transform: translateX(-100%); }
      .sidebar.open { transform: translateX(0); }
      .main-content { margin-left: 0; }
      .mobile-toggle { display: block; }
      .form-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 600px) {
      .topbar { padding: 0 1rem; }
      .dashboard-content { padding: 1rem; }
      .form-card { padding: 1.5rem; }
    }
  </style>
</head>
<body>

  <div class="admin-layout">
    
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-header">
        <div class="logo-small">DB</div>
        <div>
          <h1>Admin Panel</h1>
          <small>College Don Bosco</small>
        </div>
      </div>
      <nav class="sidebar-nav">
        <a href="#" class="sidebar-link active" data-tab="dashboard"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg> Dashboard</a>
        <a href="#" class="sidebar-link" data-tab="content"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg> Content</a>
        <a href="#" class="sidebar-link" data-tab="settings"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg> Settings</a>
      </nav>
    </aside>

  
    <main class="main-content">
      <div class="topbar">
        <div class="topbar-left">
          <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">☰</button>
          <h2>Admin Dashboard</h2>
          <p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
        </div>
        <div class="topbar-right">
          <span class="user-badge">Audace romeo</span>
          <a href="../logout.php" class="logout-btn">Logout</a>
        </div>
      </div>

      <div class="dashboard-content">
        <div class="alert-success" id="alertSuccess">✅ Login successufully!</div>

       
        <form id="mainSettingsForm">
          
         
          <div class="tab-content active" id="tab-dashboard">
            <div class="stats-row">
              <div class="stat-card"><h3>Our tem</h3><div class="num">0</div></div>
              <div class="stat-card"><h3>Events</h3><div class="num">0</div></div>
              <div class="stat-card"><h3>Announcements</h3><div class="num">0</div></div>
              <div class="stat-card"><h3>Images</h3><div class="num">0</div></div>
            </div>
          </div>

         
          <div class="tab-content" id="tab-content">
            <div class="form-card">
              <h3>📝 change homepage conent</h3>
              <div class="form-grid">
                <div class="form-group full">
                  <label>Heading</label>
                  <input type="text" name="hero_title" id="hero_title"></div>
                <div class="form-group full">
                  <label>Paragraph</label>
                  <textarea name="hero_subtitle" id="hero_subtitle" rows="3"></textarea></div>
                <div class="form-group">
                  <label>Badge Text</label>
                  <input type="text" name="hero_badge" id="hero_badge"></div>
                <div class="form-group full">
                  <label>About</label><textarea name="about_text" id="about_text" rows="4">

                  </textarea></div>
              </div>
              <button type="submit" class="btn-save">💾 Save Content</button>
            </div>
          </div>

          
          <div class="tab-content" id="tab-settings">
            <div class="form-card">
              <h3>⚙️ School settings</h3>
              <div class="form-grid">
                <div class="form-group">
                  <label>School name</label>
                  <input type="text" name="school_name" id="school_name"></div>
                <div class="form-group">
                  <label>Phone number</label>
                  <input type="text" name="phone" id="phone"></div>
                <div class="form-group">
                  <label>Email</label><input type="email" name="email" id="email"></div>
                <div class="form-group">
                  <label>Adress</label><input type="text" name="address" id="address"></div>
                <div class="form-group full">
                  <label>working hours</label>
                  <input type="text" name="working_hours" id="working_hours"></div>
              </div>
              <button type="submit" class="btn-save">💾 Save Settings</button>
            </div>
          </div>

        </form>

      </div>
    </main>
  </div>

  <script src="js/admin.js"></script>
  <script>
   
    document.querySelectorAll('.sidebar-link').forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const tab = this.dataset.tab;
        document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
        this.classList.add('active');
        document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.add('active');
        document.getElementById('sidebar').classList.remove('open');
      });
    });
  </script>
</body>
</html>