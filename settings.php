<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user'])){
header('Location: login.php');
}

$user=$_SESSION['user'];

if(isset($_POST['update'])){

$name=$_POST['name'];
$email=$_POST['email'];

$sql="UPDATE users SET
name='$name',
email='$email'
WHERE id='{$user['id']}'";

mysqli_query($conn,$sql);

$_SESSION['user']['name']=$name;
$_SESSION['user']['email']=$email;

header('Location: settings.php');
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Settings</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard">
<?php include 'sidebar.php'; ?>

<div class="main-content">
<h1>Account Settings</h1>

<div class="glass-card" style="padding:35px; max-width:650px; margin-top:30px;">

<form method="POST">

<div class="input-group">
<input type="text" name="name" value="<?php echo $user['name']; ?>">
</div>

<div class="input-group">
<input type="email" name="email" value="<?php echo $user['email']; ?>">
</div>

<button class="btn" name="update">Update Profile</button>

</form>

</div>
</div>
</div>

</body>
</html>