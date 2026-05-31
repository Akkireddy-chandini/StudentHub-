<?php
require_once 'header.php';
require_once 'config.php';
if ($role !== 'faculty') { header("Location: student_dashboard.php"); exit; }

$uid = $_SESSION['user_id'];
$msg = $err = '';

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $title = sanitize($conn, $_POST['title']??'');
    $desc = sanitize($conn, $_POST['description']??'');
    $due = sanitize($conn, $_POST['due_date']??'');
    $priority = in_array($_POST['priority']??'',['low','medium','high'])?$_POST['priority']:'medium';

    if (!$title) {
        $err = 'Task title is required.';
    } else {
        $due_val = $due ?: null;
        $stmt = $conn->prepare("INSERT INTO tasks (title,description,due_date,priority,created_by) VALUES(?,?,?,?,?)");
        $stmt->bind_param("ssssi",$title,$desc,$due_val,$priority,$uid);
        $stmt->execute() ? $msg='Task created!' : ($err='Failed to create task.');
    }
}

if (isset($_GET['delete'])) {
    $did = (int)$_GET['delete'];
    $conn->query("DELETE FROM tasks WHERE id=$did AND created_by=$uid");
    $msg = 'Task deleted.';
}

$tasks = $conn->query("SELECT * FROM tasks WHERE created_by=$uid ORDER BY created_at DESC");
?>

<?php if($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i><?=$msg?></div><?php endif; ?>
<?php if($err): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i><?=$err?></div><?php endif; ?>

<div class="form-card">
  <h2 style="font-family:'Syne',sans-serif;font-size:1rem;font-weight:700;margin-bottom:1.2rem"><i class="fas fa-plus-circle" style="color:var(--acc2)"></i> Create New Task</h2>
  <form method="POST">
    <div class="form-grid">
      <div class="fg" style="grid-column:1/-1">
        <label>Task Title *</label>
        <input type="text" name="title" placeholder="e.g. Submit Assignment 3" required>
      </div>
      <div class="fg" style="grid-column:1/-1">
        <label>Description</label>
        <textarea name="description" placeholder="Task details, instructions..."></textarea>
      </div>
      <div class="fg">
        <label>Due Date</label>
        <input type="date" name="due_date" min="<?= date('Y-m-d') ?>">
      </div>
      <div class="fg">
        <label>Priority</label>
        <select name="priority">
          <option value="low">🟢 Low</option>
          <option value="medium" selected>🟡 Medium</option>
          <option value="high">🔴 High</option>
        </select>
      </div>
    </div>
    <div style="margin-top:1rem">
      <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Create Task</button>
    </div>
  </form>
</div>

<div class="section-header">
  <h2><i class="fas fa-list" style="color:var(--acc)"></i> All Tasks</h2>
  <span class="badge badge-blue"><?= $tasks->num_rows ?></span>
</div>
<div class="table-wrapper">
  <table>
    <thead><tr><th>Title</th><th>Priority</th><th>Due Date</th><th>Created</th><th>Actions</th></tr></thead>
    <tbody>
    <?php if ($tasks->num_rows===0): ?>
    <tr><td colspan="5"><div class="empty-state"><i class="fas fa-clipboard-list"></i><p>No tasks yet.</p></div></td></tr>
    <?php else: while($t=$tasks->fetch_assoc()): ?>
    <tr>
      <td>
        <div style="font-weight:600"><?= htmlspecialchars($t['title']) ?></div>
        <?php if($t['description']): ?><div style="font-size:.78rem;color:var(--muted);margin-top:.2rem"><?= htmlspecialchars(substr($t['description'],0,60)) ?>...</div><?php endif; ?>
      </td>
      <td><span class="badge badge-<?= $t['priority'] ?>"><?= ucfirst($t['priority']) ?></span></td>
      <td><?= $t['due_date'] ? date('d M Y', strtotime($t['due_date'])) : '—' ?></td>
      <td><?= date('d M Y', strtotime($t['created_at'])) ?></td>
      <td><a href="manage_tasks.php?delete=<?=$t['id']?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this task?')"><i class="fas fa-trash"></i> Delete</a></td>
    </tr>
    <?php endwhile; endif; ?>
    </tbody>
  </table>
</div>

<?php require_once 'footer.php'; ?>