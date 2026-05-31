<?php
session_start();
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($conn, $_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        if ($user['role'] === 'faculty') {
            header("Location: faculty_dashboard.php");
        } else {
            header("Location: student_dashboard.php");
        }
        exit;
    } else {
        $error = 'Invalid email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — StudentHub</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  :root {
    --c1:#0a0a1a; --acc:#4f8ef7; --acc2:#7c3aed; --acc3:#06b6d4;
    --glass:rgba(255,255,255,0.05); --glass-b:rgba(255,255,255,0.09);
    --text:#e8eaf6; --muted:rgba(232,234,246,0.55);
    --glow:rgba(79,142,247,0.3);
  }
  *,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
  html,body{min-height:100%;font-family:'DM Sans',sans-serif;background:var(--c1);color:var(--text);}
  body{
    display:flex;flex-direction:column;align-items:center;justify-content:center;
    min-height:100vh;padding:2rem 1rem;
    background:radial-gradient(ellipse 80% 60% at 30% 20%,rgba(79,142,247,0.18) 0%,transparent 60%),
               radial-gradient(ellipse 60% 50% at 80% 80%,rgba(124,58,237,0.15) 0%,transparent 55%),
               var(--c1);
  }
  .brand{font-family:'Syne',sans-serif;font-size:1.6rem;font-weight:800;margin-bottom:2rem;text-align:center;
    background:linear-gradient(135deg,var(--acc),var(--acc3));-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
  .card{
    width:100%;max-width:440px;
    background:var(--glass);border:1px solid var(--glass-b);border-radius:24px;
    padding:2.5rem;backdrop-filter:blur(20px);
    box-shadow:0 24px 80px rgba(0,0,0,0.4),0 0 0 1px var(--glass-b);
    animation:slideUp .6s ease both;
  }
  @keyframes slideUp{from{opacity:0;transform:translateY(30px);}to{opacity:1;transform:none;}}
  .card-title{font-family:'Syne',sans-serif;font-size:1.5rem;font-weight:800;margin-bottom:.3rem;}
  .card-sub{color:var(--muted);font-size:.88rem;margin-bottom:2rem;}
  .error-box{background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:10px;
    padding:.75rem 1rem;color:#f87171;font-size:.85rem;margin-bottom:1.2rem;display:flex;align-items:center;gap:.5rem;}
  .form-group{margin-bottom:1.3rem;position:relative;}
  .form-label{display:block;font-size:.82rem;font-weight:500;color:var(--muted);margin-bottom:.5rem;letter-spacing:.3px;}
  .form-input{
    width:100%;padding:.85rem 1rem .85rem 2.8rem;
    background:rgba(255,255,255,0.04);border:1px solid var(--glass-b);border-radius:12px;
    color:var(--text);font-family:'DM Sans',sans-serif;font-size:.95rem;
    outline:none;transition:all .3s;
  }
  .form-input:focus{border-color:var(--acc);box-shadow:0 0 0 3px rgba(79,142,247,0.12);background:rgba(79,142,247,0.04);}
  .input-icon{position:absolute;left:.9rem;top:50%;transform:translateY(-50%);color:var(--muted);font-size:.85rem;margin-top:.65rem;}
  .pwd-toggle{position:absolute;right:.9rem;top:50%;transform:translateY(-50%);margin-top:.65rem;
    background:none;border:none;color:var(--muted);cursor:pointer;font-size:.85rem;transition:color .2s;}
  .pwd-toggle:hover{color:var(--acc);}
  .btn-submit{
    width:100%;padding:.9rem;border:none;border-radius:12px;
    background:linear-gradient(135deg,var(--acc),var(--acc2));
    color:#fff;font-size:1rem;font-weight:600;font-family:'DM Sans',sans-serif;
    cursor:pointer;transition:all .3s;box-shadow:0 8px 24px var(--glow);margin-top:.5rem;
    position:relative;overflow:hidden;
  }
  .btn-submit:hover{transform:translateY(-2px);box-shadow:0 12px 36px var(--glow);}
  .btn-submit:active{transform:translateY(0);}
  .footer-link{text-align:center;margin-top:1.5rem;font-size:.86rem;color:var(--muted);}
  .footer-link a{color:var(--acc);text-decoration:none;font-weight:500;}
  .footer-link a:hover{text-decoration:underline;}
  .back-link{text-align:center;margin-top:1rem;}
  .back-link a{color:var(--muted);font-size:.82rem;text-decoration:none;transition:color .2s;}
  .back-link a:hover{color:var(--text);}
</style>
</head>
<body>
<div class="brand">StudentHub</div>
<div class="card">
  <div class="card-title">Welcome back</div>
  <div class="card-sub">Sign in to your account to continue</div>
  <?php if($error): ?><div class="error-box"><i class="fas fa-exclamation-circle"></i><?= $error ?></div><?php endif; ?>
  <form method="POST">
    <div class="form-group">
      <label class="form-label">Email Address</label>
      <i class="fas fa-envelope input-icon"></i>
      <input type="email" name="email" class="form-input" placeholder="you@example.com" required value="<?= htmlspecialchars($_POST['email']??'') ?>">
    </div>
    <div class="form-group">
      <label class="form-label">Password</label>
      <i class="fas fa-lock input-icon"></i>
      <input type="password" name="password" id="pwd" class="form-input" placeholder="Enter your password" required>
      <button type="button" class="pwd-toggle" onclick="togglePwd()"><i class="fas fa-eye" id="eyeIcon"></i></button>
    </div>
    <button type="submit" class="btn-submit"><i class="fas fa-sign-in-alt"></i> Sign In</button>
  </form>
  <div class="footer-link">Don't have an account? <a href="register.php">Create one</a></div>
</div>
<div class="back-link"><a href="index.php"><i class="fas fa-arrow-left"></i> Back to Home</a></div>
<script>
function togglePwd(){
  const p=document.getElementById('pwd'), i=document.getElementById('eyeIcon');
  p.type = p.type==='password'?'text':'password';
  i.classList.toggle('fa-eye'); i.classList.toggle('fa-eye-slash');
}
</script>
</body>
</html>