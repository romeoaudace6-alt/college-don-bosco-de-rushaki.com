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
    :root { --green-darkest: #123f32; --green-medium: #2E7D32; --gold: #d5ad59; --white: #FFFFFF; --bg: #f4f7f4; --text: #1A1A1A; --text-light: #5A6B5A; --border: #E0E8E0; --sidebar-width: 260px; --header-height: 70px; --shadow: 0 4px 20px rgba(0,0,0,0.05); --radius: 12px; --transition: all 0.3s ease; }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); line-height: 1.6; }
    a { text-decoration: none; color: inherit; }
    ul { list-style: none; }
    button { font: inherit; cursor: pointer; border: none; background: none; }
    input, textarea, select { font: inherit; }
    .admin-layout { display: flex; min-height: 100vh; }
    .sidebar { width: var(--sidebar-width); background: var(--green-darkest); color: var(--white); height: 100vh; position: fixed; top: 0; left: 0; z-index: 100; overflow-y: auto; transition: var(--transition); }
    .sidebar-header { padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 0.8rem; }
    .sidebar-header .logo-small { width: 40px; height: 40px; background: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--green-darkest); font-family: 'Playfair Display', serif; font-weight: 900; font-size: 0.9rem; border: 2px solid var(--gold); }
    .sidebar-header h1 { font-family: 'Playfair Display', serif; font-size: 0.95rem; font-weight: 800; line-height: 1.2; }
    .sidebar-header small { display: block; font-size: 0.6rem; color: var(--gold); letter-spacing: 1px; margin-top: 2px; }
    .sidebar-nav { padding: 1rem 0; }
    .sidebar-nav a { display: flex; align-items: center; gap: 0.8rem; padding: 0.8rem 1.5rem; color: rgba(255,255,255,0.7); font-size: 0.85rem; font-weight: 500; transition: var(--transition); border-left: 3px solid transparent; }
    .sidebar-nav a:hover, .sidebar-nav a.active { background: rgba(255,255,255,0.08); color: var(--white); border-left-color: var(--gold); }
    .sidebar-nav a svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .main-content { margin-left: var(--sidebar-width); flex: 1; min-height: 100vh; transition: var(--transition); }
    .topbar { height: var(--header-height); background: var(--white); border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; padding: 0 2rem; position: sticky; top: 0; z-index: 50; }
    .topbar-left h2 { font-family: 'Playfair Display', serif; color: var(--green-darkest); font-size: 1.1rem; font-weight: 800; }
    .topbar-left p { font-size: 0.75rem; color: var(--text-light); }
    .topbar-right { display: flex; align-items: center; gap: 1rem; }
    .user-badge { background: var(--gold); color: var(--green-darkest); padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
    .logout-btn { padding: 0.5rem 1rem; background: #fce4e4; color: #d32f2f; border-radius: 6px; font-size: 0.8rem; font-weight: 700; transition: var(--transition); }
    .logout-btn:hover { background: #d32f2f; color: var(--white); }
    .dashboard-content { padding: 2rem; }
    .tab-content { display: none; }
    .tab-content.active { display: block; animation: fadeIn 0.4s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .form-card { background: var(--white); padding: 2rem; border-radius: var(--radius); box-shadow: var(--shadow); margin-bottom: 2rem; }
    .form-card h3 { font-family: 'Playfair Display', serif; color: var(--green-darkest); font-size: 1.1rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border); }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    .form-group { margin-bottom: 1.2rem; }
    .form-group.full { grid-column: 1 / -1; }
    .form-group label { display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-light); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 1px; }
    .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 0.7rem 1rem; border: 1px solid var(--border); border-radius: 6px; font-size: 0.9rem; transition: var(--transition); background: var(--bg); }
    .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: var(--green-darkest); background: var(--white); box-shadow: 0 0 0 3px rgba(18,63,50,0.1); }
    .form-group textarea { resize: vertical; min-height: 100px; }
    .form-group input[type="file"] { padding: 0.5rem; background: var(--white); }
    .btn-save { padding: 0.8rem 2rem; background: var(--green-darkest); color: var(--white); border-radius: 6px; font-weight: 700; font-size: 0.85rem; transition: var(--transition); display: inline-flex; align-items: center; gap: 0.5rem; }
    .btn-save:hover { background: var(--green-medium); }
    .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
    .stat-card { background: var(--white); padding: 1.5rem; border-radius: var(--radius); box-shadow: var(--shadow); border-left: 4px solid var(--gold); }
    .stat-card h3 { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-light); margin-bottom: 0.5rem; }
    .stat-card .num { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 900; color: var(--green-darkest); line-height: 1; }
    .table-wrapper { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; background: var(--white); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); }
    table th { background: var(--green-darkest); color: var(--white); padding: 1rem; text-align: left; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; }
    table td { padding: 1rem; border-bottom: 1px solid var(--border); font-size: 0.85rem; }
    .btn-delete { padding: 0.4rem 0.8rem; border-radius: 4px; font-size: 0.75rem; font-weight: 700; background: #fce4e4; color: #d32f2f; }
    .alert-success { background: #e8f5e9; color: #2e7d32; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #2e7d32; display: none; font-size: 0.85rem; }
    .alert-success.show { display: block; animation: fadeIn 0.3s ease; }
    .mobile-toggle { display: none; background: var(--green-darkest); color: var(--white); padding: 0.5rem 0.8rem; border-radius: 6px; font-size: 0.8rem; }
    @media (max-width: 1024px) {
      .sidebar { transform: translateX(-100%); }
      .sidebar.open { transform: translateX(0); }
      .main-content { margin-left: 0; }
      .mobile-toggle { display: block; }
      .form-grid { grid-template-columns: 1fr; }
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
        <a href="#" class="sidebar-link" data-tab="staff"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg> Abakozi</a>
        <a href="#" class="sidebar-link" data-tab="events"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Ibikorwa</a>
        <a href="#" class="sidebar-link" data-tab="news"><svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg> Amatangazo</a>
        <a href="#" class="sidebar-link" data-tab="gallery"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg> Amafoto</a>
        <a href="#" class="sidebar-link" data-tab="settings"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg> Settings</a>
      </nav>
    </aside>

    <main class="main-content">
      <div class="topbar">
        <div class="topbar-left">
          <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">☰</button>
          <h2>Admin Dashboard</h2>
          <p>Murakaza neza, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
        </div>
        <div class="topbar-right">
          <span class="user-badge">Admin</span>
          <a href="../logout.php" class="logout-btn">Logout</a>
        </div>
      </div>

      <div class="dashboard-content">
        <div class="alert-success" id="alertSuccess">✅ Byabitswe neza!</div>

        <div class="tab-content active" id="tab-dashboard">
          <div class="stats-row">
            <div class="stat-card"><h3>Abakozi</h3><div class="num" id="statStaff">0</div></div>
            <div class="stat-card"><h3>Ibikorwa</h3><div class="num" id="statEvents">0</div></div>
            <div class="stat-card"><h3>Amatangazo</h3><div class="num" id="statNews">0</div></div>
            <div class="stat-card"><h3>Amafoto</h3><div class="num" id="statGallery">0</div></div>
          </div>
        </div>

        <div class="tab-content" id="tab-content">
          <div class="form-card">
            <h3>Guhindura Content ya Ahabanza</h3>
            <form id="contentForm">
              <div class="form-grid">
                <div class="form-group full"><label>Umutwe (H1)</label><input type="text" name="hero_title" id="hero_title"></div>
                <div class="form-group full"><label>Ibisobanuro (Paragraph)</label><textarea name="hero_subtitle" id="hero_subtitle" rows="3"></textarea></div>
                <div class="form-group"><label>Badge Text</label><input type="text" name="hero_badge" id="hero_badge"></div>
                <div class="form-group full"><label>Amateka y'Ikigo (About)</label><textarea name="about_text" id="about_text" rows="4"></textarea></div>
              </div>
              <button type="submit" class="btn-save">💾 Bika Content</button>
            </form>
          </div>
        </div>

        <div class="tab-content" id="tab-staff">
          <div class="form-card">
            <h3>Kongeramo Umukozi</h3>
            <form id="addStaffForm">
              <div class="form-grid">
                <div class="form-group"><label>Amazina</label><input type="text" name="full_name" required></div>
                <div class="form-group"><label>Umwanya</label><input type="text" name="position" required></div>
                <div class="form-group"><label>Icyiciro</label><select name="category"><option>Umuyobozi</option><option>Umwarimu</option><option>Umukozi</option></select></div>
                <div class="form-group"><label>Ifoto</label><input type="file" name="photo" accept="image/*"></div>
              </div>
              <button type="submit" class="btn-save">➕ Ongeraho</button>
            </form>
          </div>
          <div class="table-wrapper" id="staffList"></div>
        </div>

        <div class="tab-content" id="tab-events">
          <div class="form-card">
            <h3>Kongeramo Igikorwa</h3>
            <form id="addEventForm">
              <div class="form-grid">
                <div class="form-group"><label>Izina</label><input type="text" name="title" required></div>
                <div class="form-group"><label>Itariki</label><input type="date" name="event_date" required></div>
                <div class="form-group"><label>Aho</label><input type="text" name="location"></div>
                <div class="form-group full"><label>Ibisobanuro</label><textarea name="description" rows="3"></textarea></div>
              </div>
              <button type="submit" class="btn-save">➕ Ongeraho</button>
            </form>
          </div>
          <div class="table-wrapper" id="eventsList"></div>
        </div>

        <div class="tab-content" id="tab-news">
          <div class="form-card">
            <h3>Kongeramo Itangazo</h3>
            <form id="addNewsForm">
              <div class="form-grid">
                <div class="form-group full"><label>Umutwe</label><input type="text" name="title" required></div>
                <div class="form-group full"><label>Ibisobanuro</label><textarea name="content" rows="3" required></textarea></div>
                <div class="form-group"><label>Itariki</label><input type="date" name="date_posted" required></div>
              </div>
              <button type="submit" class="btn-save">➕ Ongeraho</button>
            </form>
          </div>
          <div class="table-wrapper" id="newsList"></div>
        </div>

        <div class="tab-content" id="tab-gallery">
          <div class="form-card">
            <h3>Kongeramo Ifoto</h3>
            <form id="addGalleryForm">
              <div class="form-grid">
                <div class="form-group full"><label>Ifoto</label><input type="file" name="image" accept="image/*" required></div>
                <div class="form-group full"><label>Ibisobanuro</label><input type="text" name="caption"></div>
              </div>
              <button type="submit" class="btn-save">➕ Ongeraho</button>
            </form>
          </div>
          <div class="table-wrapper" id="galleryList"></div>
        </div>

        <div class="tab-content" id="tab-settings">
          <div class="form-card">
            <h3>Igenamiterere ry'Ikigo</h3>
            <form id="settingsForm">
              <div class="form-grid">
                <div class="form-group"><label>Izina</label><input type="text" name="school_name" id="school_name"></div>
                <div class="form-group"><label>Telefone</label><input type="text" name="phone" id="phone"></div>
                <div class="form-group"><label>Email</label><input type="email" name="email" id="email"></div>
                <div class="form-group"><label>Aderesi</label><input type="text" name="address" id="address"></div>
                <div class="form-group full"><label>Amasaha</label><input type="text" name="working_hours" id="working_hours"></div>
              </div>
              <button type="submit" class="btn-save">💾 Bika Settings</button>
            </form>
          </div>
        </div>

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