<?php
require_once 'header.php';
require_once 'config.php';

$uid = $_SESSION['user_id'];

// Notes count
$notes_count = $conn->query("SELECT COUNT(*) as c FROM notes")->fetch_assoc()['c'];

// Tasks count
$tasks_count = $conn->query("SELECT COUNT(*) as c FROM tasks")->fetch_assoc()['c'];

// Attendance
$att = $conn->query("SELECT COUNT(*) as total, SUM(status='present') as present FROM attendance WHERE student_id=$uid")->fetch_assoc();
$total_cls = $att['total'] ?? 0;
$present_cls = $att['present'] ?? 0;
$att_pct = $total_cls > 0 ? round(($present_cls / $total_cls) * 100, 1) : 0;

// Today's timetable
$today = date('l'); // Monday, Tuesday, etc.
$tt_today = $conn->query("SELECT * FROM timetable WHERE day='$today' ORDER BY start_time");
?>

<div class="cards-grid">
  <div class="dash-card blue">
    <div class="card-icon blue"><i class="fas fa-book-open"></i></div>
    <span class="card-val"><?= $notes_count ?></span>
    <div class="card-label">Notes Available</div>
  </div>
  <div class="dash-card purple">
    <div class="card-icon purple"><i class="fas fa-tasks"></i></div>
    <span class="card-val"><?= $tasks_count ?></span>
    <div class="card-label">Active Tasks</div>
  </div>
  <div class="dash-card <?= $att_pct >= 75 ? 'green' : 'amber' ?>">
    <div class="card-icon <?= $att_pct >= 75 ? 'green' : 'amber' ?>"><i class="fas fa-chart-pie"></i></div>
    <span class="card-val"><?= $att_pct ?>%</span>
    <div class="card-label">Attendance <?= $att_pct >= 75 ? '✅' : '⚠️' ?></div>
  </div>
  <div class="dash-card blue">
    <div class="card-icon blue"><i class="fas fa-calendar-day"></i></div>
    <span class="card-val"><?= $tt_today->num_rows ?></span>
    <div class="card-label">Classes Today</div>
  </div>
</div>

<!-- TODAY'S TIMETABLE -->
<div class="section-header">
  <h2><i class="fas fa-calendar-day" style="color:var(--acc)"></i> Today's Schedule — <?= $today ?></h2>
  <a href="timetable.php" class="btn btn-primary btn-sm">Full Timetable</a>
</div>
<div class="table-wrapper" style="margin-bottom:2rem">
  <table>
    <thead><tr><th>Subject</th><th>Time</th><th>Room</th></tr></thead>
    <tbody>
    <?php if ($tt_today->num_rows === 0): ?>
    <tr><td colspan="3"><div class="empty-state"><i class="fas fa-mug-hot"></i><p>No classes today. Enjoy your day!</p></div></td></tr>
    <?php else: while($r=$tt_today->fetch_assoc()): ?>
    <tr>
      <td><span class="badge badge-blue"><?= htmlspecialchars($r['subject']) ?></span></td>
      <td><?= date('h:i A', strtotime($r['start_time'])) ?> – <?= date('h:i A', strtotime($r['end_time'])) ?></td>
      <td><?= htmlspecialchars($r['room']) ?></td>
    </tr>
    <?php endwhile; endif; ?>
    </tbody>
  </table>
</div>

<!-- QUICK LINKS -->
<div class="section-header"><h2><i class="fas fa-bolt" style="color:var(--warning)"></i> Quick Access</h2></div>
<div class="cards-grid" style="margin-bottom:0">
  <a href="notes.php" class="dash-card blue" style="text-decoration:none;cursor:pointer">
    <div class="card-icon blue"><i class="fas fa-book"></i></div>
    <div class="card-label" style="color:var(--text);font-size:.95rem;font-weight:600">View Notes</div>
    <div class="card-label">Browse & download</div>
  </a>
  <a href="tasks.php" class="dash-card purple" style="text-decoration:none;cursor:pointer">
    <div class="card-icon purple"><i class="fas fa-tasks"></i></div>
    <div class="card-label" style="color:var(--text);font-size:.95rem;font-weight:600">My Tasks</div>
    <div class="card-label">Track deadlines</div>
  </a>
  <a href="attendance.php" class="dash-card green" style="text-decoration:none;cursor:pointer">
    <div class="card-icon green"><i class="fas fa-chart-pie"></i></div>
    <div class="card-label" style="color:var(--text);font-size:.95rem;font-weight:600">Attendance</div>
    <div class="card-label">Safety calculator</div>
  </a>
  <a href="timetable.php" class="dash-card amber" style="text-decoration:none;cursor:pointer">
    <div class="card-icon amber"><i class="fas fa-calendar-week"></i></div>
    <div class="card-label" style="color:var(--text);font-size:.95rem;font-weight:600">Timetable</div>
    <div class="card-label">Weekly schedule</div>
  </a>
</div>

<?php require_once 'footer.php'; ?>