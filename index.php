<?php
include __DIR__ . '/backend/config/db_connect.php';

// Fetch all posts grouped by category
$data = [
  'Home' => [],
  'About' => [],
  'Services' => []
];

$sql = "SELECT posts.*, post_categories.name AS category_name
        FROM posts
        JOIN post_categories ON posts.category_id = post_categories.id
        ORDER BY posts.created_at DESC";

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $cat = $row['category_name'];
    if (isset($data[$cat])) {
        $data[$cat][] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Software Company</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
  /* HERO ORBIT */
.logo-orbit-wrap {
  position: relative;
  width: 220px;
  height: 220px;
  margin: 0 auto 36px;
}

.orbit-ring-anim {
  position: absolute;
  inset: 0;
  animation: spin 4s linear infinite;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to   { transform: rotate(360deg); }
}

.logo-inner-circle {
  position: absolute;
  inset: 16px;
  border-radius: 50%;
  background: #f5f5f5;
  border: 1px solid #eee;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.logo-inner-circle img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 50%;
}

.logo-fallback { font-size: 2rem; color: #555; }

/* HERO TEXT */
.hero-tag {
  display: block;
  font-size: 11px;
  letter-spacing: 2.5px;
  text-transform: uppercase;
  color: #888;
  margin-bottom: 10px;
}

.hero h1 em { color: #1D9E75; font-style: italic; }

.hero-stats {
  display: flex;
  justify-content: center;
  gap: 28px;
  margin: 28px 0;
  align-items: center;
}

.stat { text-align: center; }
.stat strong { display: block; font-size: 1.4rem; color: #111; }
.stat small   { font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: 1px; }
.stat-div     { width: 1px; height: 36px; background: #ddd; }

.hero-ctas { display: flex; gap: 12px; justify-content: center; }
.hero-ctas button { padding: 11px 26px; border-radius: 6px; border: none; cursor: pointer; font-size: 14px; background: #1D9E75; color: #fff; }
.hero-ctas .btn-outline { background: transparent; border: 1px solid #ccc; color: #333; }


/* RESET */
*{margin:0;padding:0;box-sizing:border-box;font-family:Segoe UI,sans-serif;}
body{background:#fff;color:#111;}

/* NAV */
nav{
  position:fixed;width:100%;background:#fff;
  border-bottom:1px solid #eee;z-index:1000;
}
.nav-inner{
  max-width:1200px;margin:auto;
  display:flex;justify-content:space-between;
  align-items:center;padding:15px 20px;
}
.nav-links{display:flex;gap:20px;list-style:none;}
.nav-links a{cursor:pointer;text-decoration:none;color:#333;}

/* PAGE */
.page{display:none;padding-top:80px;}
.page.active{display:block;}

/* HERO */
.hero{text-align:center;padding:100px 20px 60px;}
.hero h1{font-size:3rem;margin-bottom:15px;}
.hero p{color:#555;margin-bottom:40px;}

/* SECTION */
.section{padding:80px 20px;max-width:1200px;margin:auto;}
.grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
  gap:25px;
}
.section-eyebrow {
  font-size: 11px;
  letter-spacing: 2.5px;
  text-transform: uppercase;
  color: #1D9E75;
  margin-bottom: 6px;
  font-weight: 600;
}

/* CARD */
.card{
  border:1px solid #eee;
  padding:25px;
  border-radius:12px;
  transition:.3s;
}
.card:hover{transform:translateY(-5px);}

/* ABOUT SPLIT */
.about-split{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:40px;
  align-items:center;
  margin-bottom:50px;
}
.about-img img{
  width:100%;
  height:300px;
  object-fit:cover;
  border-radius:12px;
}

/* SERVICES */
.service-card{
  display:flex;
  gap:20px;
  border:1px solid #eee;
  padding:20px;
  border-radius:12px;
}
.service-img img{
  width:120px;
  height:100px;
  object-fit:cover;
  border-radius:10px;
}

/* CONTACT */
input,textarea{
  width:100%;
  padding:12px;
  margin:10px 0;
  border:1px solid #ddd;
  border-radius:8px;
}
button{
  padding:12px;
  background:#0077ff;
  color:#fff;
  border:none;
  border-radius:6px;
}

footer{text-align:center;padding:20px;border-top:1px solid #eee;}
/* ── SERVICES ───────────────────────────────── */
.svc-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 22px;
}

.svc-card {
  border: 1px solid #eee;
  border-radius: 16px;
  padding: 32px 20px 24px;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  transition: border-color .25s, transform .25s;
  background: #fff;
}
.svc-card:hover { border-color: #1D9E75; transform: translateY(-5px); }

.svc-icon-wrap {
  width: 68px; height: 68px;
  border-radius: 50%;
  background: #E1F5EE;
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 18px;
}
.svc-icon-wrap svg {
  width: 28px; height: 28px;
  stroke: #1D9E75; fill: none;
  stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
}
.svc-card:hover .svc-icon-wrap { animation: icon-pulse .6s ease-out; }
@keyframes icon-pulse {
  0%   { box-shadow: 0 0 0 0 rgba(29,158,117,.35); }
  100% { box-shadow: 0 0 0 14px rgba(29,158,117,0); }
}

.svc-name { font-size: 16px; font-weight: 600; color: #111; margin-bottom: 10px; }
.svc-desc { font-size: 14px; color: #666; line-height: 1.65; margin-bottom: 22px; flex: 1; }

.svc-btn {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: 13px; font-weight: 500;
  color: #1D9E75;
  border: 1px solid #1D9E75;
  border-radius: 20px;
  padding: 8px 20px;
  cursor: pointer; background: transparent;
  transition: background .2s, color .2s, gap .2s;
  text-decoration: none;
}
.svc-btn:hover { background: #1D9E75; color: #fff; gap: 10px; }
.svc-btn .arr { transition: transform .2s; display: inline-block; }
.svc-btn:hover .arr { transform: translateX(3px); }

/* ── ABOUT ───────────────────────────────────── */
.about-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 24px;
}

.about-card {
  display: grid;
  grid-template-columns: 1fr 1fr;
  border: 1px solid #eee;
  border-radius: 16px;
  overflow: hidden;
  transition: border-color .25s, transform .25s;
}
.about-card:hover { border-color: #1D9E75; transform: translateY(-4px); }

.about-img-side { overflow: hidden; min-height: 200px; }
.about-img-side img {
  width: 100%; height: 100%;
  object-fit: cover; display: block;
  transition: transform .4s;
}
.about-card:hover .about-img-side img { transform: scale(1.05); }

.about-content-side {
  padding: 24px 20px;
  display: flex; flex-direction: column;
  justify-content: center; gap: 10px;
}
.about-tag-label {
  font-size: 10px; letter-spacing: 2px;
  text-transform: uppercase; color: #1D9E75; font-weight: 600;
}
.about-card-title { font-size: 15px; font-weight: 600; color: #111; line-height: 1.35; }
.about-card-desc  { font-size: 13px; color: #666; line-height: 1.65; }

.about-read-btn {
  margin-top: 6px;
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 13px; font-weight: 500; color: #1D9E75;
  background: none; border: none; cursor: pointer; padding: 0;
  transition: gap .2s;
}
.about-read-btn:hover { gap: 10px; }
.about-read-btn .arr { display: inline-block; transition: transform .2s; }
.about-read-btn:hover .arr { transform: translateX(3px); }

/* ── RESPONSIVE ──────────────────────────────── */
@media (max-width: 600px) {
  .about-card { grid-template-columns: 1fr; }
  .about-img-side { min-height: 180px; }
  .svc-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 400px) {
  .svc-grid { grid-template-columns: 1fr; }
}

</style>
</head>

<body>

<!-- NAV -->
<nav>
  <div class="nav-inner">
    <h3>Company</h3>
    <ul class="nav-links">
      <li><a onclick="showPage('home')">Home</a></li>
      <li><a onclick="showPage('about')">About</a></li>
      <li><a onclick="showPage('services')">Services</a></li>
      <li><a onclick="showPage('contact')">Contact</a></li>
    </ul>
  </div>
</nav>

<!-- HOME -->
<div id="page-home" class="page active">

  <div class="hero">

    <!-- LOGO ORBIT CIRCLE -->
    <div class="logo-orbit-wrap">
      <div class="orbit-ring-anim">
        <svg viewBox="0 0 220 220" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <linearGradient id="arcGrad" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#1D9E75" stop-opacity="1"/>
              <stop offset="40%" stop-color="#5DCAA5" stop-opacity="0.5"/>
              <stop offset="100%" stop-color="#E1F5EE" stop-opacity="0"/>
            </linearGradient>
          </defs>
          <circle cx="110" cy="110" r="104"
            fill="none" stroke="url(#arcGrad)"
            stroke-width="3" stroke-linecap="round"
            stroke-dasharray="490 164"/>
          <circle cx="110" cy="6" r="4" fill="#1D9E75"/>
        </svg>
      </div>
      <div class="logo-inner-circle">
        <?php
          // Pull company logo from DB — store as a "Home" post with image + type='logo'
          // Or a dedicated settings table. Example using first Home post image:
          $logo = !empty($data['Home'][0]['image']) ? $data['Home'][0]['image'] : null;
        ?>
        <?php if($logo): ?>
          <img src="backend/posts/uploads/<?= htmlspecialchars($logo) ?>" alt="Company Logo">
        <?php else: ?>
          <span class="logo-fallback">Co.</span>
        <?php endif; ?>
      </div>
    </div>

    <!-- HEADLINE & DETAILS -->
    <span class="hero-tag">Software Solutions</span>
    <h1>We Build <em>Digital Solutions</em></h1>
    <p>Modern web and mobile systems designed to grow your business.</p>

    <div class="hero-stats">
      <div class="stat"><strong>120+</strong><small>Projects</small></div>
      <div class="stat-div"></div>
      <div class="stat"><strong>8 yrs</strong><small>Experience</small></div>
      <div class="stat-div"></div>
      <div class="stat"><strong>98%</strong><small>Satisfaction</small></div>
    </div>

    <div class="hero-ctas">
      <button onclick="showPage('services')">View our work</button>
      <button class="btn-outline" onclick="showPage('contact')">Get in touch</button>
    </div>

  </div>

  <!-- REST OF HOME POSTS (below hero) -->
  <div class="section">
    <div class="grid">
      <?php foreach(array_slice($data['Home'], 1) as $post): ?>
        <div class="card">
          <h3><?= htmlspecialchars($post['title']) ?></h3>
          <p><?= htmlspecialchars($post['excerpt']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

</div>

<!-- ABOUT -->
<div id="page-about" class="page">
  <div class="section">
    <p class="section-eyebrow">Who we are</p>
    <h2>About us</h2>

    <?php if (empty($data['About'])): ?>
      <p style="color:#888;">No content added yet.</p>
    <?php else: ?>

    <div class="about-grid">
      <?php foreach ($data['About'] as $post): ?>
        <div class="about-card">

          <div class="about-img-side">
            <?php if (!empty($post['image'])): ?>
              <img
                src="backend/posts/uploads/<?= htmlspecialchars($post['image']) ?>"
                alt="<?= htmlspecialchars($post['title']) ?>">
            <?php else: ?>
              <div style="width:100%;height:100%;min-height:200px;background:#f5f5f5;
                          display:flex;align-items:center;justify-content:center;">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none"
                     stroke="#ccc" stroke-width="1.5" stroke-linecap="round">
                  <rect x="3" y="3" width="18" height="18" rx="2"/>
                  <circle cx="8.5" cy="8.5" r="1.5"/>
                  <path d="M21 15l-5-5L5 21"/>
                </svg>
              </div>
            <?php endif; ?>
          </div>

          <div class="about-content-side">
            <span class="about-tag-label">About us</span>
            <div class="about-card-title"><?= htmlspecialchars($post['title']) ?></div>
            <div class="about-card-desc"><?= htmlspecialchars($post['description']) ?></div>
            <button class="about-read-btn"
                    onclick="window.location='post.php?category=About&id=<?= (int)$post['id'] ?>'">
              Read more <span class="arr">→</span>
            </button>
          </div>

        </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

  </div>
</div>

<!-- SERVICES -->
<div id="page-services" class="page">
  <div class="section">
    <p class="section-eyebrow">What we do</p>
    <h2>Our services</h2>

    <?php if (empty($data['Services'])): ?>
      <p style="color:#888;">No services added yet.</p>
    <?php else: ?>

    <div class="svc-grid">
      <?php foreach ($data['Services'] as $post):
        $title_lower = strtolower($post['title']);
        if      (str_contains($title_lower, 'web'))      $icon = '<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>';
        elseif  (str_contains($title_lower, 'mobile') || str_contains($title_lower, 'app')) $icon = '<rect x="5" y="2" width="14" height="20" rx="2"/><circle cx="12" cy="17" r="1" fill="#1D9E75"/>';
        elseif  (str_contains($title_lower, 'data') || str_contains($title_lower, 'analyt')) $icon = '<path d="M18 20V10M12 20V4M6 20v-6"/>';
        elseif  (str_contains($title_lower, 'secur') || str_contains($title_lower, 'cyber'))  $icon = '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>';
        elseif  (str_contains($title_lower, 'cloud'))    $icon = '<path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/>';
        elseif  (str_contains($title_lower, 'design') || str_contains($title_lower, 'ui'))  $icon = '<circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/>';
        elseif  (str_contains($title_lower, 'support') || str_contains($title_lower, 'help')) $icon = '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>';
        else    $icon = '<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>';
      ?>
        <div class="svc-card">
          <div class="svc-icon-wrap">
            <svg viewBox="0 0 24 24"><?= $icon ?></svg>
          </div>
          <div class="svc-name"><?= htmlspecialchars($post['title']) ?></div>
          <div class="svc-desc"><?= htmlspecialchars($post['excerpt']) ?></div>
          <a href="post.php?category=Services&id=<?= (int)$post['id'] ?>" class="svc-btn">
            Read more <span class="arr">→</span>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

  </div>
</div>

<!-- CONTACT -->
<div id="page-contact" class="page">
  <div class="section">
    <h2>Contact</h2>

    <input type="text" placeholder="Name">
    <input type="email" placeholder="Email">
    <textarea placeholder="Message"></textarea>
    <button>Send Message</button>

  </div>
</div>

<footer>
  <p>© 2026 Company</p>
</footer>

<script>
function showPage(page){
  document.querySelectorAll('.page').forEach(p=>p.classList.remove('active'));
  document.getElementById('page-'+page).classList.add('active');
  window.scrollTo(0,0);
}
</script>

</body>
</html>