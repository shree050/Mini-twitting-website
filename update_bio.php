<?php
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$uid = $_SESSION['user_id'];
$bio = $_POST['bio'];

mysqli_query(
    $conn,
    "UPDATE users SET bio='$bio' WHERE id=$uid"
);

header("Location: ../profile.php");
exit;
