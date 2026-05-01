<?php
include "config/db.php";
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: auth/login.php");
  exit;
}

$uid = (int)$_SESSION['user_id'];

/* 🔔 FETCH NOTIFICATIONS WITH ACTOR USERNAME */
$q = mysqli_query($conn,"
  SELECT n.*, u.username
  FROM notifications n
  JOIN users u ON u.id = n.actor_id
  WHERE n.user_id = $uid
  ORDER BY n.created_at DESC
");

/* ✅ MARK ALL AS READ */
mysqli_query($conn,"
  UPDATE notifications
  SET is_read = 1
  WHERE user_id = $uid
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Notifications | Mini Sweeters</title>
<link rel="stylesheet" href="assets/css/style.css">
<style>
.notif-card {
  padding: 14px;
  border-bottom: 1px solid #ddd;
}
.notif-unread {
  background: #f5f8fa;
  font-weight: bold;
}
.notif-icon {
  font-size: 18px;
  margin-right: 8px;
}
.notif-card a {
  color: inherit;
  text-decoration: none;
  display: block;
}
</style>
</head>
<body>

<header class="topbar">
  <div class="logo">Mini Sweeters</div>
  <div class="top-nav">
    <a href="home.php">Home</a>
    <a href="profile.php">Profile</a>
  </div>
</header>

<div class="container">
  <h2>Notifications</h2>

  <?php if (mysqli_num_rows($q) === 0) { ?>
    <p style="opacity:.6;">No notifications yet</p>
  <?php } ?>

  <?php while ($n = mysqli_fetch_assoc($q)) {

    /* 🔗 REDIRECT LOGIC — FIXED */
    if (
      $n['type'] === 'like' ||
      $n['type'] === 'comment' ||
      $n['type'] === 'follow'
    ) {
      $link = "profile.php?id=" . (int)$n['actor_id'];
    } elseif ($n['type'] === 'message') {
      $link = "messages.php?user=" . (int)$n['actor_id'];
    } else {
      $link = "#";
    }

    /* 🎯 ICONS */
    $icon = match ($n['type']) {
      'like'    => '❤️',
      'comment' => '💬',
      'follow'  => '👤',
      'message' => '📩',
      default   => '🔔'
    };

    $unreadClass = $n['is_read'] ? '' : 'notif-unread';
  ?>

  <div class="notif-card <?= $unreadClass ?>">
    <a href="<?= $link ?>">

      <span class="notif-icon"><?= $icon ?></span>

      <?php if ($n['type'] === 'like') { ?>
        @<?= htmlspecialchars($n['username']) ?> liked your tweet

      <?php } elseif ($n['type'] === 'comment') { ?>
        @<?= htmlspecialchars($n['username']) ?> commented on your tweet

      <?php } elseif ($n['type'] === 'follow') { ?>
        @<?= htmlspecialchars($n['username']) ?> followed you

      <?php } elseif ($n['type'] === 'message') { ?>
        New message from @<?= htmlspecialchars($n['username']) ?>
      <?php } ?>

      <div style="font-size:12px; color:#777; margin-top:4px;">
        <?= htmlspecialchars($n['created_at']) ?>
      </div>

    </a>
  </div>

  <?php } ?>
</div>

</body>
</html>