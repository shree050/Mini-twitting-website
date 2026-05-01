<?php
include "../config/db.php";

$id = $_GET['id'];

mysqli_query($conn,"DELETE FROM comments WHERE id='$id'");

header("Location: admin_dashboard.php");