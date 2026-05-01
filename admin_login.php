<?php
session_start();
include "../config/db.php";

$error="";

if(isset($_POST['login'])){

$username=$_POST['username'];
$password=$_POST['password'];

$query=mysqli_query($conn,"SELECT * FROM admin WHERE username='$username' AND password='$password'");

if(mysqli_num_rows($query)==1){

$admin=mysqli_fetch_assoc($query);

$_SESSION['admin_id']=$admin['id'];

header("Location: admin_dashboard.php");
exit();

}else{

$error="Invalid Login";

}

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>

<style>

body{
font-family:Arial;
background:#f2f2f2;
}

.login-box{
width:350px;
margin:120px auto;
background:white;
padding:30px;
border-radius:8px;
box-shadow:0 0 10px rgba(0,0,0,0.2);
}

input{
width:100%;
padding:10px;
margin-top:10px;
}

button{
width:100%;
padding:10px;
margin-top:15px;
background:#1da1f2;
color:white;
border:none;
cursor:pointer;
}

.error{
color:red;
}

</style>

</head>

<body>

<div class="login-box">

<h2>Admin Login</h2>

<form method="POST">

<input type="text" name="username" placeholder="Username" required>

<input type="password" name="password" placeholder="Password" required>

<button name="login">Login</button>

</form>

<p class="error"><?php echo $error; ?></p>

</div>

</body>
</html>