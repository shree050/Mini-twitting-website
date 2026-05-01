<?php
include "config/db.php";

if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$uid = $_SESSION['user_id'];
$id = (int)$_GET['id'];

/* DELETE ONLY OWN MESSAGE */
mysqli_query($conn,"
    DELETE FROM messages
    WHERE id=$id AND sender_id=$uid
");

/* REDIRECT BACK */
$receiver = isset($_GET['user']) ? (int)$_GET['user'] : 0;

header("Location: messages.php?user=$receiver");
exit;