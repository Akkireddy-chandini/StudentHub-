<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$role = $_SESSION['user_role'];
$name = $_SESSION['user_name'];
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>StudentHub</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="layout">
<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <span class="brand-text">Student<span class="brand-acc">Hub</span></span>
    <button class="sidebar-close" id="sidebarClose"><i class="fas fa-times"></i></button>
  </div>

  <div class="sidebar-user">
    <div class="user-avatar"><?= strtoupper(substr($name,0,1)) ?></div>
    <div>
      <div class="user-name"><?= htmlspecialchars($name) ?></div>
      <div class="user-role"><?= ucfirst($role) ?></div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <?php if ($role === 'student'): ?>
    <a href="student_dashboard.php" class="nav-item <?= $current==='student_dashboard.php'?'active':'' ?>"><i class="fas fa-th-large"></i><span>Dashboard</span></a>
    <a href="notes.php" class="nav-item <?= $current==='notes.php'?'active':'' ?>"><i class="fas fa-book-open"></i><span>Notes</span></a>
    <a href="tasks.php" class="nav-item <?= $current==='tasks.php'?'active':'' ?>"><i class="fas fa-tasks"></i><span>Tasks</span></a>
    <a href="attendance.php" class="nav-item <?= $current==='attendance.php'?'active':'' ?>"><i class="fas fa-chart-pie"></i><span>Attendance</span></a>
    <a href="timetable.php" class="nav-item <?= $current==='timetable.php'?'active':'' ?>"><i class="fas fa-calendar-week"></i><span>Timetable</span></a>
    <?php else: ?>
    <a href="faculty_dashboard.php" class="nav-item <?= $current==='faculty_dashboard.php'?'active':'' ?>"><i class="fas fa-th-large"></i><span>Dashboard</span></a>
    <a href="manage_notes.php" class="nav-item <?= $current==='manage_notes.php'?'active':'' ?>"><i class="fas fa-book-open"></i><span>Manage Notes</span></a>
    <a href="manage_tasks.php" class="nav-item <?= $current==='manage_tasks.php'?'active':'' ?>"><i class="fas fa-tasks"></i><span>Manage Tasks</span></a>
    <a href="manage_attendance.php" class="nav-item <?= $current==='manage_attendance.php'?'active':'' ?>"><i class="fas fa-chart-pie"></i><span>Manage Attendance</span></a>
    <a href="manage_timetable.php" class="nav-item <?= $current==='manage_timetable.php'?'active':'' ?>"><i class="fas fa-calendar-week"></i><span>Manage Timetable</span></a>
    <?php endif; ?>
  </nav>

  <div class="sidebar-footer">
    <a href="logout.php" class="nav-item logout-btn"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
  </div>
</aside>

<!-- OVERLAY -->
<div class="overlay" id="overlay"></div>

<!-- MAIN -->
<div class="main-content">
<!-- TOP NAV -->
<header class="topbar">
  <button class="menu-btn" id="menuBtn"><i class="fas fa-bars"></i></button>
  <div class="topbar-title"><?php
    $titles = [
      'student_dashboard.php'=>'Dashboard','faculty_dashboard.php'=>'Dashboard',
      'notes.php'=>'Notes','manage_notes.php'=>'Manage Notes',
      'tasks.php'=>'Tasks','manage_tasks.php'=>'Manage Tasks',
      'attendance.php'=>'Attendance','manage_attendance.php'=>'Manage Attendance',
      'timetable.php'=>'Timetable','manage_timetable.php'=>'Manage Timetable',
    ];
    echo $titles[$current] ?? 'StudentHub';
  ?></div>
  <div class="topbar-right">
    <div class="topbar-greeting">Hi, <?= htmlspecialchars(explode(' ',$name)[0]) ?> 👋</div>
    <a href="logout.php" class="logout-mini"><i class="fas fa-sign-out-alt"></i></a>
  </div>
</header>
<div class="page-body">