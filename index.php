<?php
require_once 'api/db.php';
$db = getDB();

$settings = $db->query("SELECT * FROM settings WHERE id = 1")->fetch_assoc();
$backgrounds = [];
$bg_result = $db->query("SELECT * FROM backgrounds");
while ($row = $bg_result->fetch_assoc()) $backgrounds[$row['name']] = $row['image_path'];

$staffs = $db->query("SELECT * FROM staff ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
$events = $db->query("SELECT * FROM events ORDER BY event_date DESC")->fetch_all(MYSQLI_ASSOC);
$news = $db->query("SELECT * FROM news ORDER BY date_posted DESC")->fetch_all(MYSQLI_ASSOC);
$galleries = $db->query("SELECT * FROM gallery ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="rw">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($settings['school_name']) ?> | O'Level & A'Level</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
  <style>
    :root { --g-dark: #123f32; --g-light: #2E7D32; --gold: #d5ad59; --white: #fff; --bg: #f4f7f4; --text: #1A1A1A; --text-light: #5A6B5A; --border: #E0E8E0; --shadow: 0 4px 20px rgba(0,0,0,0.08); }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html { scroll-behavior: smooth; scroll-padding-top: 80px; }
    body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); line-height: 1.6; }
    a { text-decoration: none; color: inherit; }
    ul { list-style: none; }
    img { max-width: 100%; display: block; }

    header { background: var(--g-dark); color: var(--white); padding: 1rem 5%; position: fixed; top: 0; left: 0; right: 0; z-index: 1000; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.2); }
    .logo { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 800; display: flex; align-items: center; gap: 10px; }
    .logo img { width: 40px; height: 40px; border-radius: 50%; background: white; padding: 2px; border: 2px solid var(--gold); }
    nav ul { display: flex; gap: 1rem; align-items: center; }
    nav a { font-size: 0.8rem; font-weight: 500; transition: 0.3s; padding-bottom: 5px; border-bottom: 2px solid transparent; }
    nav a:hover, nav a.active { color: var(--gold); border-bottom-color: var(--gold); }
    
    .lang-switch { display: flex; gap: 5px; background: rgba(255,255,255,0.1); padding: 3px; border-radius: 20px; }
    .lang-switch button { background: none; border: none; color: white; padding: 5px 12px; border-radius: 15px; cursor: pointer; font-weight: bold; font-size: 0.75rem; transition: 0.3s; }
    .lang-switch button.active { background: var(--gold); color: var(--g-dark); }

    .hero { min-height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 6rem 1rem 4rem; background-size: cover; background-position: center; background-attachment: fixed; position: relative; color: var(--white); }
    .hero::before { content: ''; position: absolute; inset: 0; background: rgba(18, 63, 50, 0.75); z-index: 1; }
    .hero-content { position: relative; z-index: 2; max-width: 800px; }
    .badge { background: var(--gold); color: var(--g-dark); padding: 6px 15px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; margin-bottom: 1.5rem; display: inline-block; letter-spacing: 1px; }
    .hero h1 { font-family: 'Playfair Display', serif; font-size: clamp(2rem, 5vw, 4rem); margin-bottom: 1rem; line-height: 1.1; }
    .hero p { font-size: 1.1rem; margin-bottom: 2rem; opacity: 0.95; }
    .btn { background: var(--gold); color: var(--g-dark); padding: 12px 30px; border-radius: 5px; font-weight: 700; font-size: 0.9rem; display: inline-block; transition: 0.3s; }
    .btn:hover { transform: translateY(-3px); background: #e8c77a; }
    
    .stats-bar { background: var(--g-light); color: var(--white); display: grid; grid-template-columns: repeat(4, 1fr); padding: 2rem 5%; text-align: center; }
    .stat-item h3 { font-family: 'Playfair Display', serif; color: var(--gold); font-size: 2.5rem; line-height: 1; margin-bottom: 5px; }
    .stat-item p { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; }
    
    .page-section { padding: 6rem 5%; background-size: cover; background-position: center; background-attachment: fixed; position: relative; }
    .page-section::before { content: ''; position: absolute; inset: 0; background: rgba(255,255,255,0.92); z-index: 1; }
    .page-section .container { position: relative; z-index: 2; max-width: 1200px; margin: auto; }
    .dark-section { color: var(--white); }
    .dark-section::before { background: rgba(18, 63, 50, 0.92); }
    .dark-section .section-title { color: var(--gold); }
    
    .section-title { font-family: 'Playfair Display', serif; color: var(--g-dark); font-size: 2.2rem; margin-bottom: 3rem; text-align: center; }
    
    .grid-3 { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; }
    .card { background: var(--white); border-radius: 10px; overflow: hidden; box-shadow: var(--shadow); transition: 0.3s; border-top: 4px solid var(--gold); }
    .card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
    .card-img { width: 100%; height: 220px; object-fit: cover; }
    .card-body { padding: 1.5rem; }
    .card h4 { color: var(--g-dark); font-size: 1.2rem; margin-bottom: 0.5rem; }
    .card p { color: var(--text-light); font-size: 0.9rem; }
    .date-badge { color: var(--gold); font-weight: bold; font-size: 0.8rem; margin-bottom: 0.5rem; display: block; }
    
    .gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem; }
    .gallery img { width: 100%; height: 220px; object-fit: cover; border-radius: 8px; transition: 0.3s; cursor: pointer; }
    .gallery img:hover { transform: scale(1.03); }
    
    .about-box { max-width: 900px; margin: auto; background: var(--white); padding: 3rem; border-radius: 10px; box-shadow: var(--shadow); text-align: center; font-size: 1.05rem; color: var(--text-light); border-left: 5px solid var(--gold); }

    footer { background: var(--g-dark); color: var(--white); padding: 4rem 5% 2rem; }
    .footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; text-align: left; margin-bottom: 3rem; }
    .footer-logo { width: 80px; margin-bottom: 1rem; background: white; border-radius: 50%; padding: 5px; }
    .footer-col h4 { color: var(--gold); margin-bottom: 1rem; font-size: 1.1rem; }
    .footer-col p { opacity: 0.8; font-size: 0.9rem; margin-bottom: 0.5rem; }
    .footer-bottom { border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1.5rem; opacity: 0.6; font-size: 0.8rem; text-align: center; }
    
    .mobile-menu { display: none; font-size: 1.5rem; cursor: pointer; }
    
    @media (max-width: 900px) {
      nav ul { display: none; }
      .mobile-menu { display: block; }
      .stats-bar { grid-template-columns: 1fr 1fr; gap: 2rem; }
      .hero, .page-section { background-attachment: scroll; }
      .about-box { padding: 1.5rem; }
    }
  </style>
</head>
<body>

  <header>
    <div class="logo">
      <img src="assets/images/logo.png" alt="Logo" onerror="this.src='https://via.placeholder.com/40'">
      <?= htmlspecialchars($settings['school_name']) ?>
    </div>
    <nav>
      <ul>
        <li><a href="#ahabanza" class="nav-link" data-rw="Ahabanza" data-en="Home">Ahabanza</a></li>
        <li><a href="#amashuri" class="nav-link" data-rw="Amashuri" data-en="Programs">Amashuri</a></li>
        <li><a href="#ibyerekeye" class="nav-link" data-rw="Ibyerekeye" data-en="About">Ibyerekeye</a></li>
        <li><a href="#abakozi" class="nav-link" data-rw="Abakozi" data-en="Staff">Abakozi</a></li>
        <li><a href="#ibikorwa" class="nav-link" data-rw="Ibikorwa" data-en="Events">Ibikorwa</a></li>
        <li><a href="#amatangazo" class="nav-link" data-rw="Amatangazo" data-en="News">Amatangazo</a></li>
        <li><a href="#amafto" class="nav-link" data-rw="Amafoto" data-en="Gallery">Amafoto</a></li>
        <li><a href="#twandikire" class="nav-link" data-rw="Twandikire" data-en="Contact">Twandikire</a></li>
        <li>
          <div class="lang-switch">
            <button class="active" onclick="setLang('rw')">RW</button>
            <button onclick="setLang('en')">EN</button>
          </div>
        </li>
        <li><a href="login.php" style="color:var(--gold)" data-rw="Injira" data-en="Login">Injira</a></li>
      </ul>
    </nav>
    <div class="mobile-menu">☰</div>
  </header>

  <section class="hero" id="ahabanza" style="background-image: url('<?= htmlspecialchars($backgrounds['hero'] ?? '') ?>');">
    <div class="hero-content">
      <div class="badge" data-rw="<?= htmlspecialchars($settings['hero_badge']) ?>" data-en="GOVERNMENT AIDED SCHOOL"><?= htmlspecialchars($settings['hero_badge']) ?></div>
      <h1 data-rw="<?= htmlspecialchars($settings['hero_title']) ?>" data-en="Welcome to College Don Bosco de Rushaki"><?= htmlspecialchars($settings['hero_title']) ?></h1>
      <p data-rw="<?= htmlspecialchars($settings['hero_subtitle']) ?>" data-en="An O'Level and A'Level school providing quality education in Sciences and Art and Humanity."><?= htmlspecialchars($settings['hero_subtitle']) ?></p>
      <a href="#ibyerekeye" class="btn" data-rw="Menya Byinshi" data-en="Learn More">Menya Byinshi</a>
    </div>
  </section>

  <div class="stats-bar">
    <div class="stat-item"><h3>2</h3><p data-rw="Ibyiciro" data-en="Levels">Ibyiciro</p></div>
    <div class="stat-item"><h3>S1-S6</h3><p data-rw="Amashuri" data-en="Classes">Amashuri</p></div>
    <div class="stat-item"><h3>3</h3><p data-rw="Amashami" data-en="Streams">Amashami</p></div>
    <div class="stat-item"><h3>100%</h3><p data-rw="Ubwitange" data-en="Commitment">Ubwitange</p></div>
  </div>

  <section class="page-section" id="amashuri">
    <div class="container">
      <h2 class="section-title" data-rw="Amashuri dutanga" data-en="Our Programs">Amashuri dutanga</h2>
      <div class="grid-3">
        <div class="card">
          <div class="card-body">
            <h4 style="color: var(--gold); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;" data-rw="Ordinary Level" data-en="Ordinary Level">Ordinary Level</h4>
            <h4 data-rw="O'Level (S1 - S3)" data-en="O'Level (S1 - S3)">O'Level (S1 - S3)</h4>
            <p data-rw="Icyiciro cy'ibanze cy'amashuri yisumbuye, gitanga urufatiro rukomeye mu masomo yose. Harimo S1, S2, na S3." data-en="The foundation level of secondary education, providing a strong base in all subjects. Includes S1, S2, and S3.">Icyiciro cy'ibanze cy'amashuri yisumbuye, gitanga urufatiro rukomeye mu masomo yose. Harimo S1, S2, na S3.</p>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <h4 style="color: var(--gold); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;" data-rw="Advanced Level" data-en="Advanced Level">Advanced Level</h4>
            <h4 data-rw="A'Level (S4 - S6)" data-en="A'Level (S4 - S6)">A'Level (S4 - S6)</h4>
            <p data-rw="Icyiciro cy'amashuri yisumbuye gikomeye. Harimo S4, S5, na S6." data-en="The advanced secondary level. Includes S4, S5, and S6.">Icyiciro cy'amashuri yisumbuye gikomeye. Harimo S4, S5, na S6.</p>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <h4 style="color: var(--gold); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;" data-rw="Amashami (A'Level)" data-en="Streams (A'Level)">Amashami (A'Level)</h4>
            <h4 data-rw="Streams" data-en="Streams">Streams</h4>
            <ul style="margin-top: 0.5rem; color: var(--text-light); font-size: 0.9rem;">
              <li style="margin-bottom: 5px;" data-rw="→ Maths & Science Stream 1" data-en="→ Maths & Science Stream 1">→ Maths & Science Stream 1</li>
              <li style="margin-bottom: 5px;" data-rw="→ Science Stream 2" data-en="→ Science Stream 2">→ Science Stream 2</li>
              <li data-rw="→ Art and Humanity" data-en="→ Art and Humanity">→ Art and Humanity</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="page-section" id="ibyerekeye" style="background-image: url('<?= htmlspecialchars($backgrounds['about'] ?? '') ?>');">
    <div class="container">
      <h2 class="section-title" data-rw="Ibyerekeye Ikigo" data-en="About the School">Ibyerekeye Ikigo</h2>
      <div class="about-box">
        <p data-rw="<?= nl2br(htmlspecialchars($settings['about_text'])) ?>" data-en="Don Bosco College of Rushaki is located in Gicumbi District, Northern Province. It was founded to provide quality education to young people."><?= nl2br(htmlspecialchars($settings['about_text'])) ?></p>
      </div>
    </div>
  </section>

  <section class="page-section" id="abakozi" style="background-image: url('<?= htmlspecialchars($backgrounds['staff'] ?? '') ?>');">
    <div class="container">
      <h2 class="section-title" data-rw="Abayobozi n'Abakozi" data-en="Leaders and Staff">Abayobozi n'Abakozi</h2>
      <div class="grid-3">
        <?php foreach($staffs as $staff): ?>
        <div class="card" style="text-align: center;">
          <img src="<?= htmlspecialchars($staff['photo'] ?: 'assets/images/logo.png') ?>" alt="Staff" class="card-img" style="border-radius: 50%; width: 150px; height: 150px; margin: 2rem auto 0;">
          <div class="card-body">
            <h4><?= htmlspecialchars($staff['full_name']) ?></h4>
            <p style="color: var(--gold); font-weight: 700; font-size: 0.8rem; text-transform: uppercase;"><?= htmlspecialchars($staff['position']) ?></p>
            <p style="font-size: 0.8rem; margin-top: 5px;" data-rw="<?= htmlspecialchars($staff['category']) ?>" data-en="<?= htmlspecialchars($staff['category']) ?>"><?= htmlspecialchars($staff['category']) ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="page-section dark-section" id="ibikorwa" style="background-image: url('<?= htmlspecialchars($backgrounds['events'] ?? '') ?>');">
    <div class="container">
      <h2 class="section-title" data-rw="Ibikorwa" data-en="Events">Ibikorwa</h2>
      <div class="grid-3">
        <?php foreach($events as $event): ?>
        <div class="card">
          <?php if($event['image']): ?>
            <img src="<?= htmlspecialchars($event['image']) ?>" alt="Event" class="card-img">
          <?php endif; ?>
          <div class="card-body">
            <span class="date-badge"><?= date('d/m/Y', strtotime($event['event_date'])) ?></span>
            <h4><?= htmlspecialchars($event['title']) ?></h4>
            <p><?= htmlspecialchars($event['description']) ?></p>
            <p style="margin-top: 10px; font-size: 0.8rem; color: var(--g-light);">📍 <?= htmlspecialchars($event['location']) ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="page-section" id="amatangazo" style="background-image: url('<?= htmlspecialchars($backgrounds['announcements'] ?? '') ?>');">
    <div class="container">
      <h2 class="section-title" data-rw="Amatangazo" data-en="News & Announcements">Amatangazo</h2>
      <div class="grid-3">
        <?php foreach($news as $item): ?>
        <div class="card">
          <div class="card-body" style="border-left: 4px solid var(--gold);">
            <span class="date-badge"><?= date('d/m/Y', strtotime($item['date_posted'])) ?></span>
            <h4><?= htmlspecialchars($item['title']) ?></h4>
            <p><?= nl2br(htmlspecialchars($item['content'])) ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="page-section" id="amafto">
    <div class="container">
      <h2 class="section-title" data-rw="Amafoto y'Ikigo" data-en="School Gallery">Amafoto y'Ikigo</h2>
      <div class="gallery">
        <?php foreach($galleries as $img): ?>
          <img src="<?= htmlspecialchars($img['image_path']) ?>" alt="Gallery">
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <footer id="twandikire" class="page-section dark-section" style="background-image: url('<?= htmlspecialchars($backgrounds['contact'] ?? '') ?>'); padding: 4rem 5% 2rem;">
    <div class="container" style="position: relative; z-index: 2;">
      <div class="footer-grid">
        <div class="footer-col">
          <img src="assets/images/logo.png" alt="Logo" class="footer-logo" onerror="this.src='https://via.placeholder.com/80'">
          <h4><?= htmlspecialchars($settings['school_name']) ?></h4>
          <p data-rw="Ikigo cya O'Level na A'Level gitanga uburezi bufite ireme mu mashami ya Sciences na Art and Humanity." data-en="An O'Level and A'Level school providing quality education in Sciences and Art and Humanity.">Ikigo cya O'Level na A'Level gitanga uburezi bufite ireme mu mashami ya Sciences na Art and Humanity.</p>
        </div>
        <div class="footer-col">
          <h4 data-rw="Ihuza" data-en="Links">Ihuza</h4>
          <ul>
            <li><a href="#ahabanza" data-rw="Ahabanza" data-en="Home">Ahabanza</a></li>
            <li><a href="#amashuri" data-rw="Amashuri" data-en="Programs">Amashuri</a></li>
            <li><a href="#ibyerekeye" data-rw="Ibyerekeye" data-en="About">Ibyerekeye</a></li>
            <li><a href="login.php" data-rw="Injira (Admin/Editor)" data-en="Login (Admin/Editor)">Injira (Admin/Editor)</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4 data-rw="Twandikire" data-en="Contact">Twandikire</h4>
          <p>📍 <?= htmlspecialchars($settings['address']) ?></p>
          <p>📞 <?= htmlspecialchars($settings['phone']) ?></p>
          <p>✉️ <?= htmlspecialchars($settings['email']) ?></p>
        </div>
        <div class="footer-col">
          <h4 data-rw="Amasaha y'Akazi" data-en="Working Hours">Amasaha y'Akazi</h4>
          <p><?= htmlspecialchars($settings['working_hours']) ?></p>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($settings['school_name']) ?>. <span data-rw="Uburenganzira bwose burafitwe." data-en="All rights reserved.">Uburenganzira bwose burafitwe.</span></p>
      </div>
    </div>
  </footer>

  <script>
    function setLang(lang) {
      document.querySelectorAll('.lang-switch button').forEach(btn => {
        btn.classList.remove('active');
        if (btn.textContent === lang.toUpperCase()) {
          btn.classList.add('active');
        }
      });

      document.querySelectorAll('[data-rw]').forEach(el => {
        const text = el.getAttribute('data-' + lang);
        if (text) {
          if (text.includes('<br>') || text.includes('<span')) {
            el.innerHTML = text;
          } else {
            el.textContent = text;
          }
        }
      });

      document.documentElement.lang = lang;
    }

    const sections = document.querySelectorAll('section, footer');
    const navLinks = document.querySelectorAll('.nav-link');

    window.addEventListener('scroll', () => {
      let current = '';
      sections.forEach(section => {
        const sectionTop = section.offsetTop;
        if (scrollY >= sectionTop - 150) {
          current = section.getAttribute('id');
        }
      });

      navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === '#' + current) {
          link.classList.add('active');
        }
      });
    });
  </script>
</body>
</html>