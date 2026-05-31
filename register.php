<?php
session_start();
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($conn, $_POST['name'] ?? '');
    $email = sanitize($conn, $_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';
    $role = in_array($_POST['role']??'', ['student','faculty']) ? $_POST['role'] : 'student';

    if (!$name || !$email || !$password) {
        $error = 'All fields are required.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = 'Email is already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hash, $role);
            if ($stmt->execute()) {
                $success = 'Account created! <a href="login.php">Sign in now</a>.';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register — StudentHub</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  :root {
    --c1:#0a0a1a; --acc:#4f8ef7; --acc2:#7c3aed; --acc3:#06b6d4;
    --glass:rgba(255,255,255,0.05); --glass-b:rgba(255,255,255,0.09);
    --text:#e8eaf6; --muted:rgba(232,234,246,0.55); --glow:rgba(79,142,247,0.3);
  }
  *,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
  html{min-height:100%;scroll-behavior:smooth;}
  body{
    min-height:100vh;font-family:'DM Sans',sans-serif;
    background:radial-gradient(ellipse 80% 60% at 70% 10%,rgba(124,58,237,0.18) 0%,transparent 60%),
               radial-gradient(ellipse 60% 50% at 10% 80%,rgba(79,142,247,0.15) 0%,transparent 55%),
               var(--c1);
    color:var(--text);
    display:flex;flex-direction:column;align-items:center;
    padding:2rem 1rem;
    overflow-y:auto;
  }
  .brand{font-family:'Syne',sans-serif;font-size:1.6rem;font-weight:800;margin-bottom:1.5rem;text-align:center;
    background:linear-gradient(135deg,var(--acc2),var(--acc));-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
  .card{
    width:100%;max-width:460px;
    background:var(--glass);border:1px solid var(--glass-b);border-radius:24px;
    padding:2.5rem;backdrop-filter:blur(20px);
    box-shadow:0 24px 80px rgba(0,0,0,0.4);
    animation:slideUp .6s ease both;
    margin-bottom:1.5rem;
  }
  @keyframes slideUp{from{opacity:0;transform:translateY(30px);}to{opacity:1;transform:none;}}
  .card-title{font-family:'Syne',sans-serif;font-size:1.5rem;font-weight:800;margin-bottom:.3rem;}
  .card-sub{color:var(--muted);font-size:.88rem;margin-bottom:2rem;}
  .alert{border-radius:10px;padding:.75rem 1rem;font-size:.85rem;margin-bottom:1.2rem;display:flex;align-items:center;gap:.5rem;}
  .alert-error{background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#f87171;}
  .alert-success{background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);color:#34d399;}
  .alert-success a{color:#34d399;}
  .form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
  .form-group{margin-bottom:1.2rem;position:relative;}
  .form-label{display:block;font-size:.82rem;font-weight:500;color:var(--muted);margin-bottom:.5rem;letter-spacing:.3px;}
  .form-input{
    width:100%;padding:.85rem 1rem .85rem 2.8rem;
    background:rgba(255,255,255,0.04);border:1px solid var(--glass-b);border-radius:12px;
    color:var(--text);font-family:'DM Sans',sans-serif;font-size:.93rem;
    outline:none;transition:all .3s;appearance:none;
  }
  .form-input:focus{border-color:var(--acc);box-shadow:0 0 0 3px rgba(79,142,247,0.12);background:rgba(79,142,247,0.04);}
  .input-icon{position:absolute;left:.9rem;top:50%;transform:translateY(-50%);color:var(--muted);font-size:.85rem;margin-top:.65rem;}
  .pwd-toggle{position:absolute;right:.9rem;top:50%;transform:translateY(-50%);margin-top:.65rem;
    background:none;border:none;color:var(--muted);cursor:pointer;font-size:.85rem;transition:color .2s;}
  .pwd-toggle:hover{color:var(--acc);}
  .role-group{display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:1.2rem;}
  .role-btn{
    padding:.75rem;border:1px solid var(--glass-b);border-radius:12px;
    background:transparent;color:var(--muted);cursor:pointer;font-family:'DM Sans',sans-serif;
    font-size:.88rem;font-weight:500;transition:all .25s;text-align:center;
  }
  .role-btn.active{border-color:var(--acc);background:rgba(79,142,247,0.1);color:var(--acc);}
  .role-btn i{margin-right:.4rem;}
  #roleInput{display:none;}
  .btn-submit{
    width:100%;padding:.9rem;border:none;border-radius:12px;
    background:linear-gradient(135deg,var(--acc2),var(--acc));
    color:#fff;font-size:1rem;font-weight:600;font-family:'DM Sans',sans-serif;
    cursor:pointer;transition:all .3s;box-shadow:0 8px 24px var(--glow);
    margin-top:.5rem;
  }
  .btn-submit:hover{transform:translateY(-2px);box-shadow:0 12px 36px var(--glow);}
  .footer-link{text-align:center;margin-top:1.5rem;font-size:.86rem;color:var(--muted);}
  .footer-link a{color:var(--acc);text-decoration:none;font-weight:500;}
  .back-link{text-align:center;margin-bottom:1rem;}
  .back-link a{color:var(--muted);font-size:.82rem;text-decoration:none;transition:color .2s;}
  .back-link a:hover{color:var(--text);}
  @media(max-width:460px){.form-row{grid-template-columns:1fr;}}
</style>
</head>
<body>
<div class="brand">StudentHub</div>
<div class="card">
  <div class="card-title">Create account</div>
  <div class="card-sub">Join thousands of students and faculty</div>

  <?php if($error): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i><?= $error ?></div><?php endif; ?>
  <?php if($success): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i><?= $success ?></div><?php endif; ?>

  <form method="POST">
    <div class="form-group">
      <label class="form-label">Full Name</label>
      <i class="fas fa-user input-icon"></i>
      <input type="text" name="name" class="form-input" placeholder="John Doe" required value="<?= htmlspecialchars($_POST['name']??'') ?>">
    </div>
    <div class="form-group">
      <label class="form-label">Email Address</label>
      <i class="fas fa-envelope input-icon"></i>
      <input type="email" name="email" class="form-input" placeholder="you@example.com" required value="<?= htmlspecialchars($_POST['email']??'') ?>">
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Password</label>
        <i class="fas fa-lock input-icon"></i>
        <input type="password" name="password" id="pwd1" class="form-input" placeholder="Min 6 chars" required>
        <button type="button" class="pwd-toggle" onclick="toggle('pwd1','eye1')"><i class="fas fa-eye" id="eye1"></i></button>
      </div>
      <div class="form-group">
        <label class="form-label">Confirm</label>
        <i class="fas fa-lock input-icon"></i>
        <input type="password" name="confirm" id="pwd2" class="form-input" placeholder="Repeat password" required>
        <button type="button" class="pwd-toggle" onclick="toggle('pwd2','eye2')"><i class="fas fa-eye" id="eye2"></i></button>
      </div>
    </div>

    <label class="form-label" style="margin-bottom:.6rem;">I am a</label>
    <div class="role-group">
      <button type="button" class="role-btn active" id="btn-student" onclick="setRole('student')"><i class="fas fa-user-graduate"></i>Student</button>
      <button type="button" class="role-btn" id="btn-faculty" onclick="setRole('faculty')"><i class="fas fa-chalkboard-teacher"></i>Faculty</button>
    </div>
    <input type="hidden" name="role" id="roleInput" value="student">

    <button type="submit" class="btn-submit"><i class="fas fa-user-plus"></i> Create Account</button>
  </form>
  <div class="footer-link">Already have an account? <a href="login.php">Sign in</a></div>
</div>
<div class="back-link"><a href="index.php"><i class="fas fa-arrow-left"></i> Back to Home</a></div>
<script>
function toggle(id, icon){
  const p=document.getElementById(id), i=document.getElementById(icon);
  p.type=p.type==='password'?'text':'password';
  i.classList.toggle('fa-eye'); i.classList.toggle('fa-eye-slash');
}
function setRole(role){
  document.getElementById('roleInput').value=role;
  document.getElementById('btn-student').classList.toggle('active', role==='student');
  document.getElementById('btn-faculty').classList.toggle('active', role==='faculty');
}
</script>
</body>
</html>