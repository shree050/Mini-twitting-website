<?php
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$uid = $_SESSION['user_id'];      // logged-in user
$pid = $_POST['user_id'];         // profile user

// check follow status
$check = mysqli_query(
    $conn,
    "SELECT id FROM follows 
     WHERE follower_id=$uid AND following_id=$pid"
);

if (mysqli_num_rows($check) > 0) {
    // UNFOLLOW
    mysqli_query(
        $conn,
        "DELETE FROM follows 
         WHERE follower_id=$uid AND following_id=$pid"
    );
} else {
    // FOLLOW
    mysqli_query(
        $conn,
        "INSERT INTO follows (follower_id, following_id)
         VALUES ($uid, $pid)"
    );

    // notification
    mysqli_query($conn,"
  INSERT INTO notifications (user_id, from_user_id, type, message)
  VALUES ($targetUser, $uid, 'follow', 'followed you')
");

}

header("Location: ../profile.php?id=$pid");
exit;
