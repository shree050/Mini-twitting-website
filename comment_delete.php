<?php
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$uid = $_SESSION['user_id'];
$cid = $_GET['id'];

// delete only if comment belongs to logged-in user
mysqli_query(
    $conn,
    "DELETE FROM comments WHERE id=$cid AND user_id=$uid"
);

header("Location: ../home.php");
exit;
