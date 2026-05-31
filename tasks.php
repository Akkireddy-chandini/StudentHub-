<?php
require_once 'header.php';
require_once 'config.php';

$tasks = $conn->query("SELECT t.*, u.name as creator FROM tasks t JOIN users u ON t.created_by=u.id ORDER BY t.due_date ASC, FIELD(t.priority,'high','medium','low')");
$today = date('Y-m-d');
?>

<div class="section-header">
  <h2><i class="fas fa-tasks" style="color:var(--acc)"></i> Tasks</h2>
  <span class="badge badge-blue"><?= $tasks->num_rows ?> tasks</span>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.25rem">
<?php if ($tasks->num_rows === 0): ?>
  <div class="empty-state"><i class="fas fa-clipboard-list"></i><p>No tasks assigned yet.</p></div>
<?php else: while($t=$tasks->fetch_assoc()):
  $overdue = $t['due_date'] && $t['due_date'] < $today;
  $due_soon = $t['due_date'] && $t['due_date'] <= date('Y-m-d', strtotime('+3 days')) && !$overdue;
?>
  <div class="dash-card <?= $t['priority']==='high'?'amber':($t['priority']==='medium'?'purple':'blue') ?>" style="cursor:default">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:.8rem">
      <span class="badge badge-<?= $t['priority'] ?>"><?= ucfirst($t['priority']) ?></span>
      <?php if($overdue): ?><span class="badge" style="background:rgba(239,68,68,0.15);color:var(--danger)"><i class="fas fa-exclamation-circle"></i> Overdue</span>
      <?php elseif($due_soon): ?><span class="badge badge-medium"><i class="fas fa-clock"></i> Due Soon</span>
      <?php endif; ?>
    </div>
    <h3 style="font-family:'Syne',sans-serif;font-size:1rem;font-weight:700;margin-bottom:.5rem"><?= htmlspecialchars($t['title']) ?></h3>
    <?php if($t['description']): ?>
    <p style="font-size:.84rem;color:var(--muted);line-height:1.6;margin-bottom:.8rem"><?= nl2br(htmlspecialchars($t['description'])) ?></p>
    <?php endif; ?>
    <div style="display:flex;align-items:center;justify-content:space-between;font-size:.78rem;color:var(--muted)">
      <span><i class="fas fa-user"></i> <?= htmlspecialchars($t['creator']) ?></span>
      <?php if($t['due_date']): ?>
      <span><i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($t['due_date'])) ?></span>
      <?php endif; ?>
    </div>
  </div>
<?php endwhile; endif; ?>
</div>

<?php require_once 'footer.php'; ?>