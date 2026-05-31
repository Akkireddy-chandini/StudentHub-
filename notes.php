<?php
require_once 'header.php';
require_once 'config.php';

$notes = $conn->query("SELECT n.*, u.name as uploader FROM notes n JOIN users u ON n.uploaded_by=u.id ORDER BY n.created_at DESC");
?>

<div class="section-header">
  <h2><i class="fas fa-book-open" style="color:var(--acc)"></i> Available Notes</h2>
  <span class="badge badge-blue"><?= $notes->num_rows ?> files</span>
</div>

<div class="table-wrapper">
  <table>
    <thead>
      <tr><th>Title</th><th>Subject</th><th>Uploaded By</th><th>Date</th><th>Download</th></tr>
    </thead>
    <tbody>
    <?php if ($notes->num_rows === 0): ?>
    <tr><td colspan="5"><div class="empty-state"><i class="fas fa-folder-open"></i><p>No notes uploaded yet.</p></div></td></tr>
    <?php else: while($n=$notes->fetch_assoc()): ?>
    <tr>
      <td>
        <div style="display:flex;align-items:center;gap:.6rem">
          <i class="fas fa-file-pdf" style="color:var(--danger);font-size:1.1rem"></i>
          <span><?= htmlspecialchars($n['title']) ?></span>
        </div>
      </td>
      <td><?= $n['subject'] ? '<span class="badge badge-blue">'.htmlspecialchars($n['subject']).'</span>' : '<span class="badge">—</span>' ?></td>
      <td><?= htmlspecialchars($n['uploader']) ?></td>
      <td><?= date('d M Y', strtotime($n['created_at'])) ?></td>
      <td>
        <a href="uploads/<?= htmlspecialchars($n['file_name']) ?>" class="btn btn-success btn-sm" download>
          <i class="fas fa-download"></i> Download
        </a>
      </td>
    </tr>
    <?php endwhile; endif; ?>
    </tbody>
  </table>
</div>

<?php require_once 'footer.php'; ?>