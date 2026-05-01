<?php
include "../config/db.php";
$uid = $_SESSION['user_id'];
$id = $_GET['id'];

mysqli_query($conn,"DELETE FROM tweets WHERE id=$id AND user_id=$uid");
header("Location: ../home.php");
