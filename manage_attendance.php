<?php
require_once 'header.php';
require_once 'config.php';
if ($role !== 'faculty') { header("Location: student_dashboard.php"); exit; }

$uid = $_SESSION['user_id'];
$msg = $err = '';

// Handle mark attendance
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $student_id = (int)($_POST['student_id']??0);
    $subject = sanitize($conn, $_POST['subject']??'');
    $date = sanitize($conn, $_POST['date']??'');
    $status = in_array($_POST['status']??'',['present','absent'])?$_POST['status']:'present';

    if (!$student_id || !$subject || !$date) {
        $err = 'All fields required.';
    } else {
        // Check duplicate
        $chk = $conn->prepare("SELECT id FROM attendance WHERE student_id=? AND subject=? AND date=?");
        $chk->bind_param("iss",$student_id,$subject,$date);
        $chk->execute();
        if ($chk->get_result()->num_rows > 0) {
            // Update existing
            $upd = $conn->prepare("UPDATE attendance SET status=?, marked_by=? WHERE student_id=? AND subject=? AND date=?");
            $upd->bind_param("siiss",$status,$uid,$student_id,$subject,$date);
            $upd->execute() ? $msg='Attendance updated.' : ($err='Update failed.');
        } else {
            $ins = $conn->prepare("INSERT INTO attendance (student_id,subject,date,status,marked_by) VALUES(?,?,?,?,?)");
            $ins->bind_param("isssi",$student_id,$subject,$date,$status,$uid);
            $ins->execute() ? $msg='Attendance marked!' : ($err='Insert failed.');
        }
    }
}

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM attendance WHERE id=".(int)$_GET['delete']);
    $msg = 'Record deleted.';
}

$students = $conn->query("SELECT id, name FROM users WHERE role='student' ORDER BY name");
$records = $conn->query("SELECT a.*, u.name as sname FROM attendance a JOIN users u ON a.student_id=u.id ORDER BY a.id DESC, u.name LIMIT 50");
?>

<?php if($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i><?=$msg?></div><?php endif; ?>
<?php if($err): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i><?=$err?></div><?php endif; ?>

<div class="form-card">
  <h2 style="font-family:'Syne',sans-serif;font-size:1rem;font-weight:700;margin-bottom:1.2rem"><i class="fas fa-clipboard-check" style="color:var(--success)"></i> Mark Attendance</h2>
  <form method="POST">
    <div class="form-grid">
      <div class="fg">
        <label>Student *</label>
        <select name="student_id" required>
          <option value="">Select Student</option>
          <?php $students->data_seek(0); while($s=$students->fetch_assoc()): ?>
          <option value="<?=$s['id']?>"><?= htmlspecialchars($s['name']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="fg">
        <label>Subject *</label>
        <input type="text" name="subject" placeholder="e.g. Mathematics" required>
      </div>
      <div class="fg">
        <label>Date *</label>
        <input type="date" name="date" value="<?= date('Y-m-d') ?>" required>
      </div>
      <div class="fg">
        <label>Status</label>
        <select name="status">
          <option value="present">✅ Present</option>
          <option value="absent">❌ Absent</option>
        </select>
      </div>
    </div>
    <div style="margin-top:1rem">
      <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Mark Attendance</button>
    </div>
  </form>
</div>

<!-- Overview per student -->
<div class="section-header" style="margin-top:1.5rem">
  <h2><i class="fas fa-chart-bar" style="color:var(--acc)"></i> Student Attendance Overview</h2>
</div>
<div class="table-wrapper" style="margin-bottom:2rem">
  <table>
    <thead><tr><th>Student</th><th>Total</th><th>Present</th><th>Absent</th><th>Percentage</th><th>Status</th></tr></thead>
    <tbody>
    <?php
    $students->data_seek(0);
    while($s=$students->fetch_assoc()):
      $sid = $s['id'];
      $sa = $conn->query("SELECT COUNT(*) as t, SUM(status='present') as p FROM attendance WHERE student_id=$sid")->fetch_assoc();
      $st = (int)$sa['t']; $sp = (int)$sa['p']; $pct = $st>0?round(($sp/$st)*100,1):0;
    ?>
    <tr>
      <td><div style="display:flex;align-items:center;gap:.6rem">
        <div style="width:32px;height:32px;background:linear-gradient(135deg,var(--acc),var(--acc2));border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem;font-family:'Syne',sans-serif"><?= strtoupper(substr($s['name'],0,1)) ?></div>
        <?= htmlspecialchars($s['name']) ?>
      </div></td>
      <td><?=$st?></td>
      <td style="color:var(--success);font-weight:600"><?=$sp?></td>
      <td style="color:var(--danger);font-weight:600"><?=$st-$sp?></td>
      <td>
        <div style="display:flex;align-items:center;gap:.6rem">
          <div class="progress-bar" style="flex:1">
            <div class="progress-fill <?=$pct>=75?'safe':($pct>=60?'warning':'danger')?>" style="width:<?=$pct?>%"></div>
          </div>
          <span style="font-weight:600;font-size:.82rem;min-width:40px"><?=$pct?>%</span>
        </div>
      </td>
      <td><span class="badge <?=$pct>=75?'badge-present':($pct>=60?'badge-medium':'badge-absent')?>"><?=$pct>=75?'Safe':($pct>=60?'Warning':'Danger')?></span></td>
    </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Recent records -->
<div class="section-header"><h2><i class="fas fa-history" style="color:var(--acc)"></i> Recent Records (Last 50)</h2></div>
<div class="table-wrapper">
  <table>
    <thead><tr><th>Student</th><th>Subject</th><th>Date</th><th>Status</th><th>Action</th></tr></thead>
    <tbody>
    <?php if($records->num_rows===0): ?>
    <tr><td colspan="5"><div class="empty-state"><i class="fas fa-clipboard"></i><p>No records yet.</p></div></td></tr>
    <?php else: while($r=$records->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($r['sname']) ?></td>
      <td><?= htmlspecialchars($r['subject']) ?></td>
      <td><?= date('d M Y', strtotime($r['date'])) ?></td>
      <td><span class="badge badge-<?=$r['status']==='present'?'present':'absent'?>"><?= $r['status']==='present'?'Present':'Absent' ?></span></td>
      <td><a href="manage_attendance.php?delete=<?=$r['id']?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a></td>
    </tr>
    <?php endwhile; endif; ?>
    </tbody>
  </table>
</div>

<?php require_once 'footer.php'; ?>