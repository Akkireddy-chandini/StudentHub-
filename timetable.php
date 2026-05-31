<?php
require_once 'header.php';
require_once 'config.php';

$days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

$colors = [
    'Mathematics' => 'tt-math',
    'Physics' => 'tt-physics',
    'Chemistry' => 'tt-chemistry',
    'English' => 'tt-english',
    'Computer Science' => 'tt-cs'
];

// Build structured timetable
$tt_data = [];
$res = $conn->query("
    SELECT t.*, u.name as faculty_name 
    FROM timetable t 
    JOIN users u ON t.faculty_id = u.id 
    ORDER BY FIELD(day,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'), start_time
");

while ($r = $res->fetch_assoc()) {
    $tt_data[$r['day']][] = $r;
}

$today = date('l');
?>

<div class="section-header" style="margin-bottom:1.5rem">
  <h2><i class="fas fa-calendar-week" style="color:var(--acc)"></i> Weekly Timetable</h2>
  <span class="badge badge-blue">
    <i class="fas fa-clock"></i> Today: <?= $today ?>
  </span>
</div>

<!-- Cards View -->
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.25rem;margin-bottom:2rem">
<?php foreach ($days as $day): ?>
  <div class="dash-card <?= ($day === $today) ? 'blue' : '' ?>" 
       style="<?= ($day === $today) ? 'border-color:rgba(79,142,247,0.4);' : '' ?>">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
      <h3 style="font-family:'Syne',sans-serif;font-size:.95rem;font-weight:700;
                 color:<?= ($day === $today) ? 'var(--acc)' : 'var(--text)' ?>">
        <?= $day ?>
      </h3>

      <?php if ($day === $today): ?>
        <span class="badge badge-blue">Today</span>
      <?php endif; ?>
    </div>

    <?php if (empty($tt_data[$day])): ?>
      <div style="text-align:center;color:var(--muted);font-size:.82rem;padding:.5rem">
        <i class="fas fa-coffee"></i> No classes
      </div>
    <?php else: ?>
      <?php foreach ($tt_data[$day] as $cl): 
          $colorClass = $colors[$cl['subject']] ?? 'tt-default';
      ?>
        <div class="tt-cell <?= $colorClass ?>" style="margin-bottom:.5rem">
          <div style="font-weight:700"><?= htmlspecialchars($cl['subject']) ?></div>
          <div style="font-size:.72rem;opacity:.85;margin-top:.2rem">
            <i class="fas fa-clock"></i>
            <?= date('h:i A', strtotime($cl['start_time'])) ?> – 
            <?= date('h:i A', strtotime($cl['end_time'])) ?>
            &nbsp;|&nbsp;
            <i class="fas fa-door-open"></i> <?= htmlspecialchars($cl['room']) ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

  </div>
<?php endforeach; ?>
</div>

<!-- Table View -->
<div class="section-header">
  <h2><i class="fas fa-table" style="color:var(--acc3)"></i> Grid View</h2>
</div>

<div class="table-wrapper" style="overflow-x:auto">
<table class="tt-table">
<thead>
<tr>
  <th>Day</th>
  <th>09:00</th>
  <th>10:00</th>
  <th>11:00</th>
  <th>12:00</th>
  <th>Afternoon</th>
</tr>
</thead>

<tbody>
<?php foreach ($days as $day): ?>
<tr style="<?= ($day === $today) ? 'background:rgba(79,142,247,0.05)' : '' ?>">

<td style="font-weight:700;font-family:'Syne',sans-serif;
           color:<?= ($day === $today) ? 'var(--acc)' : 'var(--text)' ?>">

    <?= $day ?>

    <?php if ($day === $today): ?>
        <span class="badge badge-blue" style="font-size:.65rem;">Today</span>
    <?php endif; ?>

</td>

<?php
$slots = ['09:00:00','10:00:00','11:00:00','12:00:00','13:00:00'];

foreach ($slots as $slot):
    $found = false;

    if (isset($tt_data[$day])):
        foreach ($tt_data[$day] as $cl):
            if ($cl['start_time'] === $slot):
                $found = true;
                $cc = $colors[$cl['subject']] ?? 'tt-default';
?>
<td>
    <div class="tt-cell <?= $cc ?>">
        <?= htmlspecialchars($cl['subject']) ?><br>
        <span style="font-size:.68rem;opacity:.8">
            <?= date('h:i', strtotime($cl['start_time'])) ?>
        </span>
    </div>
</td>
<?php
                break;
            endif;
        endforeach;
    endif;

    if (!$found):
?>
<td><span class="tt-empty">—</span></td>
<?php endif; endforeach; ?>

</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<?php require_once 'footer.php'; ?>