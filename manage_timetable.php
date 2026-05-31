<?php
require_once 'header.php';
require_once 'config.php';
if ($role !== 'faculty') { header("Location: student_dashboard.php"); exit; }

$uid = $_SESSION['user_id'];
$msg = $err = '';

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $subject = sanitize($conn, $_POST['subject']??'');
    $day = sanitize($conn, $_POST['day']??'');
    $start = sanitize($conn, $_POST['start_time']??'');
    $end = sanitize($conn, $_POST['end_time']??'');
    $room = sanitize($conn, $_POST['room']??'');

    $valid_days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

    if (!$subject || !$day || !$start || !$end) {
        $err = 'Subject, day, and times are required.';
    } elseif (!in_array($day, $valid_days)) {
        $err = 'Invalid day.';
    } elseif ($start >= $end) {
        $err = 'End time must be after start time.';
    } else {
        $stmt = $conn->prepare("INSERT INTO timetable (subject,day,start_time,end_time,faculty_id,room) VALUES(?,?,?,?,?,?)");
        $stmt->bind_param("ssssis",$subject,$day,$start,$end,$uid,$room);

        $stmt->execute() ? $msg='Class added to timetable!' : ($err='Insert failed: '.$conn->error);
    }
}

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM timetable WHERE id=".(int)$_GET['delete']." AND faculty_id=$uid");
    $msg = 'Entry deleted.';
}

$days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
$colors = [
    'Mathematics'=>'tt-math',
    'Physics'=>'tt-physics',
    'Chemistry'=>'tt-chemistry',
    'English'=>'tt-english',
    'Computer Science'=>'tt-cs'
];

/* ✅ FIXED QUERY */
$tt = $conn->query("
SELECT * FROM timetable 
WHERE faculty_id=$uid 
ORDER BY 
FIELD(day,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'),
start_time
");?>

<?php if($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i><?=$msg?></div><?php endif; ?>
<?php if($err): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i><?=$err?></div><?php endif; ?>

<div class="form-card">
  <h2 style="font-family:'Syne',sans-serif;font-size:1rem;font-weight:700;margin-bottom:1.2rem">
    <i class="fas fa-calendar-plus" style="color:var(--warning)"></i> Add Class to Timetable
  </h2>

  <form method="POST">
    <div class="form-grid">
      <div class="fg">
        <label>Subject *</label>
        <input type="text" name="subject" placeholder="e.g. Mathematics" required>
      </div>

      <div class="fg">
        <label>Day *</label>
        <select name="day" required>
          <option value="">Select Day</option>
          <?php foreach($days as $d): ?>
            <option value="<?=$d?>"><?=$d?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="fg">
        <label>Start Time *</label>
        <input type="time" name="start_time" required>
      </div>

      <div class="fg">
        <label>End Time *</label>
        <input type="time" name="end_time" required>
      </div>

      <div class="fg">
        <label>Room / Location</label>
        <input type="text" name="room" placeholder="e.g. Room 101">
      </div>
    </div>

    <div style="margin-top:1rem">
      <button type="submit" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Class
      </button>
    </div>
  </form>
</div>

<div class="section-header">
  <h2><i class="fas fa-calendar-week" style="color:var(--acc)"></i> Your Timetable</h2>
</div>

<div class="table-wrapper">
  <table>
    <thead>
      <tr>
        <th>Day</th>
        <th>Subject</th>
        <th>Start</th>
        <th>End</th>
        <th>Room</th>
        <th>Action</th>
      </tr>
    </thead>

    <tbody>
    <?php if($tt->num_rows===0): ?>
      <tr>
        <td colspan="6">
          <div class="empty-state">
            <i class="fas fa-calendar-times"></i>
            <p>No classes added yet.</p>
          </div>
        </td>
      </tr>
    <?php else: while($r=$tt->fetch_assoc()): 
        $cc=$colors[$r['subject']]??'tt-default'; ?>
      <tr>
        <td><span class="badge badge-blue"><?=$r['day']?></span></td>

        <td>
          <span class="tt-cell <?=$cc?>" style="display:inline-block;padding:.3rem .8rem;border-radius:8px">
            <?=htmlspecialchars($r['subject'])?>
          </span>
        </td>

        <td><?=date('h:i A', strtotime($r['start_time']))?></td>
        <td><?=date('h:i A', strtotime($r['end_time']))?></td>
        <td><?=htmlspecialchars($r['room'])?:'-'?></td>

        <td>
          <a href="manage_timetable.php?delete=<?=$r['id']?>" 
             class="btn btn-danger btn-sm"
             onclick="return confirm('Remove this class?')">
             <i class="fas fa-trash"></i> Remove
          </a>
        </td>
      </tr>
    <?php endwhile; endif; ?>
    </tbody>
  </table>
</div>

<?php require_once 'footer.php'; ?>