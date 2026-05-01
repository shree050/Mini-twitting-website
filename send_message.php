<?php
include "../config/db.php";

if (session_status() === PHP_SESSION_NONE) session_start();

$sender = $_SESSION['user_id'];
$receiver = (int)$_POST['receiver_id'];
$message = trim($_POST['message']);

/* IMAGE UPLOAD */
if (!empty($_FILES['image']['name'])) {

    // clean filename
    $fileName = time() . "_" . basename($_FILES['image']['name']);

    // server path (where file is stored)
    $target = "../uploads/" . $fileName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {

        // IMPORTANT: browser path (this fixes broken image)
        $path = "/mini_sweeters_pro/uploads/" . $fileName;

        mysqli_query($conn,"
            INSERT INTO messages (sender_id, receiver_id, message, type)
            VALUES ($sender, $receiver, '$path', 'image')
        ");
    }

} elseif ($message !== '') {

    // safe text
    $safeMsg = mysqli_real_escape_string($conn, $message);

    mysqli_query($conn,"
        INSERT INTO messages (sender_id, receiver_id, message, type)
        VALUES ($sender, $receiver, '$safeMsg', 'text')
    ");
}

/* REDIRECT BACK */
header("Location: ../messages.php?user=$receiver");
exit;