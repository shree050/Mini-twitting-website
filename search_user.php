<?php
include "config/db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) exit;

$q = trim($_GET['q'] ?? '');
if ($q === '') exit;

$uid = $_SESSION['user_id'];

$res = mysqli_query(
  $conn,
  "SELECT id, name, username, profile
   FROM users
   WHERE username LIKE '%$q%'
   AND id != $uid
   LIMIT 6"
);

while ($u = mysqli_fetch_assoc($res)) {
?>
<a href="profile.php?id=<?= $u['id'] ?>" class="search-item">
  <img src="assets/profile_pics/<?= $u['profile'] ?>" alt="profile">
  <div>
    <div class="search-name"><?= htmlspecialchars($u['name']) ?></div>
    <div class="search-username">@<?= htmlspecialchars($u['username']) ?></div>
  </div>
</a>
<?php } ?>
