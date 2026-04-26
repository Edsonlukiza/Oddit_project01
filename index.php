<?php

include __DIR__ . '/backend/config/db_connect.php';



// Fetch all posts grouped by category

$data = [

  'Home' => [],

  'About' => [],

  'Service' => [],

  'Contact' => []

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


// Fetch all settings
$settings_sql = "SELECT setting_key, setting_value FROM site_settings";
$settings_result = mysqli_query($conn, $settings_sql);

$site = [];
while ($setting = mysqli_fetch_assoc($settings_result)) {
    $site[$setting['setting_key']] = $setting['setting_value'];
}

// Fallback values if a key is missing (prevents errors)
$site['site_name'] = $site['site_name'] ?? 'Company Name';
$site['company_email'] = $site['company_email'] ?? 'info@example.com';


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

  animation: float 3s ease-in-out infinite;

}

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-15px); }
}

.orbit-ring-anim {

  position: absolute;

  inset: 0;

  animation: spin 8s linear infinite;

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

  border: 2px solid #1D9E75;

  display: flex;

  align-items: center;

  justify-content: center;

  overflow: hidden;

  box-shadow: 0 0 30px rgba(29, 158, 117, 0.15), inset 0 0 20px rgba(29, 158, 117, 0.05);

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

  color: #1D9E75;

  margin-bottom: 10px;

  font-weight: 600;

  animation: slideUp 0.8s ease-out 0.1s both;

}



.hero h1 em { color: #1D9E75; font-style: italic; }



.hero-stats {

  display: flex;

  justify-content: center;

  gap: 28px;

  margin: 28px 0;

  align-items: center;

  animation: slideUp 0.8s ease-out 0.4s both;

}



.stat { text-align: center; }

.stat strong { display: block; font-size: 1.4rem; color: #111; font-weight: 700; }

.stat small   { font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: 1px; }

.stat-div     { width: 1px; height: 36px; background: linear-gradient(to bottom, #ddd, transparent); }



.hero-ctas { 
  display: flex; 
  gap: 12px; 
  justify-content: center;
  animation: slideUp 0.8s ease-out 0.5s both;
}

.hero-ctas button { 
  padding: 13px 32px; 
  border-radius: 8px; 
  border: none; 
  cursor: pointer; 
  font-size: 14px; 
  background: #1D9E75; 
  color: #fff; 
  font-weight: 600;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(29, 158, 117, 0.3);
}

.hero-ctas button:hover { 
  background: #0F6E56; 
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(29, 158, 117, 0.5);
}

.hero-ctas button:active {
  transform: translateY(-1px);
}

.hero-ctas .btn-outline { 
  background: transparent; 
  border: 2px solid #1D9E75; 
  color: #1D9E75;
  box-shadow: none;
}

.hero-ctas .btn-outline:hover { 
  background: #1D9E75; 
  color: #fff;
  box-shadow: 0 8px 25px rgba(29, 158, 117, 0.5);
}





/* RESET */

*{margin:0;padding:0;box-sizing:border-box;font-family:Segoe UI,sans-serif;}

body{background:#fff;color:#111;}



/* NAV */

nav{

  position:fixed;width:100%;background:#fff;

  border-bottom: 1px solid #e0e0e0;
  z-index:1000;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
  transition: all 0.3s ease;
}

nav li a {
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
}

nav li a:hover{
  color:#1D9E75;
}

nav li a::after {
  content: '';
  position: absolute;
  bottom: -5px;
  left: 0;
  width: 0;
  height: 2px;
  background: #1D9E75;
  transition: width 0.3s ease;
}

nav li a:hover::after {
  width: 100%;
}

.nav-inner{

  max-width:1200px;margin:auto;

  display:flex;justify-content:space-between;

  align-items:center;padding:15px 20px;

}

.nav-links{display:flex;gap:20px;list-style:none;}

.nav-links a{text-decoration:none;color:#333;}



/* PAGE */

.page{display:none;padding-top:80px;}

.page.active{display:block;}



/* HERO */

.hero{
  text-align:center;
  padding:100px 20px 60px;
  animation: fadeInDown 0.8s ease-out;
}

.hero h1{
  font-size:3rem;
  margin-bottom:15px;
  font-weight: 700;
  letter-spacing: -1px;
  animation: slideUp 0.8s ease-out 0.2s both;
}

.hero p{
  color:#555;
  margin-bottom:40px;
  font-size: 1.1rem;
  animation: slideUp 0.8s ease-out 0.3s both;
}

@keyframes fadeInDown {
  from { opacity: 0; transform: translateY(-30px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}



/* SECTION */

.section{
  padding: 80px 20px;
  max-width: 1200px;
  margin: auto;
  animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.grid{

  display:grid;

  grid-template-columns:repeat(auto-fit,minmax(260px,1fr));

  gap:25px;

  animation: staggerIn 0.6s ease-out;

}

@keyframes staggerIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.grid > * {
  animation: slideUp 0.6s ease-out;
}

.grid > *:nth-child(1) { animation-delay: 0.1s; }
.grid > *:nth-child(2) { animation-delay: 0.15s; }
.grid > *:nth-child(3) { animation-delay: 0.2s; }
.grid > *:nth-child(n+4) { animation-delay: 0.25s; }

.section-eyebrow {

  font-size: 11px;

  letter-spacing: 2.5px;

  text-transform: uppercase;

  color: #1D9E75;

  margin-bottom: 6px;

  font-weight: 600;

  display: inline-block;

  animation: slideUp 0.6s ease-out;

}

.section h2 {
  font-size: 2.2rem;
  font-weight: 700;
  margin-bottom: 14px;
  animation: slideUp 0.6s ease-out 0.1s both;
  letter-spacing: -0.5px;
}

.section > p {
  animation: slideUp 0.6s ease-out 0.2s both;
}



/* CARD */

.card{
  border: 1px solid #e0e0e0;
  padding: 28px;
  border-radius: 12px;
  transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  background: #fff;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  position: relative;
  overflow: hidden;
}

.card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, #1D9E75, #5DCAA5);
  transform: scaleX(0);
  transform-origin: left;
  transition: transform 0.35s ease;
}

.card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 28px rgba(29, 158, 117, 0.15);
  border-color: #1D9E75;
}

.card:hover::before {
  transform: scaleX(1);
}

.card h3 {
  color: #111;
  font-weight: 600;
  margin-bottom: 12px;
  font-size: 16px;
}

.card p {
  color: #555;
  line-height: 1.6;
  font-size: 14px;
}



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

  padding: 13px 14px;

  margin: 10px 0;

  border: 1.5px solid #e0e0e0;

  border-radius: 8px;

  font-family: Segoe UI, sans-serif;

  font-size: 14px;

  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);

  background: #fafafa;

}

input:focus,
textarea:focus {
  outline: none;
  border-color: #1D9E75;
  background: #fff;
  box-shadow: 0 0 0 4px rgba(29, 158, 117, 0.1), inset 0 0 0 1px rgba(29, 158, 117, 0.05);
}

button{

  padding: 13px 32px;

  background: linear-gradient(135deg, #1D9E75, #0F6E56);

  color: #fff;

  border: none;

  border-radius: 8px;

  cursor: pointer;

  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);

  font-weight: 600;

  font-size: 14px;

  box-shadow: 0 4px 15px rgba(29, 158, 117, 0.3);

  position: relative;

  overflow: hidden;

}

button::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  transition: left 0.5s ease;
}

button:hover::before {
  left: 100%;
}

button:hover {
  background: linear-gradient(135deg, #0F6E56, #0a4a3a);
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(29, 158, 117, 0.5);
}

button:active {
  transform: translateY(-1px);
}

#formMessage {
  margin-bottom: 15px;
}

footer{text-align:center;padding:20px;border-top:1px solid #eee;}


/* ── SERVICES ───────────────────────────────── */

.svc-grid {

  display: grid;

  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));

  gap: 22px;

  animation: staggerIn 0.6s ease-out;

}

.svc-grid > * {
  animation: slideUp 0.6s ease-out;
}

.svc-grid > *:nth-child(1) { animation-delay: 0.1s; }
.svc-grid > *:nth-child(2) { animation-delay: 0.15s; }
.svc-grid > *:nth-child(3) { animation-delay: 0.2s; }
.svc-grid > *:nth-child(4) { animation-delay: 0.25s; }
.svc-grid > *:nth-child(5) { animation-delay: 0.3s; }
.svc-grid > *:nth-child(n+6) { animation-delay: 0.35s; }



.svc-card {

  border: 1px solid #e0e0e0;

  border-radius: 16px;

  padding: 32px 20px 24px;

  display: flex;

  flex-direction: column;

  align-items: center;

  text-align: center;

  transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);

  background: #fff;

  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);

  position: relative;

  overflow: hidden;

}

.svc-card::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, #1D9E75, #5DCAA5);
  transform: scaleX(0);
  transform-origin: center;
  transition: transform 0.35s ease;
}

.svc-card:hover { 
  border-color: #1D9E75; 
  transform: translateY(-10px);
  box-shadow: 0 16px 32px rgba(29, 158, 117, 0.18);
}

.svc-card:hover::after {
  transform: scaleX(1);
}

.svc-icon-wrap {

  width: 68px; height: 68px;

  border-radius: 50%;

  background: linear-gradient(135deg, #E1F5EE, #D4F1E8);

  display: flex; align-items: center; justify-content: center;

  margin-bottom: 18px;

  transition: all 0.35s ease;

}

.svc-icon-wrap svg {

  width: 28px; height: 28px;

  stroke: #1D9E75; fill: none;

  stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;

}

.svc-card:hover .svc-icon-wrap { 
  transform: scale(1.15) rotate(5deg);
  background: linear-gradient(135deg, #1D9E75, #0F6E56);
}

.svc-card:hover .svc-icon-wrap svg {
  stroke: #fff;
  animation: icon-bounce 0.6s ease-out;
}

@keyframes icon-bounce {

  0% { transform: scale(1); }
  50% { transform: scale(1.2); }
  100% { transform: scale(1); }

}

.svc-name { 
  font-size: 16px; 
  font-weight: 600; 
  color: #111; 
  margin-bottom: 10px; 
  transition: color 0.3s ease;
}

.svc-card:hover .svc-name {
  color: #1D9E75;
}

.svc-desc { font-size: 14px; color: #666; line-height: 1.65; margin-bottom: 22px; flex: 1; }



.svc-btn {

  display: inline-flex; align-items: center; gap: 6px;

  font-size: 13px; font-weight: 600;

  color: #1D9E75;

  border: 2px solid #1D9E75;

  border-radius: 20px;

  padding: 9px 22px;

  cursor: pointer; background: transparent;

  transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);

  text-decoration: none;

  position: relative;

  overflow: hidden;

}

.svc-btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: #1D9E75;
  z-index: -1;
  transition: left 0.35s ease;
}

.svc-btn:hover { 
  background: #1D9E75; 
  color: #fff; 
  gap: 10px;
  box-shadow: 0 4px 15px rgba(29, 158, 117, 0.4);
  transform: translateY(-2px);
}

.svc-btn .arr { 
  transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  display: inline-block;
}

.svc-btn:hover .arr { 
  transform: translateX(4px) scale(1.2);
}



/* ── ABOUT ───────────────────────────────────── */

.about-grid {

  display: grid;

  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));

  gap: 24px;

  animation: staggerIn 0.6s ease-out;

}

.about-grid > * {
  animation: slideUp 0.6s ease-out;
}

.about-grid > *:nth-child(1) { animation-delay: 0.1s; }
.about-grid > *:nth-child(2) { animation-delay: 0.15s; }
.about-grid > *:nth-child(3) { animation-delay: 0.2s; }



.about-card {

  display: grid;

  grid-template-columns: 1fr 1fr;

  border: 1px solid #e0e0e0;

  border-radius: 16px;

  overflow: hidden;

  transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);

  background: #fff;

  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);

}

.about-card:hover { 
  border-color: #1D9E75; 
  transform: translateY(-8px);
  box-shadow: 0 16px 32px rgba(29, 158, 117, 0.15);
}



.about-img-side { 
  overflow: hidden; 
  min-height: 200px;
  position: relative;
}

.about-img-side img {

  width: 100%; height: 100%;

  object-fit: cover; display: block;

  transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);

}

.about-card:hover .about-img-side img { 
  transform: scale(1.08) rotate(1deg);
}



.about-content-side {

  padding: 24px 20px;

  display: flex; flex-direction: column;

  justify-content: center; gap: 10px;

}

.about-tag-label {

  font-size: 10px; letter-spacing: 2px;

  text-transform: uppercase; color: #1D9E75; font-weight: 600;

}

.about-card-title { 
  font-size: 15px; 
  font-weight: 600; 
  color: #111; 
  line-height: 1.35;
  transition: color 0.3s ease;
}

.about-card:hover .about-card-title {
  color: #1D9E75;
}

.about-card-desc  { 
  font-size: 13px; 
  color: #666; 
  line-height: 1.65;
  transition: color 0.3s ease;
}

.about-card:hover .about-card-desc {
  color: #555;
}

.about-read-btn {

  margin-top: 6px;

  display: inline-flex; align-items: center; gap: 5px;

  font-size: 13px; font-weight: 500; color: #1D9E75;

  background: none; border: none; cursor: pointer; padding: 0;

  transition: all 0.3s ease;

  text-decoration: none;

}

.about-read-btn:hover { 
  gap: 10px;
  color: #0F6E56;
}

.about-read-btn .arr { 
  display: inline-block; 
  transition: transform 0.3s ease;
}

.about-read-btn:hover .arr { 
  transform: translateX(3px) scale(1.2);
}
.main-footer {
    background: linear-gradient(135deg, #1a1a1a, #0f0f12);
    color: #ffffff;
    padding: 60px 20px 30px;
    margin-top: auto;
    border-top: 3px solid #1D9E75;
    position: relative;
    overflow: hidden;
}

.main-footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, #1D9E75, transparent);
    opacity: 0.5;
}

.footer-inner {
    max-width: 1200px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

.footer-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-bottom: 40px;
    animation: fadeInUp 0.8s ease-out 0.2s both;
}

.footer-col h3, .footer-col h4 {
    color: #1D9E75;
    margin-bottom: 20px;
    font-size: 1.2rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.footer-col:hover h3,
.footer-col:hover h4 {
    color: #00ffb4;
    transform: translateX(3px);
}

.footer-col p, .footer-col li {
    font-size: 14px;
    color: #ccc;
    line-height: 1.8;
    margin-bottom: 10px;
}

.footer-col ul { list-style: none; padding: 0; }

.footer-col a {
    color: #ccc;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    display: inline-block;
}

.footer-col a::before {
  content: '';
  position: absolute;
  bottom: -3px;
  left: 0;
  width: 0;
  height: 2px;
  background: #1D9E75;
  transition: width 0.3s ease;
}

.footer-col a:hover {
    color: #1D9E75;
}

.footer-col a:hover::before {
  width: 100%;
}

.footer-col p a {
    display: inline-block;
    transition: all 0.3s ease;
}

.footer-col p a:hover {
    color: #00ffb4;
    transform: translateX(2px);
}

.footer-col p i {
    margin-right: 8px;
    color: #1D9E75;
    transition: all 0.3s ease;
}

.footer-bottom {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid rgba(255,255,255,0.1);
    font-size: 12px;
    color: rgba(255,255,255,0.5);
    animation: fadeInUp 0.8s ease-out 0.3s both;
}



/* ── RESPONSIVE ──────────────────────────────── */

@media (max-width: 600px) {

  .about-card { grid-template-columns: 1fr; }

  .about-img-side { min-height: 180px; }

  .svc-grid { grid-template-columns: 1fr 1fr; }
  
  .hero h1 { font-size: 2rem; }
  .hero p { font-size: 1rem; }
  
  .hero-stats {
    flex-direction: column;
    gap: 16px;
  }
  
  .stat-div {
    display: none;
  }

}

@media (max-width: 400px) {

  .svc-grid { grid-template-columns: 1fr; }
  
  .hero h1 { font-size: 1.5rem; }
  .hero-ctas {
    flex-direction: column;
    width: 100%;
  }
  
  .hero-ctas button {
    width: 100%;
  }

}



</style>

</head>



<body>



<!-- NAV -->

<nav>

  <div class="nav-inner">

    <h3>Oddity Tech Company</h3>

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

      <?php

      $stats = include __DIR__ . '/backend/config/stats.php';

      foreach ($stats as $index => $stat):

      ?>

        <?php if ($index > 0): ?>

          <div class="stat-div"></div>

        <?php endif; ?>

        <div class="stat">

          <strong><?= htmlspecialchars($stat['number']) ?></strong>

          <small><?= htmlspecialchars($stat['label']) ?></small>

        </div>

      <?php endforeach; ?>

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



    <?php if (empty($data['Service'])): ?>

      <p style="color:#888;">No services added yet.</p>

    <?php else: ?>



    <div class="svc-grid">

      <?php foreach ($data['Service'] as $post):

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

          <a href="post.php?category=Service&id=<?= (int)$post['id'] ?>" class="svc-btn">

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

    <p class="section-eyebrow">Get in touch</p>

    <h2>Contact Us</h2>
    
    <p style="margin-bottom: 30px; color: #555; max-width: 600px;">Have questions or want to discuss a project? Send us a message and we'll respond as soon as possible.</p>

    <form id="contactForm" style="max-width: 600px;">

      <div id="formMessage"></div>

      <input type="text" id="name" name="name" placeholder="Your Name" required>

      <input type="email" id="email" name="email" placeholder="Your Email" required>

      <textarea id="message" name="message" placeholder="Your Message" rows="6" required></textarea>

      <button type="submit">Send Message</button>

    </form>

  </div>

</div>

<script>

function showPage(page){

  document.querySelectorAll('.page').forEach(p=>p.classList.remove('active'));

  document.getElementById('page-'+page).classList.add('active');

  window.scrollTo(0,0);

}

// Contact Form Handler
document.addEventListener('DOMContentLoaded', function() {
  const contactForm = document.getElementById('contactForm');
  
  if (contactForm) {
    contactForm.addEventListener('submit', async function(e) {
      e.preventDefault();
      
      const name = document.getElementById('name').value;
      const email = document.getElementById('email').value;
      const message = document.getElementById('message').value;
      const formMessage = document.getElementById('formMessage');
      
      // Clear previous messages
      formMessage.innerHTML = '';
      
      try {
        const response = await fetch('backend/handlers/contact_handler.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams({
            name: name,
            email: email,
            message: message
          })
        });
        
        const result = await response.json();
        
        if (result.success) {
          formMessage.innerHTML = `<div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; border: 1px solid #c3e6cb; margin-bottom: 15px;">${result.message}</div>`;
          contactForm.reset();
        } else {
          const errorHtml = result.errors.map(err => `<li>${err}</li>`).join('');
          formMessage.innerHTML = `<div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; border: 1px solid #f5c6cb; margin-bottom: 15px;"><ul style="margin: 0; padding-left: 20px;">${errorHtml}</ul></div>`;
        }
      } catch (error) {
        formMessage.innerHTML = `<div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; border: 1px solid #f5c6cb; margin-bottom: 15px;">Error sending message. Please try again.</div>`;
        console.error('Error:', error);
      }
    });
  }
});

</script>
<footer class="main-footer">
    <div class="footer-inner">
        <div class="footer-grid">
            
            <div class="footer-col">
                <h3><?= htmlspecialchars($site['site_name']) ?></h3>
                <p><?= htmlspecialchars($site['footer_about']) ?></p>
            </div>
            
            <div class="footer-col">
                <h4>Navigation</h4>
                <ul>
                    <li><a onclick="showPage('home')">Home</a></li>
                    <li><a onclick="showPage('about')">About</a></li>
                    <li><a onclick="showPage('services')">Services</a></li>
                    <li><a onclick="showPage('contact')">Contact</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Contact Info</h4>
                <p>
                    <i class="fas fa-map-marker-alt"></i>
                    <?php if (!empty($site['company_address'])): ?>
                        <a href="https://maps.google.com/?q=<?= urlencode($site['company_address']) ?>" 
                           target="_blank" style="color: #ccc; text-decoration: none;">
                            <?= htmlspecialchars($site['company_address']) ?>
                        </a>
                    <?php else: ?>
                        <span>Location not set</span>
                    <?php endif; ?>
                </p>
                <p>
                    <i class="fas fa-envelope"></i>
                    <?php if (!empty($site['company_email'])): ?>
                        <a href="mailto:<?= htmlspecialchars($site['company_email']) ?>" 
                           style="color: #ccc; text-decoration: none;">
                            <?= htmlspecialchars($site['company_email']) ?>
                        </a>
                    <?php else: ?>
                        <span>Email not set</span>
                    <?php endif; ?>
                </p>
                <p>
                    <i class="fas fa-phone"></i>
                    <?php if (!empty($site['company_phone'])): ?>
                        <a href="tel:<?= htmlspecialchars($site['company_phone']) ?>" 
                           style="color: #ccc; text-decoration: none;">
                            <?= htmlspecialchars($site['company_phone']) ?>
                        </a>
                    <?php else: ?>
                        <span>Phone not set</span>
                    <?php endif; ?>
                </p>
            </div>

            <div class="footer-col">
                <h4>Follow Us</h4>
                <div class="social-links">
                    <a href="<?= $site['facebook_url'] ?>"><i class="fab fa-facebook"></i></a>
                    <a href="<?= $site['linkedin_url'] ?>"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>

        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($site['site_name']) ?>. All rights reserved.</p>
        </div>
    </div>
</footer>



</body>

</html>