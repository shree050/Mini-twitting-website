<?php
include "../config/db.php";

/* ✅ make sure session exists */
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['user_id'])) {
  http_response_code(403);
  exit;
}

$uid = (int) $_SESSION['user_id'];
$tid = (int) ($_POST['tweet_id'] ?? 0);

if ($tid <= 0) {
  http_response_code(400);
  exit;
}

// check like
$check = mysqli_query(
  $conn,
  "SELECT id FROM likes WHERE user_id=$uid AND tweet_id=$tid"
);

if (mysqli_num_rows($check)) {

  // ❌ UNLIKE
  mysqli_query(
    $conn,
    "DELETE FROM likes WHERE user_id=$uid AND tweet_id=$tid"
  );

  $liked = false;

} else {

  // ❤️ LIKE
  mysqli_query(
    $conn,
    "INSERT INTO likes (user_id, tweet_id) VALUES ($uid, $tid)"
  );

  $liked = true;

  /* 🔔 ADD NOTIFICATION (ONLY ADDITION) */
  $tweet = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT user_id FROM tweets WHERE id=$tid")
  );

  if ($tweet && (int)$tweet['user_id'] !== $uid) {
    $owner_id = (int)$tweet['user_id'];

    mysqli_query($conn,"
      INSERT INTO notifications (user_id, actor_id, type, reference_id)
      VALUES ($owner_id, $uid, 'like', $tid)
    ");
  }
}

// get updated count
$res = mysqli_query(
  $conn,
  "SELECT COUNT(*) AS c FROM likes WHERE tweet_id=$tid"
);
$row = mysqli_fetch_assoc($res);

header("Content-Type: application/json");
echo json_encode([
  "liked" => $liked,
  "count" => (int)$row['c']
]);
exit;