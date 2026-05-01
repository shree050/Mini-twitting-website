<?php
include "config/db.php";
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: auth/login.php");
  exit;
}

$uid = (int)$_SESSION['user_id'];
$pid = isset($_GET['id']) ? (int)$_GET['id'] : $uid;

$is_own_profile = !isset($_GET['id']) || $pid === $uid;

$notif_count = mysqli_fetch_assoc(
  mysqli_query($conn,"
    SELECT COUNT(*) AS c
    FROM notifications
    WHERE user_id=$uid AND is_read=0
  ")
)['c'];

$msg_count = mysqli_fetch_assoc(
  mysqli_query($conn,"
    SELECT COUNT(*) AS c
    FROM messages
    WHERE receiver_id=$uid AND seen=0
  ")
)['c'];

$user = mysqli_fetch_assoc(
  mysqli_query($conn,"SELECT * FROM users WHERE id=$pid")
);

$post_count = mysqli_num_rows(mysqli_query(
  $conn,"SELECT id FROM tweets WHERE user_id=$pid"
));

$follower_count = mysqli_num_rows(mysqli_query(
  $conn,"SELECT id FROM follows WHERE following_id=$pid"
));

$following_count = mysqli_num_rows(mysqli_query(
  $conn,"SELECT id FROM follows WHERE follower_id=$pid"
));

$is_following = mysqli_num_rows(mysqli_query(
  $conn,"
  SELECT id FROM follows
  WHERE follower_id=$uid AND following_id=$pid
  "
));
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile | Mini Sweeters</title>

<style>
* {
  box-sizing: border-box;
  font-family: "Segoe UI", Arial, sans-serif;
}

body {
  margin: 0;
  background: #f5f7fa;
}

/* TOPBAR */
.topbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 20px;
  background: #000;
  color: #fff;
}

.logo {
  display: flex;
  align-items: center;
  gap: 8px;
}

.top-nav a {
  margin-left: 15px;
  color: white;
  text-decoration: none;
  font-weight: bold;
}

.badge {
  background: red;
  padding: 2px 6px;
  border-radius: 50%;
  font-size: 12px;
}

/* CONTAINER */
.container {
  max-width: 600px;
  margin: 20px auto;
}

/* PROFILE CARD */
.profile-card {
  background: #fff;
  padding: 25px;
  border-radius: 14px;
  text-align: center;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

/* PROFILE IMAGE FIX */
.profile-pic {
  width: 110px;
  height: 110px;
  border-radius: 50%;
  object-fit: cover;
  margin-bottom: 10px;
}

/* NAME */
.profile-card h2 {
  margin: 5px 0;
}

.profile-card p {
  color: #555;
}

/* STATS */
.profile-stats {
  display: flex;
  justify-content: space-around;
  margin-top: 15px;
}

.profile-stats div {
  font-size: 14px;
}

/* BUTTONS */
button, .btn-message {
  padding: 8px 16px;
  border-radius: 20px;
  border: none;
  cursor: pointer;
  text-decoration: none;
  background: #1da1f2;
  color: white;
  font-weight: bold;
}

button:hover, .btn-message:hover {
  background: #0d8ae5;
}

/* TWEETS */
.tweet {
  background: #fff;
  padding: 15px;
  border-radius: 12px;
  margin-top: 15px;
  box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}

.tweet img {
  border-radius: 50%;
}

.tweet p {
  margin-top: 10px;
}
</style>

<script src="assets/js/main.js" defer></script>
</head>

<body>

<header class="topbar">
  <div class="logo">
    <img src="assets/logo1.svg" width="30">
    <span>Mini Sweeters</span>
  </div>

  <div class="top-nav">
    <a href="home.php">Home</a>

    <a href="messages.php">
      Messages
      <?php if ($msg_count > 0) { ?>
        <span class="badge"><?= $msg_count ?></span>
      <?php } ?>
    </a>

    <a href="notifications.php">
      Notifications
      <?php if ($notif_count > 0) { ?>
        <span class="badge"><?= $notif_count ?></span>
      <?php } ?>
    </a>

    <a href="auth/logout.php">Logout</a>
  </div>
</header>

<div class="container">

<div class="profile-card">

  <img src="assets/profile_pics/<?= !empty($user['profile']) ? htmlspecialchars($user['profile']) : 'default.png' ?>" class="profile-pic">

  <h2><?= htmlspecialchars($user['name']) ?></h2>
  <p>@<?= htmlspecialchars($user['username']) ?></p>
  <p><?= htmlspecialchars($user['bio'] ?: "No bio added yet.") ?></p>

  <div class="profile-stats">
    <div><b><?= $post_count ?></b><br>Posts</div>
    <div><b><?= $follower_count ?></b><br>Followers</div>
    <div><b><?= $following_count ?></b><br>Following</div>
  </div>

  <div style="display:flex; gap:12px; justify-content:center; margin-top:15px;">

    <?php if ($is_own_profile) { ?>
      <a href="edit_profile.php" class="btn-message">Edit Profile</a>
    <?php } else { ?>

      <form action="actions/follow.php" method="post">
        <input type="hidden" name="user_id" value="<?= $pid ?>">
        <button type="submit">
          <?= $is_following ? "Unfollow" : "Follow" ?>
        </button>
      </form>

      <a href="messages.php?user=<?= $pid ?>" class="btn-message">
        Message
      </a>

    <?php } ?>

  </div>

</div>

<h3 style="margin-top:30px;">Posts</h3>

<?php
$tweets = mysqli_query($conn,"
SELECT tweets.*, users.name, users.username, users.profile
FROM tweets
JOIN users ON users.id = tweets.user_id
WHERE tweets.user_id=$pid
ORDER BY tweets.id DESC
");

while ($t = mysqli_fetch_assoc($tweets)) {
?>

<div class="tweet">

<div style="display:flex;align-items:center;gap:10px;">

<img src="assets/profile_pics/<?= !empty($t['profile']) ? htmlspecialchars($t['profile']) : 'default.png' ?>"
style="width:40px;height:40px;border-radius:50%;object-fit:cover;">

<div>
<b>
<?= htmlspecialchars($t['name']) ?>
<span style="color:#657786">@<?= htmlspecialchars($t['username']) ?></span>
</b>
<br>
<small style="color:#657786;">
<?= date("M d H:i", strtotime($t['created_at'])) ?>
</small>
</div>

</div>

<?php if($t['is_retweet'] == 1){ ?>
<div style="color:#657786;font-size:13px;margin-top:5px;">
🔁 Retweeted
</div>
<?php } ?>

<p><?= nl2br(htmlspecialchars($t['content'])) ?></p>

<?php if(!empty($t['image'])){ ?>
<img src="assets/tweet_images/<?= htmlspecialchars($t['image']) ?>"
style="width:100%;border-radius:10px;margin-top:10px;">
<?php } ?>

</div>

<?php } ?>

</div>

</body>
</html>