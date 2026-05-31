<?php
require_once 'header.php';
require_once 'config.php';
if ($role !== 'faculty') { header("Location: student_dashboard.php"); exit; }

$uid = $_SESSION['user_id'];
$msg = $err = '';

// Upload directory
$upload_dir = 'uploads/';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

// Handle upload
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_FILES['note_file'])) {
    $title = sanitize($conn, $_POST['title']??'');
    $subject = sanitize($conn, $_POST['subject']??'');
    $file = $_FILES['note_file'];

    $allowed = ['pdf','doc','docx','ppt','pptx','txt','png','jpg','jpeg'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!$title) {
        $err = 'Title is required.';
    } elseif ($file['error'] !== UPLOAD_ERR_OK) {
        $err = 'File upload error.';
    } elseif (!in_array($ext, $allowed)) {
        $err = 'File type not allowed.';
    } elseif ($file['size'] > 10*1024*1024) {
        $err = 'File too large (max 10MB).';
    } else {
        $fname = uniqid('note_').'_'.time().'.'.$ext;

        if (move_uploaded_file($file['tmp_name'], $upload_dir.$fname)) {

            $user_id = $_SESSION['user_id'];
            $file_path = $upload_dir.$fname;

            $stmt = $conn->prepare("INSERT INTO notes (title, subject, file_name, file_path, uploaded_by) VALUES (?, ?, ?, ?, ?)");

            $stmt->bind_param("ssssi", $title, $subject, $fname, $file_path, $user_id);

            if ($stmt->execute()) {
                $msg = 'Note uploaded successfully!';
            } else {
                $err = 'Database error.';
            }

        } else {
            $err = 'Failed to save file.';
        }
    }
}
// Handle delete
if (isset($_GET['delete'])) {
    $did = (int)$_GET['delete'];
    $r = $conn->query("SELECT * FROM notes WHERE id=$did AND uploaded_by=$uid")->fetch_assoc();
    if ($r) {
        @unlink($r['file_path']);
        $conn->query("DELETE FROM notes WHERE id=$did");
        $msg = 'Note deleted.';
    }
}

$notes = $conn->query("SELECT * FROM notes WHERE uploaded_by=$uid ORDER BY created_at DESC");
?>

<?php if($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i><?=$msg?></div><?php endif; ?>
<?php if($err): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i><?=$err?></div><?php endif; ?>

<!-- Upload Form -->
<div class="form-card">
  <h2 style="font-family:'Syne',sans-serif;font-size:1rem;font-weight:700;margin-bottom:1.2rem"><i class="fas fa-upload" style="color:var(--acc)"></i> Upload New Note</h2>
  <form method="POST" enctype="multipart/form-data">
    <div class="form-grid">
      <div class="fg">
        <label>Title *</label>
        <input type="text" name="title" placeholder="e.g. Chapter 3 Notes" required>
      </div>
      <div class="fg">
        <label>Subject</label>
        <input type="text" name="subject" placeholder="e.g. Mathematics">
      </div>
      <div class="fg" style="grid-column:1/-1">
        <label>File * (PDF, DOC, PPT, TXT, Image – max 10MB)</label>
        <input type="file" name="note_file" required accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.png,.jpg,.jpeg" style="padding:.6rem">
      </div>
    </div>
    <div style="margin-top:1rem">
      <button type="submit" class="btn btn-primary"><i class="fas fa-cloud-upload-alt"></i> Upload Note</button>
    </div>
  </form>
</div>

<!-- Notes List -->
<div class="section-header">
  <h2><i class="fas fa-list" style="color:var(--acc)"></i> Uploaded Notes</h2>
  <span class="badge badge-blue"><?= $notes->num_rows ?> files</span>
</div>
<div class="table-wrapper">
  <table>
    <thead><tr><th>Title</th><th>Subject</th><th>File</th><th>Date</th><th>Actions</th></tr></thead>
    <tbody>
    <?php if ($notes->num_rows === 0): ?>
    <tr><td colspan="5"><div class="empty-state"><i class="fas fa-inbox"></i><p>No notes uploaded yet.</p></div></td></tr>
    <?php else: while($n=$notes->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($n['title']) ?></td>
      <td><?= $n['subject']?'<span class="badge badge-blue">'.htmlspecialchars($n['subject']).'</span>':'—' ?></td>
      <td><i class="fas fa-file" style="color:var(--acc)"></i> <?= htmlspecialchars($n['file_name']) ?></td>
      <td><?= date('d M Y', strtotime($n['created_at'])) ?></td>
      <td>
        <a href="uploads/<?= htmlspecialchars($n['file_name']) ?>" class="btn btn-success btn-sm" download><i class="fas fa-download"></i></a>
        <a href="manage_notes.php?delete=<?= $n['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this note?')"><i class="fas fa-trash"></i></a>
      </td>
    </tr>
    <?php endwhile; endif; ?>
    </tbody>
  </table>
</div>

<?php require_once 'footer.php'; ?>