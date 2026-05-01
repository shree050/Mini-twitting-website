<?php
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$uid = $_SESSION['user_id'];
$tweet_id = $_POST['tweet_id'];
$comment = $_POST['comment'];

// insert comment
mysqli_query(
    $conn,
    "INSERT INTO comments (user_id, tweet_id, comment)
     VALUES ($uid, $tweet_id, '$comment')"
);

// notify tweet owner (not self)
$owner = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT user_id FROM tweets WHERE id=$tweet_id")
);

if ($owner['user_id'] != $uid) {
    mysqli_query(
        $conn,
        "INSERT INTO notifications (user_id, type, message)
         VALUES (
           {$owner['user_id']},
           'comment',
           'Someone commented on your tweet'
         )"
    );
}

header("Location: ../home.php");
exit;
