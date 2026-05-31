<?php
require_once 'header.php';
require_once 'config.php';

$uid = $_SESSION['user_id'];

// Attendance stats per subject
$subjects_sql = $conn->query("
  SELECT subject,
    COUNT(*) as total,
    SUM(status='present') as present,
    SUM(status='absent') as absent
  FROM attendance
  WHERE student_id=$uid
  GROUP BY subject
  ORDER BY subject
");

// Overall
$overall = $conn->query("SELECT COUNT(*) as total, SUM(status='present') as present FROM attendance WHERE student_id=$uid")->fetch_assoc();
$total = (int)($overall['total'] ?? 0);
$present = (int)($overall['present'] ?? 0);
$absent = $total - $present;
$pct = $total > 0 ? round(($present/$total)*100, 1) : 0;
$safe_pct = 75;
$status_class = $pct >= $safe_pct ? 'safe' : ($pct >= 60 ? 'warning' : 'danger');
$status_text = $pct >= $safe_pct ? 'Safe' : ($pct >= 60 ? 'Warning' : 'Danger');

// Safety calculator
$classes_needed = 0;
$can_miss = 0;
if ($total > 0) {
    if ($pct < $safe_pct) {
        // How many consecutive classes to attend to reach 75%?
        $x = 0;
        while (true) {
            $new_pct = (($present + $x) / ($total + $x)) * 100;
            if ($new_pct >= $safe_pct) break;
            $x++;
            if ($x > 500) break;
        }
        $classes_needed = $x;
    } else {
        // How many classes can be missed while staying above 75%?
        $y = 0;
        while (true) {
            $new_pct = ($present / ($total + $y + 1)) * 100;
            if ($new_pct < $safe_pct) break;
            $y++;
            if ($y > 500) break;
        }
        $can_miss = $y;
    }
}

// Recent attendance records
$recent = $conn->query("SELECT * FROM attendance WHERE student_id=$uid ORDER BY date DESC LIMIT 20");
?>

<!-- Circular Progress & Overview -->
<div class="cards-grid" style="margin-bottom:2rem">
  <!-- Overall Circle -->
  <div class="dash-card <?= $status_class==='safe'?'green':($status_class==='warning'?'amber':'blue') ?>" style="grid-column:span 1;text-align:center;padding:2rem">
    <div class="circular-progress">
      <svg width="140" height="140" viewBox="0 0 140 140">
        <circle cx="70" cy="70" r="58" fill="none" stroke="rgba(255,255,255,0.07)" stroke-width="10"/>
        <circle cx="70" cy="70" r="58" fill="none"
          stroke="<?= $pct>=75?'#10b981':($pct>=60?'#f59e0b':'#ef4444') ?>"
          stroke-width="10"
          stroke-dasharray="<?= round(2*M_PI*58, 2) ?>"
          stroke-dashoffset="<?= round(2*M_PI*58*(1-$pct/100), 2) ?>"
          stroke-linecap="round"
          style="transition:stroke-dashoffset 1.5s ease"/>
      </svg>
      <span class="cp-value" style="color:<?= $pct>=75?'#10b981':($pct>=60?'#f59e0b':'#ef4444') ?>"><?= $pct ?>%</span>
      <span class="cp-label">Overall</span>
    </div>
    <div style="margin-top:1rem">
      <span class="badge <?= $pct>=75?'badge-present':($pct>=60?'badge-medium':'badge-absent') ?>" style="font-size:.85rem;padding:.4rem 1.2rem">
        <i class="fas <?= $pct>=75?'fa-shield-alt':($pct>=60?'fa-exclamation-triangle':'fa-times-circle') ?>"></i>
        <?= $status_text ?>
      </span>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="dash-card green">
    <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
    <span class="card-val"><?= $present ?></span>
    <div class="card-label">Classes Attended</div>
  </div>
  <div class="dash-card amber">
    <div class="card-icon amber"><i class="fas fa-times-circle"></i></div>
    <span class="card-val"><?= $absent ?></span>
    <div class="card-label">Classes Missed</div>
  </div>
  <div class="dash-card blue">
    <div class="card-icon blue"><i class="fas fa-calendar-alt"></i></div>
    <span class="card-val"><?= $total ?></span>
    <div class="card-label">Total Classes</div>
  </div>
</div>

<!-- Safety Calculator -->
<div class="section-header"><h2><i class="fas fa-calculator" style="color:var(--acc2)"></i> Attendance Safety Calculator</h2></div>
<div class="dash-card" style="margin-bottom:2rem;padding:1.8rem">
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:2rem;align-items:center">
    <div>
      <div style="font-size:.82rem;color:var(--muted);margin-bottom:.5rem">Required Safe Attendance</div>
      <div style="font-family:'Syne',sans-serif;font-size:2rem;font-weight:800;color:var(--acc)"><?= $safe_pct ?>%</div>
      <div class="progress-bar" style="margin-top:.6rem">
        <div class="progress-fill <?= $status_class ?>" style="width:<?= $pct ?>%"></div>
      </div>
      <div style="font-size:.78rem;color:var(--muted);margin-top:.4rem">Your current: <?= $pct ?>%</div>
    </div>

    <?php if ($pct < $safe_pct && $total > 0): ?>
    <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:14px;padding:1.2rem;text-align:center">
      <i class="fas fa-exclamation-triangle" style="color:var(--danger);font-size:1.5rem;margin-bottom:.6rem;display:block"></i>
      <div style="font-size:.82rem;color:var(--muted);margin-bottom:.3rem">You need to attend</div>
      <div style="font-family:'Syne',sans-serif;font-size:2.4rem;font-weight:800;color:var(--danger)"><?= $classes_needed ?></div>
      <div style="font-size:.82rem;color:var(--muted)">consecutive classes to reach 75%</div>
    </div>
    <?php elseif ($total > 0): ?>
    <div style="background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.2);border-radius:14px;padding:1.2rem;text-align:center">
      <i class="fas fa-shield-alt" style="color:var(--success);font-size:1.5rem;margin-bottom:.6rem;display:block"></i>
      <div style="font-size:.82rem;color:var(--muted);margin-bottom:.3rem">You can still miss</div>
      <div style="font-family:'Syne',sans-serif;font-size:2.4rem;font-weight:800;color:var(--success)"><?= $can_miss ?></div>
      <div style="font-size:.82rem;color:var(--muted)">classes and stay above 75%</div>
    </div>
    <?php else: ?>
    <div style="text-align:center;color:var(--muted)"><i class="fas fa-info-circle"></i> No attendance data yet.</div>
    <?php endif; ?>

    <div>
      <div style="font-size:.82rem;color:var(--muted);margin-bottom:.8rem;font-weight:600">Status Breakdown</div>
      <?php foreach([['Safe Zone','≥75%','#10b981'],['Warning','60-74%','#f59e0b'],['Danger','<60%','#ef4444']] as [$l,$r,$c]): ?>
      <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.5rem;font-size:.82rem">
        <span style="width:10px;height:10px;background:<?=$c?>;border-radius:50%;flex-shrink:0"></span>
        <span><?=$l?></span><span style="color:var(--muted);margin-left:auto"><?=$r?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Per Subject Attendance -->
<?php if ($subjects_sql->num_rows > 0): ?>
<div class="section-header"><h2><i class="fas fa-list-alt" style="color:var(--acc3)"></i> Subject-wise Attendance</h2></div>
<div class="table-wrapper" style="margin-bottom:2rem">
  <table>
    <thead><tr><th>Subject</th><th>Present</th><th>Absent</th><th>Total</th><th>Percentage</th><th>Status</th></tr></thead>
    <tbody>
    <?php while($s=$subjects_sql->fetch_assoc()):
      $sp = $s['total']>0 ? round(($s['present']/$s['total'])*100,1) : 0;
      $sc = $sp>=75?'safe':($sp>=60?'warning':'danger');
    ?>
    <tr>
      <td><span class="badge badge-blue"><?= htmlspecialchars($s['subject']) ?></span></td>
      <td style="color:var(--success);font-weight:600"><?= $s['present'] ?></td>
      <td style="color:var(--danger);font-weight:600"><?= $s['absent'] ?></td>
      <td><?= $s['total'] ?></td>
      <td>
        <div style="display:flex;align-items:center;gap:.75rem">
          <div class="progress-bar" style="flex:1">
            <div class="progress-fill <?=$sc?>" style="width:<?=$sp?>%"></div>
          </div>
          <span style="font-weight:600;font-size:.85rem;min-width:42px"><?=$sp?>%</span>
        </div>
      </td>
      <td><span class="badge <?=$sp>=75?'badge-present':($sp>=60?'badge-medium':'badge-absent')?>"><?=$sp>=75?'Safe':($sp>=60?'Warning':'Danger')?></span></td>
    </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

<!-- Recent Records -->
<div class="section-header"><h2><i class="fas fa-history" style="color:var(--acc)"></i> Recent Records</h2></div>
<div class="table-wrapper">
  <table>
    <thead><tr><th>Date</th><th>Subject</th><th>Status</th></tr></thead>
    <tbody>
    <?php if ($recent->num_rows===0): ?>
    <tr><td colspan="3"><div class="empty-state"><i class="fas fa-calendar-times"></i><p>No attendance recorded yet.</p></div></td></tr>
    <?php else: while($r=$recent->fetch_assoc()): ?>
    <tr>
      <td><?= date('d M Y', strtotime($r['date'])) ?></td>
      <td><?= htmlspecialchars($r['subject']) ?></td>
      <td><span class="badge badge-<?=$r['status']==='present'?'present':'absent'?>"><?= $r['status']==='present'?'✅ Present':'❌ Absent' ?></span></td>
    </tr>
    <?php endwhile; endif; ?>
    </tbody>
  </table>
</div>

<?php require_once 'footer.php'; ?>