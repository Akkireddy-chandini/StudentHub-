<?php
require_once 'header.php';
require_once 'config.php';

$uid = $_SESSION['user_id'];

$notes_count = $conn->query("SELECT COUNT(*) as c FROM notes WHERE uploaded_by=$uid")->fetch_assoc()['c'];
$tasks_count = $conn->query("SELECT COUNT(*) as c FROM tasks WHERE created_by=$uid")->fetch_assoc()['c'];
$students_count = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='student'")->fetch_assoc()['c'];

/* FIXED LINE */
$att_today = $conn->query("SELECT COUNT(*) as c FROM attendance")->fetch_assoc()['c'];
?>

<div class="cards-grid">
  <div class="dash-card blue">
    <div class="card-icon blue"><i class="fas fa-book-open"></i></div>
    <span class="card-val"><?= $notes_count ?></span>
    <div class="card-label">Notes Uploaded</div>
  </div>
  <div class="dash-card purple">
    <div class="card-icon purple"><i class="fas fa-tasks"></i></div>
    <span class="card-val"><?= $tasks_count ?></span>
    <div class="card-label">Tasks Created</div>
  </div>
  <div class="dash-card green">
    <div class="card-icon green"><i class="fas fa-user-graduate"></i></div>
    <span class="card-val"><?= $students_count ?></span>
    <div class="card-label">Total Students</div>
  </div>
  <div class="dash-card amber">
    <div class="card-icon amber"><i class="fas fa-calendar-check"></i></div>
    <span class="card-val"><?= $att_today ?></span>
    <div class="card-label">Attendance Marked Today</div>
  </div>
</div>

<div class="section-header">
  <h2><i class="fas fa-cogs" style="color:var(--acc)"></i> Management Modules</h2>
</div>

<div class="cards-grid" style="margin-bottom:2rem">

  <a href="manage_notes.php" class="dash-card blue" style="text-decoration:none;cursor:pointer">
    <div class="card-icon blue"><i class="fas fa-upload"></i></div>
    <div class="card-label" style="color:var(--text);font-size:.95rem;font-weight:600">Manage Notes</div>
    <div class="card-label">Upload & delete notes</div>
  </a>

  <a href="manage_tasks.php" class="dash-card purple" style="text-decoration:none;cursor:pointer">
    <div class="card-icon purple"><i class="fas fa-plus-circle"></i></div>
    <div class="card-label" style="color:var(--text);font-size:.95rem;font-weight:600">Manage Tasks</div>
    <div class="card-label">Assign & track tasks</div>
  </a>

  <a href="manage_attendance.php" class="dash-card green" style="text-decoration:none;cursor:pointer">
    <div class="card-icon green"><i class="fas fa-clipboard-check"></i></div>
    <div class="card-label" style="color:var(--text);font-size:.95rem;font-weight:600">Manage Attendance</div>
    <div class="card-label">Mark & view records</div>
  </a>

  <a href="manage_timetable.php" class="dash-card amber" style="text-decoration:none;cursor:pointer">
    <div class="card-icon amber"><i class="fas fa-calendar-plus"></i></div>
    <div class="card-label" style="color:var(--text);font-size:.95rem;font-weight:600">Manage Timetable</div>
    <div class="card-label">Schedule classes</div>
  </a>

</div>

<?php require_once 'footer.php'; ?>