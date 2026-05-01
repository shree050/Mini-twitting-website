<?php
include "config/db.php";
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: auth/login.php");
  exit;
}

$uid = (int)$_SESSION['user_id'];

/* Fetch user */
$user = mysqli_fetch_assoc(
  mysqli_query($conn, "SELECT * FROM users WHERE id=$uid")
);

/* UPDATE PROFILE */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $bio = mysqli_real_escape_string($conn, $_POST['bio'] ?? '');

  if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0){

    $file = $_FILES['profile_pic']['name'];
$tmp = $_FILES['profile_pic']['tmp_name'];

    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif','webp'];

    if(in_array($ext,$allowed)){

      $newname = "user_".$uid."_".time().".".$ext;
      $path = "assets/profile_pics/".$newname;

      if(move_uploaded_file($tmp,$path)){

        if(!empty($user['profile']) && $user['profile'] != "default.png"){
          $old = "assets/profile_pics/".$user['profile'];
          if(file_exists($old)){
            unlink($old);
          }
        }

        mysqli_query($conn,"UPDATE users SET profile='$newname', bio='$bio' WHERE id=$uid");
      }
    }

  } else {
    mysqli_query($conn,"UPDATE users SET bio='$bio' WHERE id=$uid");
  }

  header("Location: profile.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Profile</title>

<style>
* {
  box-sizing: border-box;
  font-family: "Segoe UI", Arial, sans-serif;
}

body {
  margin: 0;
  background: #f5f7fa;
}

/* TOPBAR */
.topbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 20px;
  background: #000;
  color: #fff;
}

.top-nav a {
  margin-left: 15px;
  color: white;
  text-decoration: none;
  font-weight: bold;
}

/* CONTAINER */
.container {
  max-width: 500px;
  margin: 40px auto;
}

/* CARD */
.profile-card {
  background: #fff;
  padding: 25px;
  border-radius: 14px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
  text-align: center;
}

/* IMAGE */
.profile-pic {
  width: 110px;
  height: 110px;
  border-radius: 50%;
  object-fit: cover;
  margin-bottom: 10px;
}

/* INPUT FILE */
input[type="file"] {
  margin-top: 10px;
}

/* TEXTAREA */
textarea {
  width: 100%;
  padding: 12px;
  border-radius: 8px;
  border: 1px solid #ccc;
  resize: none;
  font-size: 14px;
}

/* BUTTON */
button {
  margin-top: 15px;
  padding: 10px 20px;
  border-radius: 20px;
  border: none;
  background: #1da1f2;
  color: white;
  font-weight: bold;
  cursor: pointer;
}

button:hover {
  background: #0d8ae5;
}

/* LABEL */
label {
  display: block;
  margin-top: 10px;
  text-align: left;
}
</style>

</head>

<body>

<header class="topbar">
  <div><b>Mini Sweeters</b></div>

  <div class="top-nav">
    <a href="profile.php">Back</a>
    <a href="auth/logout.php">Logout</a>
  </div>
</header>

<div class="container">

<div class="profile-card">

<h2>Edit Profile</h2>

<form method="post" enctype="multipart/form-data">

<img id="preview"
src="assets/profile_pics/<?= !empty($user['profile']) ? htmlspecialchars($user['profile']) : 'default.png' ?>"
class="profile-pic">

<label><b>Profile Picture</b></label>
<input type="file" name="profile_pic" accept="image/*" onchange="previewImage(event)">

<label><b>Bio</b></label>
<textarea name="bio" rows="3" placeholder="Write something about yourself..."><?= htmlspecialchars($user['bio']) ?></textarea>

<button type="submit">Save Changes</button>

</form>

</div>

</div>

<script>
function previewImage(event){
  const img = document.getElementById('preview');
  img.src = URL.createObjectURL(event.target.files[0]);
}
</script>

</body>
</html>