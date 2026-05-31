<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Profile</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="dashboard">

<div class="sidebar">

<h2>StudentHub</h2>

<ul>

<li>
<a href="student_dashboard.php">
<i class="fa-solid fa-house"></i>
<span>Dashboard</span>
</a>
</li>

<li>
<a href="attendance.php">
<i class="fa-solid fa-calendar-check"></i>
<span>Attendance</span>
</a>
</li>

<li>
<a href="notes.php">
<i class="fa-solid fa-book"></i>
<span>Notes</span>
</a>
</li>

<li>
<a href="tasks.php">
<i class="fa-solid fa-list-check"></i>
<span>Tasks</span>
</a>
</li>

<li>
<a href="timetable.php">
<i class="fa-solid fa-table"></i>
<span>Timetable</span>
</a>
</li>

<li>
<a href="profile.php" class="active">
<i class="fa-solid fa-user"></i>
<span>Profile</span>
</a>
</li>

</ul>

</div>

<div class="main-content">

<div class="topbar">

<h1>Student Profile</h1>

<a href="index.php" class="btn">
Logout
</a>

</div>

<div class="glass-card" style="padding:40px; max-width:700px;">

<div style="text-align:center; margin-bottom:35px;">

<div style="
width:120px;
height:120px;
border-radius:50%;
background:linear-gradient(45deg,#6c63ff,#00d4ff);
display:flex;
align-items:center;
justify-content:center;
font-size:50px;
margin:auto;
margin-bottom:20px;
">
<i class="fa-solid fa-user"></i>
</div>

<h2>Student Name</h2>

<p style="color:#cfcfcf;">
student@college.com
</p>

</div>

<div class="table-container">

<table>

<tr>
<th>Field</th>
<th>Details</th>
</tr>

<tr>
<td>Department</td>
<td>MCA</td>
</tr>

<tr>
<td>Year</td>
<td>2nd Year</td>
</tr>

<tr>
<td>Roll Number</td>
<td>23MCA101</td>
</tr>

<tr>
<td>Phone</td>
<td>9876543210</td>
</tr>

<tr>
<td>Attendance</td>
<td>82%</td>
</tr>

</table>

</div>

<br>

<form>

<div class="input-group">
<input type="text" placeholder="Update Name">
</div>

<div class="input-group">
<input type="email" placeholder="Update Email">
</div>

<div class="input-group">
<input type="password" placeholder="Update Password">
</div>

<button class="btn">
Update Profile
</button>

</form>

</div>

</div>

</div>

</body>
</html>