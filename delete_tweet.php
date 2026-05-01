<?php
session_start();
include "../config/db.php";

$id=$_GET['id'];

mysqli_query($conn,"DELETE FROM tweets WHERE id='$id'");

header("Location: admin_dashboard.php");
?>