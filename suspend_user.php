<?php
include "../config/db.php";

$id = (int)$_GET['id'];

mysqli_query($conn,"UPDATE users SET status='suspended' WHERE id=$id");

header("Location: admin_dashboard.php");