<?php
include "../config/db.php";

$uid = $_SESSION['user_id'];

$img = $_FILES['profile_pic']['name'];
$tmp = $_FILES['profile_pic']['tmp_name'];

$new_name = time() . "_" . $img;

move_uploaded_file($tmp, "../assets/profile_pics/" . $new_name);

mysqli_query(
    $conn,
    "UPDATE users SET profile='$new_name' WHERE id=$uid"
);

header("Location: ../profile.php");
