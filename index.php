<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>StudentHub — Learn. Grow. Excel.</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
<style>
  :root {
    --c1: #0a0a1a;
    --c2: #0d1b3e;
    --acc: #4f8ef7;
    --acc2: #7c3aed;
    --acc3: #06b6d4;
    --glow: rgba(79,142,247,0.35);
    --glow2: rgba(124,58,237,0.3);
    --glass: rgba(255,255,255,0.04);
    --glass-border: rgba(255,255,255,0.09);
    --text: #e8eaf6;
    --muted: rgba(232,234,246,0.55);
  }

  *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

  html { scroll-behavior: smooth; }

  body {
    background: var(--c1);
    color: var(--text);
    font-family: 'DM Sans', sans-serif;
    overflow-x: hidden;
  }

  /* ── PARTICLES ── */
  #particles {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 0;
  }

  /* ── NAVBAR ── */
  nav {
    position: fixed;
    top: 0; left: 0; right: 0;
    z-index: 1000;
    padding: 1.1rem 5%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(10,10,26,0.7);
    backdrop-filter: blur(18px);
    border-bottom: 1px solid var(--glass-border);
    transition: all .4s;
  }

  nav.scrolled {
    padding: .75rem 5%;
    background: rgba(10,10,26,0.92);
    box-shadow: 0 4px 40px rgba(79,142,247,0.08);
  }

  .logo {
    font-family: 'Syne', sans-serif;
    font-size: 1.5rem;
    font-weight: 800;
    background: linear-gradient(135deg, var(--acc), var(--acc3));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    letter-spacing: -0.5px;
  }

  .logo span { color: var(--acc2); -webkit-text-fill-color: var(--acc2); }

  .nav-links {
    display: flex;
    gap: 2.2rem;
    list-style: none;
  }

  .nav-links a {
    color: var(--muted);
    text-decoration: none;
    font-size: .92rem;
    font-weight: 500;
    letter-spacing: .3px;
    transition: color .25s;
    position: relative;
  }

  .nav-links a::after {
    content: '';
    position: absolute;
    bottom: -4px; left: 0;
    width: 0; height: 2px;
    background: linear-gradient(90deg, var(--acc), var(--acc3));
    border-radius: 2px;
    transition: width .3s;
  }

  .nav-links a:hover { color: var(--text); }
  .nav-links a:hover::after { width: 100%; }

  .nav-btns { display: flex; gap: .75rem; align-items: center; }

  .btn-ghost {
    padding: .5rem 1.2rem;
    border: 1px solid var(--glass-border);
    border-radius: 8px;
    color: var(--text);
    background: transparent;
    font-size: .88rem;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    transition: all .25s;
  }

  .btn-ghost:hover {
    background: var(--glass);
    border-color: var(--acc);
    color: var(--acc);
  }

  .btn-primary {
    padding: .5rem 1.4rem;
    border: none;
    border-radius: 8px;
    background: linear-gradient(135deg, var(--acc), var(--acc2));
    color: #fff;
    font-size: .88rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all .3s;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 20px var(--glow);
  }

  .btn-primary::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, var(--acc2), var(--acc));
    opacity: 0;
    transition: opacity .3s;
  }

  .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 30px var(--glow); }
  .btn-primary:hover::before { opacity: 1; }
  .btn-primary span { position: relative; z-index: 1; }

  /* Ripple */
  .btn-primary::after {
    content: '';
    position: absolute;
    width: 0; height: 0;
    background: rgba(255,255,255,0.3);
    border-radius: 50%;
    transform: translate(-50%,-50%);
    transition: width .6s, height .6s, opacity .6s;
    opacity: 0;
  }

  .btn-primary.ripple::after {
    width: 400px; height: 400px; opacity: 0;
  }

  /* Hamburger */
  .hamburger { display:none; flex-direction:column; gap:5px; cursor:pointer; }
  .hamburger span { width:24px; height:2px; background:var(--text); border-radius:2px; transition:.3s; }

  /* ── HERO ── */
  #home {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    padding: 8rem 5% 4rem;
    text-align: center;
    overflow: hidden;
  }

  .hero-bg {
    position: absolute;
    inset: 0;
    background:
      radial-gradient(ellipse 80% 60% at 50% -10%, rgba(79,142,247,0.25) 0%, transparent 60%),
      radial-gradient(ellipse 60% 40% at 80% 80%, rgba(124,58,237,0.2) 0%, transparent 55%),
      radial-gradient(ellipse 40% 30% at 10% 70%, rgba(6,182,212,0.15) 0%, transparent 50%);
    animation: bgPulse 8s ease-in-out infinite alternate;
  }

  @keyframes bgPulse {
    0% { opacity: .8; }
    100% { opacity: 1; transform: scale(1.05); }
  }

  .wave-container {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    overflow: hidden;
    line-height: 0;
  }

  .wave-container svg { display: block; width: 100%; }

  .hero-content { position: relative; z-index: 2; max-width: 800px; }

  .hero-badge {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .4rem 1rem;
    background: var(--glass);
    border: 1px solid var(--glass-border);
    border-radius: 50px;
    font-size: .8rem;
    color: var(--acc);
    margin-bottom: 1.8rem;
    backdrop-filter: blur(10px);
    animation: fadeInDown .8s ease both;
  }

  .hero-badge i { animation: spin 3s linear infinite; }

  @keyframes spin { to { transform: rotate(360deg); } }
  @keyframes fadeInDown { from { opacity:0; transform:translateY(-20px); } to { opacity:1; transform:translateY(0); } }
  @keyframes fadeInUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
  @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }

  .hero-title {
    font-family: 'Syne', sans-serif;
    font-size: clamp(2.8rem, 7vw, 5.5rem);
    font-weight: 800;
    line-height: 1.05;
    margin-bottom: 1.4rem;
    animation: fadeInUp .9s .2s ease both;
  }

  .hero-title .grad {
    background: linear-gradient(135deg, var(--acc) 0%, var(--acc3) 50%, var(--acc2) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-size: 200% auto;
    animation: gradShift 4s linear infinite;
  }

  @keyframes gradShift {
    0% { background-position: 0% center; }
    100% { background-position: 200% center; }
  }

  .hero-sub {
    font-size: 1.15rem;
    color: var(--muted);
    max-width: 560px;
    margin: 0 auto 2.5rem;
    line-height: 1.7;
    animation: fadeInUp .9s .35s ease both;
  }

  .hero-btns {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    animation: fadeInUp .9s .5s ease both;
  }

  .btn-hero {
    padding: .85rem 2.2rem;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all .3s;
    position: relative;
    overflow: hidden;
  }

  .btn-hero-primary {
    background: linear-gradient(135deg, var(--acc), var(--acc2));
    color: #fff;
    border: none;
    box-shadow: 0 8px 32px var(--glow);
  }

  .btn-hero-primary:hover { transform: translateY(-3px); box-shadow: 0 16px 48px var(--glow); }

  .btn-hero-outline {
    background: var(--glass);
    color: var(--text);
    border: 1px solid var(--glass-border);
    backdrop-filter: blur(10px);
  }

  .btn-hero-outline:hover { border-color: var(--acc); color: var(--acc); transform: translateY(-3px); }

  .floating-cards {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: 1;
  }

  .float-card {
    position: absolute;
    background: var(--glass);
    border: 1px solid var(--glass-border);
    backdrop-filter: blur(12px);
    border-radius: 16px;
    padding: .9rem 1.2rem;
    display: flex;
    align-items: center;
    gap: .7rem;
    font-size: .82rem;
    white-space: nowrap;
    animation: float 6s ease-in-out infinite;
  }

  .float-card:nth-child(1) { top: 22%; left: 6%; animation-delay: 0s; }
  .float-card:nth-child(2) { top: 15%; right: 8%; animation-delay: 1.5s; }
  .float-card:nth-child(3) { bottom: 28%; left: 4%; animation-delay: .8s; }
  .float-card:nth-child(4) { bottom: 22%; right: 6%; animation-delay: 2.2s; }

  .float-card i { font-size: 1.1rem; }
  .float-card .fc-text span { display: block; font-size: .7rem; color: var(--muted); }

  @keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-14px); }
  }

  /* ── SECTIONS COMMON ── */
  section { position: relative; z-index: 1; }

  .section-pad { padding: 6rem 5%; }

  .section-tag {
    display: inline-block;
    padding: .35rem .9rem;
    background: rgba(79,142,247,0.1);
    border: 1px solid rgba(79,142,247,0.25);
    border-radius: 50px;
    font-size: .78rem;
    color: var(--acc);
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 1rem;
  }

  .section-title {
    font-family: 'Syne', sans-serif;
    font-size: clamp(1.8rem, 4vw, 2.8rem);
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: .8rem;
  }

  .section-title .hl {
    background: linear-gradient(135deg, var(--acc), var(--acc3));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .section-sub {
    color: var(--muted);
    font-size: 1rem;
    line-height: 1.7;
    max-width: 560px;
  }

  /* Scroll reveal */
  .reveal { opacity: 0; transform: translateY(40px); transition: opacity .7s, transform .7s; }
  .reveal.visible { opacity: 1; transform: none; }
  .reveal-left { opacity: 0; transform: translateX(-40px); transition: opacity .7s, transform .7s; }
  .reveal-left.visible { opacity: 1; transform: none; }
  .reveal-right { opacity: 0; transform: translateX(40px); transition: opacity .7s, transform .7s; }
  .reveal-right.visible { opacity: 1; transform: none; }

  /* ── ABOUT ── */
  #about { background: linear-gradient(180deg, var(--c1) 0%, var(--c2) 100%); }

  .about-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
    max-width: 1100px;
    margin: 0 auto;
  }

  .about-visual {
    position: relative;
    height: 380px;
  }

  .about-glow {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%,-50%);
    width: 320px; height: 320px;
    background: radial-gradient(circle, rgba(79,142,247,0.2), transparent 70%);
    border-radius: 50%;
    animation: pulse 4s ease-in-out infinite;
  }

  @keyframes pulse { 0%,100%{transform:translate(-50%,-50%) scale(1);} 50%{transform:translate(-50%,-50%) scale(1.15);} }

  .about-card {
    position: absolute;
    background: var(--glass);
    border: 1px solid var(--glass-border);
    backdrop-filter: blur(16px);
    border-radius: 20px;
    padding: 1.4rem 1.6rem;
    transition: transform .3s;
  }

  .about-card:hover { transform: scale(1.04); }

  .about-card.ac1 { top: 0; left: 0; }
  .about-card.ac2 { top: 40%; right: 0; }
  .about-card.ac3 { bottom: 0; left: 20%; }

  .ac-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
    margin-bottom: .7rem;
  }

  .ac-icon.blue { background: rgba(79,142,247,0.15); color: var(--acc); }
  .ac-icon.purple { background: rgba(124,58,237,0.15); color: var(--acc2); }
  .ac-icon.cyan { background: rgba(6,182,212,0.15); color: var(--acc3); }

  .ac-label { font-size: .8rem; color: var(--muted); }
  .ac-val { font-family: 'Syne', sans-serif; font-size: 1.4rem; font-weight: 700; }

  .about-text .section-sub { max-width: 100%; margin-bottom: 1.5rem; }

  .about-bullets { list-style: none; display: flex; flex-direction: column; gap: .9rem; }

  .about-bullets li {
    display: flex;
    align-items: center;
    gap: .75rem;
    color: var(--muted);
    font-size: .95rem;
  }

  .about-bullets li i { color: var(--acc); font-size: .85rem; }

  /* ── FEATURES ── */
  #features { background: var(--c1); }

  .features-header { text-align: center; margin-bottom: 3.5rem; }
  .features-header .section-sub { margin: 0 auto; }

  .features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    max-width: 1100px;
    margin: 0 auto;
  }

  .feat-card {
    background: var(--glass);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    padding: 2rem;
    transition: all .35s;
    cursor: default;
    position: relative;
    overflow: hidden;
  }

  .feat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, var(--acc), var(--acc2), var(--acc3));
    opacity: 0;
    transition: opacity .35s;
  }

  .feat-card:hover {
    transform: translateY(-8px);
    border-color: rgba(79,142,247,0.25);
    box-shadow: 0 20px 60px rgba(79,142,247,0.12);
  }

  .feat-card:hover::before { opacity: 1; }

  .feat-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem;
    margin-bottom: 1.2rem;
  }

  .feat-icon.i1 { background: rgba(79,142,247,0.12); color: var(--acc); }
  .feat-icon.i2 { background: rgba(124,58,237,0.12); color: var(--acc2); }
  .feat-icon.i3 { background: rgba(6,182,212,0.12); color: var(--acc3); }
  .feat-icon.i4 { background: rgba(16,185,129,0.12); color: #10b981; }
  .feat-icon.i5 { background: rgba(245,158,11,0.12); color: #f59e0b; }
  .feat-icon.i6 { background: rgba(239,68,68,0.12); color: #ef4444; }

  .feat-title {
    font-family: 'Syne', sans-serif;
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: .55rem;
  }

  .feat-desc { font-size: .88rem; color: var(--muted); line-height: 1.65; }

  /* ── STATS ── */
  #stats {
    background: linear-gradient(135deg, var(--c2) 0%, var(--c1) 100%);
    border-top: 1px solid var(--glass-border);
    border-bottom: 1px solid var(--glass-border);
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 2rem;
    max-width: 900px;
    margin: 0 auto;
    text-align: center;
  }

  .stat-item { padding: 1.5rem; }

  .stat-num {
    font-family: 'Syne', sans-serif;
    font-size: 3rem;
    font-weight: 800;
    background: linear-gradient(135deg, var(--acc), var(--acc3));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    display: block;
    margin-bottom: .3rem;
  }

  .stat-label { color: var(--muted); font-size: .88rem; font-weight: 500; }

  /* ── FOOTER ── */
  footer {
    background: var(--c1);
    border-top: 1px solid var(--glass-border);
    padding: 3rem 5% 1.5rem;
  }

  .footer-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: 3rem;
    margin-bottom: 2.5rem;
    max-width: 1100px;
    margin-left: auto;
    margin-right: auto;
  }

  .footer-brand p { color: var(--muted); font-size: .88rem; line-height: 1.7; margin: .8rem 0 1.2rem; }

  .social-links { display: flex; gap: .7rem; }

  .social-links a {
    width: 36px; height: 36px;
    background: var(--glass);
    border: 1px solid var(--glass-border);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: var(--muted);
    text-decoration: none;
    transition: all .25s;
    font-size: .85rem;
  }

  .social-links a:hover { border-color: var(--acc); color: var(--acc); background: rgba(79,142,247,0.08); }

  .footer-col h4 {
    font-family: 'Syne', sans-serif;
    font-size: .9rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: var(--text);
  }

  .footer-col ul { list-style: none; display: flex; flex-direction: column; gap: .55rem; }

  .footer-col ul a {
    color: var(--muted);
    text-decoration: none;
    font-size: .85rem;
    transition: color .25s;
  }

  .footer-col ul a:hover { color: var(--acc); }

  .footer-bottom {
    border-top: 1px solid var(--glass-border);
    padding-top: 1.5rem;
    text-align: center;
    color: var(--muted);
    font-size: .8rem;
    max-width: 1100px;
    margin: 0 auto;
  }

  /* ── MOBILE ── */
  @media (max-width: 768px) {
    .nav-links { display: none; }
    .hamburger { display: flex; }
    .nav-links.open {
      display: flex; flex-direction: column;
      position: fixed; inset: 0;
      background: rgba(10,10,26,.97);
      align-items: center; justify-content: center;
      gap: 2rem; font-size: 1.1rem;
      z-index: 999;
    }
    .about-grid { grid-template-columns: 1fr; }
    .about-visual { display: none; }
    .footer-grid { grid-template-columns: 1fr; gap: 2rem; }
    .float-card { display: none; }
  }
</style>
</head>
<body>

<canvas id="particles"></canvas>

<!-- NAVBAR -->
<nav id="navbar">
  <div class="logo">Student<span>Hub</span></div>
  <ul class="nav-links" id="navLinks">
    <li><a href="#home">Home</a></li>
    <li><a href="#about">About</a></li>
    <li><a href="#features">Features</a></li>
    <li><a href="#stats">Stats</a></li>
  </ul>
  <div class="nav-btns">
    <a href="login.php" class="btn-ghost">Login</a>
    <a href="register.php" class="btn-primary"><span>Get Started</span></a>
    <div class="hamburger" id="hamburger">
      <span></span><span></span><span></span>
    </div>
  </div>
</nav>

<!-- HERO -->
<section id="home">
  <div class="hero-bg"></div>
  <div class="floating-cards">
    <div class="float-card"><i class="fas fa-book-open" style="color:#4f8ef7"></i><div class="fc-text">Notes Uploaded<span>+2,340 files</span></div></div>
    <div class="float-card"><i class="fas fa-check-circle" style="color:#10b981"></i><div class="fc-text">Tasks Done<span>98% on time</span></div></div>
    <div class="float-card"><i class="fas fa-chart-line" style="color:#7c3aed"></i><div class="fc-text">Attendance<span>Tracked daily</span></div></div>
    <div class="float-card"><i class="fas fa-calendar-alt" style="color:#06b6d4"></i><div class="fc-text">Timetable<span>Smart scheduling</span></div></div>
  </div>
  <div class="hero-content">
    <div class="hero-badge"><i class="fas fa-star"></i> Next-gen Student Management Platform</div>
    <h1 class="hero-title">Learn Smarter,<br><span class="grad">Excel Further</span></h1>
    <p class="hero-sub">StudentHub unifies your academic life — notes, tasks, attendance & timetable — in one premium, beautifully designed platform.</p>
    <div class="hero-btns">
      <a href="register.php" class="btn-hero btn-hero-primary">Get Started Free <i class="fas fa-arrow-right"></i></a>
      <a href="#about" class="btn-hero btn-hero-outline">Learn More</a>
    </div>
  </div>
  <div class="wave-container">
    <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg">
      <path fill="#0d1b3e" d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z"/>
    </svg>
  </div>
</section>

<!-- ABOUT -->
<section id="about" class="section-pad">
  <div class="about-grid">
    <div class="about-visual reveal-left">
      <div class="about-glow"></div>
      <div class="about-card ac1">
        <div class="ac-icon blue"><i class="fas fa-users"></i></div>
        <div class="ac-label">Active Students</div>
        <div class="ac-val">12,000+</div>
      </div>
      <div class="about-card ac2">
        <div class="ac-icon purple"><i class="fas fa-graduation-cap"></i></div>
        <div class="ac-label">Institutions</div>
        <div class="ac-val">340+</div>
      </div>
      <div class="about-card ac3">
        <div class="ac-icon cyan"><i class="fas fa-file-alt"></i></div>
        <div class="ac-label">Notes Shared</div>
        <div class="ac-val">50K+</div>
      </div>
    </div>
    <div class="about-text reveal-right">
      <div class="section-tag">About StudentHub</div>
      <h2 class="section-title">Built for the <span class="hl">Modern Student</span></h2>
      <p class="section-sub">StudentHub bridges the gap between students and faculty with an intuitive, all-in-one academic management system. Track your progress, never miss a task, and study smarter.</p>
      <ul class="about-bullets">
        <li><i class="fas fa-check-circle"></i> Real-time attendance tracking with safety calculator</li>
        <li><i class="fas fa-check-circle"></i> Faculty-managed notes with instant download</li>
        <li><i class="fas fa-check-circle"></i> Priority-based task management</li>
        <li><i class="fas fa-check-circle"></i> Color-coded interactive timetable</li>
        <li><i class="fas fa-check-circle"></i> Role-based access for students & faculty</li>
      </ul>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section id="features" class="section-pad">
  <div class="features-header reveal">
    <div class="section-tag">What We Offer</div>
    <h2 class="section-title">Everything You Need to <span class="hl">Succeed</span></h2>
    <p class="section-sub">Six powerful modules, one seamless experience. Designed for students, managed by faculty.</p>
  </div>
  <div class="features-grid">
    <div class="feat-card reveal"><div class="feat-icon i1"><i class="fas fa-book"></i></div><div class="feat-title">Smart Notes</div><p class="feat-desc">Faculty upload subject-wise notes. Students access and download them instantly with a beautiful file management interface.</p></div>
    <div class="feat-card reveal" style="transition-delay:.1s"><div class="feat-icon i2"><i class="fas fa-tasks"></i></div><div class="feat-title">Task Management</div><p class="feat-desc">Assign, track and prioritize academic tasks. Color-coded priority system with due-date alerts keeps you on schedule.</p></div>
    <div class="feat-card reveal" style="transition-delay:.2s"><div class="feat-icon i3"><i class="fas fa-chart-pie"></i></div><div class="feat-title">Attendance Tracker</div><p class="feat-desc">Live attendance percentage with an intelligent safety calculator. Know exactly how many classes you need to attend.</p></div>
    <div class="feat-card reveal" style="transition-delay:.3s"><div class="feat-icon i4"><i class="fas fa-calendar-week"></i></div><div class="feat-title">Timetable</div><p class="feat-desc">A modern, color-coded weekly timetable with hover effects. Never miss a class again.</p></div>
    <div class="feat-card reveal" style="transition-delay:.4s"><div class="feat-icon i5"><i class="fas fa-chalkboard-teacher"></i></div><div class="feat-title">Faculty Dashboard</div><p class="feat-desc">Comprehensive admin panel for faculty to manage notes, tasks, attendance and timetables effortlessly.</p></div>
    <div class="feat-card reveal" style="transition-delay:.5s"><div class="feat-icon i6"><i class="fas fa-shield-alt"></i></div><div class="feat-title">Secure Auth</div><p class="feat-desc">Role-based authentication system. Students and faculty each get tailored dashboards with protected routes.</p></div>
  </div>
</section>

<!-- STATS -->
<section id="stats" class="section-pad">
  <div class="stats-grid">
    <div class="stat-item reveal"><span class="stat-num" data-target="12000">0</span><div class="stat-label">Active Students</div></div>
    <div class="stat-item reveal" style="transition-delay:.15s"><span class="stat-num" data-target="340">0</span><div class="stat-label">Institutions</div></div>
    <div class="stat-item reveal" style="transition-delay:.3s"><span class="stat-num" data-target="50000">0</span><div class="stat-label">Notes Shared</div></div>
    <div class="stat-item reveal" style="transition-delay:.45s"><span class="stat-num" data-target="98">0</span><div class="stat-label">% Uptime</div></div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-grid">
    <div class="footer-brand">
      <div class="logo">Student<span>Hub</span></div>
      <p>Your all-in-one academic management platform built for next-generation learners and educators.</p>
      <div class="social-links">
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-linkedin"></i></a>
        <a href="#"><i class="fab fa-github"></i></a>
      </div>
    </div>
    <div class="footer-col">
      <h4>Platform</h4>
      <ul>
        <li><a href="#features">Features</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Modules</h4>
      <ul>
        <li><a href="#">Notes</a></li>
        <li><a href="#">Tasks</a></li>
        <li><a href="#">Attendance</a></li>
        <li><a href="#">Timetable</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; 2025 StudentHub. Crafted with <i class="fas fa-heart" style="color:#ef4444"></i> for students everywhere.</p>
  </div>
</footer>

<script>
// Particles
const canvas = document.getElementById('particles');
const ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

const particles = Array.from({length:80}, () => ({
  x: Math.random() * canvas.width,
  y: Math.random() * canvas.height,
  r: Math.random() * 1.5 + 0.3,
  dx: (Math.random()-.5) * 0.4,
  dy: (Math.random()-.5) * 0.4,
  alpha: Math.random() * 0.5 + 0.1
}));

function drawParticles(){
  ctx.clearRect(0,0,canvas.width,canvas.height);
  particles.forEach(p => {
    ctx.beginPath();
    ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
    ctx.fillStyle = `rgba(79,142,247,${p.alpha})`;
    ctx.fill();
    p.x += p.dx; p.y += p.dy;
    if(p.x<0||p.x>canvas.width) p.dx*=-1;
    if(p.y<0||p.y>canvas.height) p.dy*=-1;
  });
  requestAnimationFrame(drawParticles);
}
drawParticles();

window.addEventListener('resize', () => {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
});

// Navbar scroll
window.addEventListener('scroll', () => {
  document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 60);
});

// Hamburger
document.getElementById('hamburger').addEventListener('click', () => {
  document.getElementById('navLinks').classList.toggle('open');
});

// Scroll reveal
const reveals = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
const ro = new IntersectionObserver((entries) => {
  entries.forEach(e => { if(e.isIntersecting) e.target.classList.add('visible'); });
}, {threshold: 0.15});
reveals.forEach(r => ro.observe(r));

// Counter animation
const counters = document.querySelectorAll('.stat-num[data-target]');
const co = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if(!e.isIntersecting) return;
    const el = e.target, target = +el.dataset.target;
    let val = 0;
    const step = target / 80;
    const t = setInterval(() => {
      val = Math.min(val + step, target);
      el.textContent = val >= 1000 ? (val/1000).toFixed(val>=10000?0:1)+'K+' : Math.floor(val)+(target===98?'%':'+');
      if(val >= target) clearInterval(t);
    }, 20);
    co.unobserve(el);
  });
}, {threshold: 0.5});
counters.forEach(c => co.observe(c));

// Ripple
document.querySelectorAll('.btn-primary').forEach(btn => {
  btn.addEventListener('click', function(e){
    this.classList.remove('ripple');
    void this.offsetWidth;
    this.classList.add('ripple');
  });
});
</script>
</body>
</html>